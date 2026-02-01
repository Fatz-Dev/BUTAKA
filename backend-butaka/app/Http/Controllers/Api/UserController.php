<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ApiResponse;

    // Data user. 
    public function index(Request $request): JsonResponse
    {
        $query = User::latest();

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();
        return $this->success($users);
    }

    // Tambah user baru.
    public function store(RegisterUserRequest $request): JsonResponse
    {
        // Validasi data
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);

        return $this->success($user, 'User berhasil ditambahkan', 201);
    }

    // Data user spesifik.
    public function show(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('User tidak ditemukan', 404);
        }

        return $this->success($user);
    }

    // Update user.
    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('User tidak ditemukan', 404);
        }

        $validated = $request->validate([
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => [Rule::in(['admin', 'resepsionis'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return $this->success($user, 'User berhasil diupdate');
    }

    // Hapus User
    public function destroy(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('User tidak ditemukan', 404);
        }

        if ($user->id === auth()->id()) {
            return $this->error('Anda tidak dapat menghapus diri sendiri', 400);
        }

        $user->delete();

        return $this->success(null, 'User berhasil dihapus');
    }
}
