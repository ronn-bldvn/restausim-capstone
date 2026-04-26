<?php

namespace App\Http\Controllers;

use App\Mail\SectionInviteMail;
use App\Models\EmailInvites;
use App\Models\Section;
use App\Models\SectionMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionInvitationController extends Controller
{
    public function sendInvite(Request $request, $section_id)
    {
        $request->validate([
            'emails' => 'required|json'
        ]);

        $section = Section::findOrFail($section_id);

        $faculty = User::find($section->user_id);

        // Optional: check ownership
        if ($section->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Decode JSON emails
        $emails = json_decode($request->emails, true);

        if (empty($emails) || !is_array($emails)) {
            return back()->with('error', 'Please provide at least one email address.');
        }

        $emails = array_map('trim', $emails);
        $emails = array_filter($emails); // remove empty strings

        $successCount = 0;
        $failedEmails = [];
        $alreadyInvited = [];
        $alreadyMembers = [];

        // Bulk fetch existing users
        $users = User::whereIn('email', $emails)->get()->keyBy('email');

        // Bulk fetch existing members
        $memberUserIds = SectionMember::where('section_id', $section->section_id)
            ->whereIn('user_id', $users->pluck('id'))
            ->pluck('user_id')
            ->toArray();

        // Bulk fetch existing invites
        $existingInvites = EmailInvites::where('section_id', $section->section_id)
            ->whereIn('email', $emails)
            ->where('expires_at', '>', now())
            ->pluck('email')
            ->toArray();

        DB::beginTransaction();

        try {
            foreach ($emails as $email) {
                // Validate email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $failedEmails[] = $email . ' (invalid format)';
                    continue;
                }

                // Already invited?
                if (in_array($email, $existingInvites)) {
                    $alreadyInvited[] = $email;
                    continue;
                }

                // Already member?
                if (isset($users[$email]) && in_array($users[$email]->id, $memberUserIds)) {
                    $alreadyMembers[] = $email;
                    continue;
                }

                try {
                    $token = Str::random(40);

                    // Delete expired invites for this email
                    EmailInvites::where('section_id', $section->section_id)
                        ->where('email', $email)
                        ->where('expires_at', '<=', now())
                        ->delete();

                    // Save invitation
                    $invite = EmailInvites::create([
                        'section_id' => $section->section_id,
                        'email' => $email,
                        'token' => $token,
                        'expires_at' => now()->addHours(24),
                    ]);

                    Mail::to($email)->send(new SectionInviteMail($invite, $section,  $faculty));

                    $successCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send invite to ' . $email . ': ' . $e->getMessage());
                    $failedEmails[] = $email;
                }
            }

            DB::commit();

            // Build response
            $messages = [];
            if ($successCount > 0) $messages[] = "✅ Successfully sent {$successCount} invitation(s)";
            if ($alreadyInvited) $messages[] = "⚠️ Already invited: " . implode(', ', $alreadyInvited);
            if ($alreadyMembers) $messages[] = "ℹ️ Already members: " . implode(', ', $alreadyMembers);
            if ($failedEmails) $messages[] = "❌ Failed to send: " . implode(', ', $failedEmails);

            $message = implode('. ', $messages);

            return $successCount > 0
                ? back()->with('success', $message)
                : back()->with('error', $message ?: 'No invitations were sent.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in sendInvite: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while sending invitations. Please try again.');
        }
    }

    public function acceptInvite($token)
    {
        $invite = EmailInvites::where('token', $token)->first();

        if (!$invite) {
            abort(404, 'Invalid invitation link.');
        }

        if (now()->greaterThan($invite->expires_at)) {
            // Delete expired invite
            $invite->delete();
            abort(410, 'This invitation link has expired.');
        }

        // Check if section still exists
        $section = Section::find($invite->section_id);
        if (!$section) {
            $invite->delete();
            abort(404, 'The section no longer exists.');
        }

        // If user already exists → join section directly
        $user = User::where('email', $invite->email)->first();

        if (!$user) {
            // If no account → redirect to register page
            return redirect()->route('register')
                ->with('emailToJoin', $invite->email)
                ->with('sectionToJoin', $invite->section_id)
                ->with('inviteToken', $token);
        }

        // Already joined? Skip
        $alreadyMember = SectionMember::where('user_id', $user->id)
            ->where('section_id', $invite->section_id)
            ->exists();

        if (!$alreadyMember) {
            SectionMember::create([
                'user_id' => $user->id,
                'section_id' => $invite->section_id,
            ]);
        }

        // Delete invite after use
        $invite->delete();

        return redirect()->route('student.section')
            ->with('success', 'You have successfully joined ' . $section->section_name . '!');
    }

    public function resendInvite($id)
    {
        $invite = EmailInvites::findOrFail($id);

        // Check if invite has expired
        if (now()->greaterThan($invite->expires_at)) {
            // Regenerate token and extend expiry
            $invite->token = Str::random(40);
            $invite->expires_at = now()->addHours(24);
            $invite->save();
        }

        $section = Section::findOrFail($invite->section_id);

        try {
            Mail::to($invite->email)->send(new SectionInviteMail($invite, $section));
            return back()->with('success', 'Invitation resent successfully to ' . $invite->email);
        } catch (\Exception $e) {
            Log::error('Failed to resend invite: ' . $e->getMessage());
            return back()->with('error', 'Failed to resend invitation. Please try again.');
        }
    }

    public function revokeInvite($id)
    {
        $invite = EmailInvites::findOrFail($id);
        $email = $invite->email;
        $invite->delete();

        return back()->with('success', 'Invitation to ' . $email . ' has been revoked.');
    }

    public function copyInviteLink($section_id)
    {
        $section = Section::findOrFail($section_id);

        // Generate a generic invite link (you might want to create a share code system)
        $link = route('section.join', ['code' => $section->share_code]);

        return response()->json([
            'success' => true,
            'link' => $link
        ]);
    }
}
