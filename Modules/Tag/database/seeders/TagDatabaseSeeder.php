<?php

namespace Modules\Tag\database\seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Modules\Genres\Models\Genres;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Modules\Tag\Models\Tag;

class TagDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();


        $avatarPath = config('app.avatar_base_path');

        $tags = [
            [
                'name' => 'Korean',
                'slug' => 'korean',
                'file_url' =>  '/dummy-images/genre/action_genre.png',
                'description' => 'Korean movies',
                'status' => 1,

            ],
            [
                'name' => 'Indian',
                'file_url' => '/dummy-images/genre/animation_genre.png',
                'slug' => 'indian',
                'description' => 'Indian movies',
                'status' => 1,
            ],

            [
                'name' => 'Chinese',
                'file_url' => '/dummy-images/genre/comedy_genre.png',
                'slug' => 'chinese',
                'description' => 'Chinese movies',
                'status' => 1,
            ],

        ];

        if (env('IS_DUMMY_DATA')) {
            foreach ($tags as $tag) {
                $posterPath = $tag['file_url'] ?? null;

                $tag = Tag::create(Arr::except($tag, ['file_url']));
                if (isset($posterPath)) {
                    $posterUrl = $this->uploadToSpaces($posterPath);
                    if ($posterUrl) {
                        $tag->file_url = extractFileNameFromUrl($posterUrl);
                    }
                }

                $tag->save();
            }

            Schema::enableForeignKeyConstraints();
        }
    }

    private function uploadToSpaces($publicPath)
    {
        $localFilePath = public_path($publicPath);
        $remoteFilePath = 'streamit-laravel/' . basename($publicPath);

        if (file_exists($localFilePath)) {
            // Get the active storage disk from the environment
            $disk = env('ACTIVE_STORAGE', 'local');

            if ($disk === 'local') {
                // Store in the public directory for local storage
                Storage::disk($disk)->put('public/' . $remoteFilePath, file_get_contents($localFilePath));
                return asset('storage/' . $remoteFilePath);
            } else {
                // Upload to the specified storage disk
                Storage::disk($disk)->put($remoteFilePath, file_get_contents($localFilePath));
                return Storage::disk($disk)->url($remoteFilePath);
            }
        }

        return false;

    }
}
