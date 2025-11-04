<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@donasi.test'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'phone' => '08123456789',
                'address' => 'Jalan Admin, Jakarta',
            ]
        );

        $admin->assignRole('super_admin');
    }
}
