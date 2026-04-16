<?php

namespace Database\Seeders;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Seeder;

class NoticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        Notice::factory(5)->for($admin)->create();
        Notice::factory(3)->event()->for($admin)->create();
    }
}
