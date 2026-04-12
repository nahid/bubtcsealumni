<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@bubtalumni.test',
            'mobile' => '01700000000',
            'intake' => 1,
        ]);

        // Verified alumni
        $verifiedUsers = User::factory(20)->create();

        // A few pending users referencing two verified alumni each
        User::factory(5)->withReference(
            $verifiedUsers->random()->email,
            $verifiedUsers->random()->email,
        )->create();

        // Seed well-known tags
        $tagNames = ['Laravel', 'PHP', 'JavaScript', 'DataScience', 'MachineLearning', 'DevOps', 'Android', 'iOS', 'React', 'Python'];
        foreach ($tagNames as $tagName) {
            Tag::create(['name' => $tagName, 'slug' => Str::slug($tagName)]);
        }

        // Subscribe some users to random tags
        $tags = Tag::all();
        $verifiedUsers->each(function (User $user) use ($tags) {
            $user->subscribedTags()->attach($tags->random(rand(1, 4)));
        });

        $this->call([
            JobPostSeeder::class,
            NoticeSeeder::class,
        ]);
    }
}
