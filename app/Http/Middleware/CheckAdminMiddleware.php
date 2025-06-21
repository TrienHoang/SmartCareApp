<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
class CheckAdminMiddleware
{

    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check()) {
            if (Auth::user()->role_id == 1) {
                return $next($request);
            } else {
                return redirect()->route('home')->with('message', 'Bạn không có quyền truy cập trang này!');
            }
        }else{
            return redirect()->route('auth')->with('message', 'Bạn cần đăng nhập để truy cập trang này!');
        }
    }
}
