<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Temples',
                'slug' => 'temples',
                'description' => 'Ancient historical and religious temples across Cambodia.',
            ],
            [
                'name' => 'Beaches',
                'slug' => 'beaches',
                'description' => 'Tropical coastal beaches and pristine islands.',
            ],
            [
                'name' => 'Nature',
                'slug' => 'nature',
                'description' => 'National parks, waterfalls, mountains, and wildlife sanctuaries.',
            ],
            [
                'name' => 'Cultural & Historical',
                'slug' => 'cultural-historical',
                'description' => 'Museums, monuments, and cultural heritage sites.',
            ],
            [
                'name' => 'Eco-Tourism',
                'slug' => 'eco-tourism',
                'description' => 'Community-based eco-tourism and outdoor conservation areas.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'description' => $cat['description']]
            );
        }
    }
}
