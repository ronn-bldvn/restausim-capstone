<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserProfileImageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $folder = storage_path('app/public/profile_images');

        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        foreach ($users as $user) {
            $imageName = 'user_' . $user->id . '_' . time() . '.png';
            $destination = $folder . '/' . $imageName;

            // Create a 200x200 PNG image
            $img = imagecreatetruecolor(200, 200);

            // Random background color
            $bgColor = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));
            imagefilledrectangle($img, 0, 0, 200, 200, $bgColor);

            // White text color
            $textColor = imagecolorallocate($img, 255, 255, 255);

            // Initials (first 2 letters)
            $initials = strtoupper(substr($user->name, 0, 2));

            // Path to a TTF font
            $font = public_path('fonts/arial.ttf'); // make sure this exists

            // Font size
            $fontSize = 60;

            // Calculate bounding box for centering
            $bbox = imagettfbbox($fontSize, 0, $font, $initials);
            $textWidth = $bbox[2] - $bbox[0];
            $textHeight = $bbox[1] - $bbox[7];

            $x = (200 - $textWidth) / 2;
            $y = (200 + $textHeight) / 2;

            // Add initials
            imagettftext($img, $fontSize, 0, $x, $y, $textColor, $font, $initials);

            // Save image
            imagepng($img, $destination);
            imagedestroy($img);

            // Update user record
            $user->update([
                'profile_image' => $imageName,
            ]);
        }

        $this->command->info('All users now have unique profile images!');
    }
}
