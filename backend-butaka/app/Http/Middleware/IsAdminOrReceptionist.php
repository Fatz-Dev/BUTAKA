<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminOrReceptionist
{
    /**
     * Handle untuk admin atau resepsionis.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            !$request->user() ||
            (!$request->user()->isAdmin() && !$request->user()->isResepsionis())
        ) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin or Receptionist access required.',
            ], 403);
        }

        return $next($request);
    }
}
