<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $guestCountToday = Guest::whereDate('visit_date', today())->count();
        $totalReceptionists = User::where('role', 'resepsionis')->count();
        $averageRating = Feedback::avg('rating');

        return response()->json([
            'guest_count_today' => $guestCountToday,
            'total_receptionists' => $totalReceptionists,
            'average_rating' => round($averageRating, 1),
        ]);
    }
}
