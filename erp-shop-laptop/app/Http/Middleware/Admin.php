<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            if (Auth::check()) {
                return redirect()->route('home')
                    ->with('error', 'Bạn không có quyền truy cập trang quản trị');
            }
            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        return $next($request);
    }
} 