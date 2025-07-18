<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceptionistMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role->name === 'receptionist') {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
