<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminPassword = (string) config('admin.password', '');
        if ($adminPassword === '') {
            $adminPassword = Str::password(24);
        }

        User::factory()->admin()->create([
            'name' => (string) config('admin.name', 'Primary Admin'),
            'email' => (string) config('admin.email', 'admin@example.com'),
            'mobile' => (string) config('admin.mobile', '01700000000'),
            'intake' => (int) config('admin.intake', 1),
            'password' => Hash::make($adminPassword),
        ]);

        if (app()->isProduction()) {
            return;
        }

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
