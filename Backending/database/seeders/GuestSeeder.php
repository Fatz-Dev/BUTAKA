<?php

namespace Database\Seeders;

use App\Models\Guest;
use Illuminate\Database\Seeder;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guest::create([
            'name' => 'John Doe',
            'phone' => '08123456789',
            'email' => 'john@example.com',
            'purpose' => 'Business Inquiry',
            'visit_date' => now()->toDateString(),
            'visit_time' => now()->toTimeString(),
            'status' => 'pending',
        ]);

        Guest::create([
            'name' => 'Jane Smith',
            'phone' => '08987654321',
            'email' => 'jane@example.com',
            'purpose' => 'Meeting with HR',
            'visit_date' => now()->subDay()->toDateString(),
            'visit_time' => '10:00:00',
            'status' => 'completed',
        ]);
    }
}
