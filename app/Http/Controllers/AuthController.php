<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SectionMember;
use App\Models\EmailInvites;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!Str::endsWith($value, '@clsu2.edu.ph')) {
                        $fail('The email must be a @clsu2.edu.ph.');
                    }
                },
            ],
            'username' => 'required|string|max:255|min:5|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Make sure OTP was verified for this exact email
        if (
            !session('otp_verified') ||
            session('otp_verified_email') !== $request->email
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'email' => 'Please verify your email with OTP first.'
                ]);
        }

        // Generate unique profile image
        $folder = storage_path('app/public/profile_images');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        $imageName = 'user_' . time() . '_' . rand(1000, 9999) . '.png';
        $destination = $folder . '/' . $imageName;

        // Create 200x200 PNG
        $img = imagecreatetruecolor(200, 200);

        // Random background color
        $bgColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
        imagefilledrectangle($img, 0, 0, 200, 200, $bgColor);

        // White text color
        $textColor = imagecolorallocate($img, 255, 255, 255);

        // User initials
        $initials = strtoupper(substr($request->name, 0, 1));

        // Path to TTF font
        $font = storage_path('app/public/fonts/arial.ttf');
        $fontSize = 60;

        // Center the initials
        $bbox = imagettfbbox($fontSize, 0, $font, $initials);
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];
        $x = (200 - $textWidth) / 2;
        $y = (200 + $textHeight) / 2;

        imagettftext($img, $fontSize, 0, $x, $y, $textColor, $font, $initials);
        imagepng($img, $destination);
        imagedestroy($img);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'role' => 'student',
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'profile_image' => $imageName,
        ]);

        // Auto-join section if invited
        if ($request->input('section_to_join')) {
            SectionMember::create([
                'user_id' => $user->id,
                'section_id' => $request->input('section_to_join'),
            ]);

            EmailInvites::where('section_id', $request->input('section_to_join'))
                ->where('email', $user->email)
                ->delete();

            $message = 'Account created and you have joined the section successfully!';
        } else {
            $message = 'Account created successfully!';
        }

        // Clear OTP session after success
        session()->forget([
            'otp',
            'otp_email',
            'otp_expires_at',
            'otp_last_sent_at',
            'otp_verified',
            'otp_verified_email',
        ]);

        Auth::login($user);

        return redirect()->route('student.section')->with('success', $message);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            if (!Str::endsWith($request->login, ['@clsu2.edu.ph', '@gmail.com'])) {
                return back()
                    ->withInput($request->only('login'))
                    ->with('error', 'Only @clsu2.edu.ph or @gmail.com accounts are allowed.');
            }
        }

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (!$user) {
            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Invalid email or password!');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('login'))
                ->with('error', 'Incorrect password!');
        }

        Auth::login($user);

        session()->flash('success', 'Welcome back, ' . $user->username . '!');

        if ($user->role === 'faculty') {
            return redirect()->route('faculty.dashboard');
        } elseif ($user->role === 'student') {
            return redirect()->route('student.section');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } else {
            return view('auth.auth');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->flash('success', 'Logout Successful!');

        return redirect()->route('login');
    }

    public function showLoginForm()
    {
        return view('auth.auth');
    }

    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email']
        ]);

        $email = strtolower(trim($request->email));

        if (!Str::endsWith($email, '@clsu2.edu.ph')) {
            return response()->json([
                'success' => false,
                'error' => 'Only CLSU email allowed.'
            ], 422);
        }

        // Prevent rapid repeated sending
        $cooldownSeconds = 60;
        $lastSentAt = session('otp_last_sent_at');

        if (
            session('otp_email') === $email &&
            $lastSentAt &&
            now()->diffInSeconds(\Carbon\Carbon::parse($lastSentAt)) < $cooldownSeconds
        ) {
            $remaining = $cooldownSeconds - now()->diffInSeconds(\Carbon\Carbon::parse($lastSentAt));

            return response()->json([
                'success' => false,
                'error' => "Please wait {$remaining} seconds before requesting a new OTP."
            ], 429);
        }

        $otp = rand(100000, 999999);

        session([
            'otp' => (string) $otp,
            'otp_email' => $email,
            'otp_expires_at' => now()->addMinutes(5)->toDateTimeString(),
            'otp_last_sent_at' => now()->toDateTimeString(),
            'otp_verified' => false,
            'otp_verified_email' => null,
        ]);

        Mail::send('emails.otpVerification', [
            'otp' => $otp,
            'email' => $email,
        ], function ($msg) use ($email) {
            $msg->to($email)
                ->subject('RestauSim Email Verification');
        });

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully.'
        ]);
    }

    public function verifyOTP(Request $request)
    {
        try {
            $request->validate([
                'otp' => ['required', 'digits:6'],
                'email' => ['required', 'email'],
            ]);

            $email = strtolower(trim($request->email));

            if (session('otp_email') !== $email) {
                return response()->json([
                    'success' => false,
                    'error' => 'OTP email does not match.'
                ], 422);
            }

            if (!session('otp') || !session('otp_expires_at')) {
                return response()->json([
                    'success' => false,
                    'error' => 'No OTP found. Please request a new one.'
                ], 422);
            }

            if (now()->greaterThan(\Carbon\Carbon::parse(session('otp_expires_at')))) {
                session()->forget(['otp', 'otp_email', 'otp_expires_at']);

                return response()->json([
                    'success' => false,
                    'error' => 'OTP has expired. Please request a new one.'
                ], 422);
            }

            if ((string) $request->otp !== (string) session('otp')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid OTP'
                ], 422);
            }

            session([
                'otp_verified' => true,
                'otp_verified_email' => $email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully'
            ]);
        } catch (\Throwable $e) {
            \Log::error('OTP verify failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Verification failed',
            ], 500);
        }
    }
}