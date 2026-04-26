<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Mail\NewAnnouncementMail;
use Illuminate\Support\Facades\Mail;
use App\Models\SectionMember;
use App\Models\User;

class AnnouncementController extends Controller
{
    public function index($section_id)
    {
        $section = Section::findOrFail($section_id);

        $announcements = Announcement::where('section_id', $section_id)
            ->where('is_archived', false)
            ->with('user')
            ->latest()
            ->get();

        return view('faculty.announcements', compact('section', 'announcements', 'section_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'announcements' => 'required|string',
            'section_id' => 'required|integer',
            'attachments' => 'nullable|array',
            'files.*' => 'nullable|file|max:10240' // 10MB limit
        ]);

        $announcement = Announcement::create([
            'content' => $request->announcements,
            'section_id' => $request->section_id,
            'user_id' => auth()->id(),
        ]);

        // Handle Links/YouTube
        if ($request->has('attachments')) {
            foreach ($request->attachments as $attachJson) {
                $data = json_decode($attachJson, true);

                // Check if decoding was successful and keys exist
                if (is_array($data) && isset($data['type'], $data['url'])) {
                    $title = null;

                    if ($data['type'] === 'youtube') {
                        $title = $this->getYoutubeTitle($data['url']);
                    }

                    $announcement->attachments()->create([
                        'type' => $data['type'],
                        'url' => $data['url'],
                        'title' => $title
                    ]);
                }
            }
        }

        // Handle File Uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('announcements/files', 'public');
                $announcement->attachments()->create([
                    'type' => 'file',
                    'url' => $path,
                    'title' => $file->getClientOriginalName()
                ]);
            }
        }

        // send emails to all students
        $section = Section::find($request->section_id);
        $members = SectionMember::where('section_id', $request->section_id)
            ->with('user')
            ->get();

        foreach ($members as $member) {
            if (!empty($member->user->email)) {
                Mail::to($member->user->email)
                    ->send(new NewAnnouncementMail($announcement, $section, $member->user, auth()->user()));
            }
        }

        return redirect()
            ->route('faculty.announcements', ['section_id' => $request->section_id])
            ->with('success', 'Announcement posted successfully');
    }

    public function update(Request $request, Announcement $announcement)
    {

        $request->validate([
            'content' => 'required|string',
        ]);

        $announcement->update([
            'content' => $request->input('content'),
        ]);

        return redirect()
            ->route('faculty.announcements', ['section_id' => $announcement->section_id])
            ->with('success', 'Announcement updated successfully');
    }

    public function destroy(Announcement $announcement)
    {
        $section_id = $announcement->section_id;
        $announcement->delete();

        return redirect()
            ->route('faculty.announcements', ['section_id' => $section_id])
            ->with('success', 'Announcement deleted successfully');
    }

    private function getYoutubeTitle(string $url): ?string
    {
        try {
            $oembedUrl = 'https://www.youtube.com/oembed?url=' . urlencode($url) . '&format=json';
            $response = file_get_contents($oembedUrl);

            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);
            return $data['title'] ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
