<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login? lempar ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Login tapi bukan admin? forbidden
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Khusus admin.');
        }

        return $next($request);
    }
}
