<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $feedback = Feedback::with('guest')->latest()->get();
        return response()->json($feedback);
    }

    // Additional methods like store/delete can be added if needed, 
    // but usually feedback is created by guests (public side) and read by admin.
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return response()->json(['message' => 'Feedback deleted']);
    }
}
