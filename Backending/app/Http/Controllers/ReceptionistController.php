<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ReceptionistController extends Controller
{
    public function index()
    {
        $receptionists = User::where('role', 'resepsionis')->get();
        return response()->json($receptionists);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $receptionist = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'resepsionis',
        ]);

        return response()->json($receptionist, 201);
    }

    public function show(User $receptionist)
    {
        if ($receptionist->role !== 'resepsionis') {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($receptionist);
    }

    public function update(Request $request, User $receptionist)
    {
        if ($receptionist->role !== 'resepsionis') {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => ['email', Rule::unique('users')->ignore($receptionist->id)],
            'password' => 'nullable|min:8',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $receptionist->update($validated);

        return response()->json($receptionist);
    }

    public function destroy(User $receptionist)
    {
        if ($receptionist->role !== 'resepsionis') {
            return response()->json(['message' => 'Not found'], 404);
        }

        $receptionist->delete();
        return response()->json(['message' => 'Receptionist deleted']);
    }
}
