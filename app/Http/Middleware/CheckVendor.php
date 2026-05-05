<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!in_array(session('user.role'), ['admin', 'vendor'])) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
