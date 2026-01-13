<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::latest()->get();
        return response()->json($guests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'purpose' => 'required|string',
            'visit_date' => 'required|date',
            'visit_time' => 'required',
        ]);

        $guest = Guest::create($validated);

        return response()->json($guest, 201);
    }

    public function show(Guest $guest)
    {
        return response()->json($guest);
    }

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'nullable|email',
            'purpose' => 'string',
            'visit_date' => 'date',
            'visit_time' => 'string',
            'status' => 'in:pending,completed',
        ]);

        $guest->update($validated);

        return response()->json($guest);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();
        return response()->json(['message' => 'Guest deleted']);
    }
}
