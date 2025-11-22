<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @param  string|null  $guard
     */
    public function handle(Request $request, Closure $next, string $role, string $guard = null): Response
    {
        $guard = $guard ?? 'web'; // default ke web jika guard tidak dikirim
        // Cek apakah pengguna sudah login sesuai guard
        if (!Auth::guard($guard)->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah pengguna memiliki role yang sesuai
        if (Auth::guard($guard)->user()->role !== $role) {
            return redirect()->route('guest-index')
                ->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
