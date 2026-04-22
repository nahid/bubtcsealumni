<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tagNames = [
            'Laravel', 'PHP', 'JavaScript', 'TypeScript', 'Vue', 'React',
            'Node', 'Python', 'DataScience', 'MachineLearning',
            'DevOps', 'Android', 'iOS', 'Flutter', 'Go', 'Rust',
            'QA', 'UIUX', 'ProductManagement', 'CyberSecurity',
        ];

        foreach ($tagNames as $tagName) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName],
            );
        }
    }
}
