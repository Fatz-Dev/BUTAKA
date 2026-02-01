<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FeedbackController extends Controller
{
    use ApiResponse;

    // Data Feedback
    public function index(): JsonResponse
    {
        $feedback = Feedback::latest()->get();
        return $this->success($feedback);
    }

    // Tambah Feedback
    public function store(StoreFeedbackRequest $request): JsonResponse
    {
        $feedback = Feedback::create($request->validated());

        return $this->success($feedback, 'Terima kasih telah memberikan umpan balik!', 201);
    }

    // Data Feedback spesifik
    public function show(string $id): JsonResponse
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return $this->error('Feedback tidak ditemukan', 404);
        }

        return $this->success($feedback);
    }

    // Hapus Feedback
    public function destroy(string $id): JsonResponse
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return $this->error('Feedback tidak ditemukan', 404);
        }

        $feedback->delete();

        return $this->success(null, 'Feedback berhasil dihapus');
    }
}
