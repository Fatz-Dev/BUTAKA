<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'avatar' => null,
            'email' => 'admin@butaka.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Resepsionis users
        User::create([
            'name' => 'Resepsionis 1',
            'avatar' => null,
            'email' => 'resepsionis1@butaka.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'resepsionis',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Resepsionis 2',
            'avatar' => null,
            'email' => 'resepsionis2@butaka.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'resepsionis',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Resepsionis Tidak Aktif',
            'avatar' => null,
            'email' => 'resepsionis.inactive@butaka.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'resepsionis',
            'is_active' => false,
        ]);
    }
}
