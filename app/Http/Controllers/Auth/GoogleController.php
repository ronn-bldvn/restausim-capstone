<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailInvites;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirecttogoogle(Request $request)
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        
         // -------------------------------------------------
        // RESTRICT LOGIN TO CLSU DOMAIN ONLY
        // -------------------------------------------------
        $email = $googleUser->getEmail();
        $allowedDomain = 'clsu2.edu.ph';
    
        if (!str_ends_with($email, '@' . $allowedDomain)) {
            return redirect()->route('login')
                ->with('error', 'Only CLSU email accounts (@clsu2.edu.ph) are allowed to login.');
        }

        // -----------------------------------------------
        // 1. CHECK IF USER ALREADY EXISTS BY GOOGLE ID
        // -----------------------------------------------
        $findUser = User::where("google_id", $googleUser->getId())->first();

        if ($findUser) {

            // Update avatar on login
            $avatarUrl = $googleUser->getAvatar();
            $filename = 'user_' . uniqid() . '.png';
            $path = public_path('storage/profile_images/' . $filename);

            if (!file_exists(public_path('storage/profile_images'))) {
                mkdir(public_path('storage/profile_images'), 0777, true);
            }

            file_put_contents($path, file_get_contents($avatarUrl));

            $findUser->update([
                "profile_image" => $filename
            ]);

            Auth::login($findUser);

            $this->joinPendingInvitation($findUser);

            return redirect()->route("student.section");
        }

        // -----------------------------------------------
        // 2. IF USER DOES NOT EXIST — CREATE NEW USER
        // -----------------------------------------------
        $username = explode('@', $googleUser->getEmail())[0];

        if (User::where('username', $username)->exists()) {
            $username .= '_' . uniqid();
        }

        // Download avatar
        $avatarUrl = $googleUser->getAvatar();
        $filename = 'user_' . uniqid() . '.png';
        $path = public_path('storage/profile_images/' . $filename);

        if (!file_exists(public_path('storage/profile_images'))) {
            mkdir(public_path('storage/profile_images'), 0777, true);
        }

        file_put_contents($path, file_get_contents($avatarUrl));
        
        // -----------------------------------------------
        // CHECK IF EMAIL ALREADY EXISTS
        // -----------------------------------------------
        $emailUser = User::where('email', $googleUser->getEmail())->first();

        if ($emailUser) {
        
            // If email exists but Google not linked yet
            if (!$emailUser->google_id) {
        
                $emailUser->update([
                    'google_id' => $googleUser->getId()
                ]);
            }
        
            Auth::login($emailUser);
        
            $this->joinPendingInvitation($emailUser);
        
            return redirect()->route("student.section");
        }

        // Create user
        $user = User::create([
            "username"      => $username,
            "name"          => $googleUser->getName(),
            "email"         => $googleUser->getEmail(),
            "google_id"     => $googleUser->getId(),
            "profile_image" => $filename,
        ]);

        Auth::login($user); 

        $this->joinPendingInvitation($user);

        return redirect()->route("student.section");
    }

    private function joinPendingInvitation($user)
    {
        $invite = EmailInvites::where('email', $user->email)
            ->where('expires_at', '>', now())
            ->first();
    
        if ($invite) {
    
            // Add to pivot (section_members)
            \App\Models\SectionMember::firstOrCreate([
                'user_id'    => $user->id,
                'section_id' => $invite->section_id,
            ]);
    
            // Delete invite after successful join
            $invite->delete();
        }
    }

}
