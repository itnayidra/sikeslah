<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check()) {
            if (Auth::user()->role === $role) {
                return $next($request);
            }

            // Jika pengguna memiliki peran yang salah
            return redirect()->route('home')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Jika pengguna belum login
        return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
    }
}
