<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Guest;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guest = Guest::where('email', 'jane@example.com')->first();

        if ($guest) {
            Feedback::create([
                'guest_id' => $guest->id,
                'rating' => 5,
                'comment' => 'Great service!',
            ]);
        }
    }
}
