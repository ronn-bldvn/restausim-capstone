<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }

            $rules = [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|email|unique:users,email,' . $user->id,
                'profile_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:5120', // 5MB
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $user->name = $request->input('name');
            $user->username = $request->input('username');
            $user->email = $request->input('email');

            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');

                if (!$file->isValid()) {
                    return response()->json(['success' => false, 'message' => 'Uploaded file is not valid.'], 400);
                }

                // Delete old profile image if it exists
                if ($user->profile_image && Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
                    Storage::disk('public')->delete('profile_images/' . $user->profile_image);
                }

                $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $path = $file->storeAs('profile_images', $filename, 'public');

                if (!$path) {
                    Log::error("Failed to store uploaded profile image for user {$user->id}");
                    return response()->json(['success' => false, 'message' => 'Failed to store the uploaded file.'], 500);
                }

                $user->profile_image = $filename;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $user->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }

            $rules = [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Check if current password is correct
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Current password is incorrect.'], 422);
            }

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            Log::info("Password changed successfully for user {$user->id}");

            return response()->json(['success' => true, 'message' => 'Password changed successfully']);

        } catch (\Exception $e) {
            Log::error('Password change error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function downloadData()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }

            // Prepare user data
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'profile_image' => $user->profile_image,
                'export_date' => now()->toDateTimeString(),
            ];

            // Convert to JSON
            $jsonData = json_encode($userData, JSON_PRETTY_PRINT);

            Log::info("Profile data downloaded by user {$user->id}");

            return response($jsonData)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="profile_data_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.json"');

        } catch (\Exception $e) {
            Log::error('Download data error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
            }

            $rules = [
                'password' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            // Check if password is correct
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Password is incorrect.'], 422);
            }

            // Delete profile image if it exists
            if ($user->profile_image && Storage::disk('public')->exists('profile_images/' . $user->profile_image)) {
                Storage::disk('public')->delete('profile_images/' . $user->profile_image);
            }

            $userId = $user->id;

            // Log before deletion
            Log::info("User account deletion initiated", ['user_id' => $userId, 'email' => $user->email]);

            // Delete the user account
            $user->delete();

            // Logout the user
            Auth::logout();

            Log::info("User account deleted successfully", ['user_id' => $userId]);

            return response()->json(['success' => true, 'message' => 'Account deleted successfully']);

        } catch (\Exception $e) {
            Log::error('Account deletion error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
}

?>
