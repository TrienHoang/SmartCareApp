<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, \Closure $next, $role)
    {
        if (!\Auth::check()) {
            return redirect()->route('login');
        }

        $user = \Auth::user();

        // Nếu quan hệ là belongsTo Role => role.name là tên quyền
        if (!isset($user->role) || strtolower($user->role->name) !== strtolower($role)) {
            abort(403, 'Không có quyền truy cập.');
        }

        return $next($request);
    }
}