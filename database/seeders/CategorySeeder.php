<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Musik',
                'description' => 'Konser musik, festival, dan pertunjukan live'
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Pertandingan olahraga dan turnamen'
            ],
            [
                'name' => 'Seni & Budaya',
                'description' => 'Pameran seni, teater, dan pertunjukan budaya'
            ],
            [
                'name' => 'Seminar',
                'description' => 'Workshop, konferensi, dan seminar'
            ],
            [
                'name' => 'Festival',
                'description' => 'Festival makanan, film, dan acara tematik'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
