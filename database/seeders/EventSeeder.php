<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Soundfest 2023',
                'description' => ' Festival ini menampilkan berbagai musisi dari berbagai genre, baik dari dalam maupun luar negeri, dan dikenal dengan suasana yang meriah serta pengalaman yang tak terlupakan bagi para pecinta musik.',
                'image' => 'img-events/soundfest.jpg',
                'event_date' => now()->addDays(10),
                'location' => 'Parking Ground Summarecon Mall Bekasi',
                'price' => 250000,
                'quota' => 500,
                'remaining_quota' => 500,
                'status' => 'active',
            ],
            [
                'title' => 'Soundfest 2025 EXPERINCE THE MAGIC art exhibition',
                'description' => 'Soundfest 2025 dengan bangga mempersembahkan pameran seni "EXPERIENCE THE MAGIC". Selami dunia imajinasi dan keajaiban melalui karya seni visual yang memukau. Rasakan pengalaman multisensori yang akan membawa Anda pada perjalanan artistik tak terlupakan.',
                'image' => 'img-events/soundfest-2.jpg',
                'event_date' => now()->addDays(20),
                'location' => 'Parking Ground Summarecon Mall Bekasi',
                'price' => 150000,
                'quota' => 200,
                'remaining_quota' => 200,
                'status' => 'active',
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
