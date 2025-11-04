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
                'name' => 'Fakir Miskin',
                'slug' => 'fakir-miskin',
                'description' => 'Bantuan untuk orang fakir dan miskin yang membutuhkan',
                'icon' => '🤝',
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan',
                'slug' => 'pendidikan',
                'description' => 'Beasiswa dan bantuan pendidikan untuk anak-anak',
                'icon' => '📚',
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan',
                'slug' => 'kesehatan',
                'description' => 'Bantuan kesehatan dan pengobatan',
                'icon' => '🏥',
                'is_active' => true,
            ],
            [
                'name' => 'Muallaf',
                'slug' => 'muallaf',
                'description' => 'Bantuan untuk mualaf (orang yang baru masuk Islam)',
                'icon' => '✨',
                'is_active' => true,
            ],
            [
                'name' => 'Pembebasan Utang',
                'slug' => 'pembebasan-utang',
                'description' => 'Bantuan untuk melepaskan orang dari beban utang',
                'icon' => '💳',
                'is_active' => true,
            ],
            [
                'name' => 'Fisabilillah',
                'slug' => 'fisabilillah',
                'description' => 'Dukungan untuk jalan Allah dan pengembangan agama',
                'icon' => '🕌',
                'is_active' => true,
            ],
            [
                'name' => 'Musafir',
                'slug' => 'musafir',
                'description' => 'Bantuan untuk musafir (orang yang sedang dalam perjalanan)',
                'icon' => '✈️',
                'is_active' => true,
            ],
            [
                'name' => 'Pemeliharaan Yatim',
                'slug' => 'pemeliharaan-yatim',
                'description' => 'Bantuan untuk memelihara dan mendukung anak yatim',
                'icon' => '👶',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
