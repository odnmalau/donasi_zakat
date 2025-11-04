<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a petugas_yayasan user
        $petugas = User::firstOrCreate(
            ['email' => 'petugas@donasi.test'],
            [
                'name' => 'Petugas Yayasan',
                'password' => bcrypt('password'),
                'phone' => '08987654321',
                'address' => 'Jalan Yayasan, Jakarta',
            ]
        );
        $petugas->assignRole('petugas_yayasan');

        // Create 5 active campaigns
        Campaign::factory()
            ->count(5)
            ->active()
            ->for($petugas, 'creator')
            ->create();

        // Create 3 completed campaigns
        Campaign::factory()
            ->count(3)
            ->completed()
            ->for($petugas, 'creator')
            ->create();

        // Create 2 draft campaigns
        Campaign::factory()
            ->count(2)
            ->state(['status' => 'draft'])
            ->for($petugas, 'creator')
            ->create();
    }
}
