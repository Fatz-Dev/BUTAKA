<?php

use Illuminate\Support\Facades\Route;

// Catch-all: semua URL selain /api diarahkan ke Vue SPA
Route::get('/{any?}', function () {
    $path = public_path('index.html');
    if (file_exists($path)) {
        return response()->file($path, ['Content-Type' => 'text/html']);
    }
    // Fallback jika belum build Vue
    return response('Vue SPA belum di-build. Jalankan: cd frontend-butaka && npm run build', 404);
})->where('any', '^(?!api).*$');
