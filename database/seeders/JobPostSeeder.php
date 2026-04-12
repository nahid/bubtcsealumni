<?php

namespace Database\Seeders;

use App\Models\JobPost;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::all();
        $verifiedUsers = User::where('status', 'verified')->get();

        JobPost::factory(15)
            ->recycle($verifiedUsers)
            ->create()
            ->each(function (JobPost $jobPost) use ($tags) {
                $jobPost->tags()->attach($tags->random(rand(1, 3)));
            });
    }
}
