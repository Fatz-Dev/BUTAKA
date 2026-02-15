<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get visitors who have completed their visit
        $completedVisitors = Visitor::where('status', 'selesai')->pluck('id')->toArray();

        // If no visitors, we can't seed feedback effectively linked to them
        if (empty($completedVisitors)) {
            return;
        }

        // Generate feedback for about 70% of completed visits
        foreach ($completedVisitors as $visitorId) {
            if ($faker->boolean(70)) {
                Feedback::create([
                    'visitor_id' => $visitorId,
                    'rating' => $faker->numberBetween(3, 5), // Mostly positive feedback
                    'comment' => $faker->optional(0.8)->sentence(10), // 80% have comments
                    'created_at' => $faker->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        }
    }
}
