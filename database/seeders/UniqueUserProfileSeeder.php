<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UniqueUserProfileSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $users = User::all();

        $folder = storage_path('app/public/profile_images');
        if (!file_exists($folder)) {
            mkdir($folder, 0755, true);
        }

        foreach ($users as $user) {
            $imageName = 'user_' . $user->id . '_' . time() . '.jpg';
            $faker->image($folder, 200, 200, null, false);

            $latestImage = collect(glob($folder . '/*.jpg'))->sortByDesc('filemtime')->first();
            rename($latestImage, $folder . '/' . $imageName);

            $user->update([
                'profile_image' => $imageName,
            ]);
        }

        $this->command->info('All users now have unique profile images!');
    }
}


?>
