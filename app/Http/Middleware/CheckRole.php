<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah role pengguna sesuai dengan yang diminta rute
        if (Auth::user()->role !== $role) {
            // Jika siswa coba-coba masuk ke rute pustakawan, lempar ke dashboard biasa
            if (Auth::user()->role !== 'pustakawan' && $role === 'pustakawan') {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak! Halaman tersebut khusus Pustakawan.');
            }
            
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}