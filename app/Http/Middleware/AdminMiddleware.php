<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah session login admin TIDAK ADA
        if (!session()->has('admin_logged_in') || session('admin_logged_in') !== true) {
            // Jika tidak ada, tendang ke halaman login admin dengan pesan error
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu untuk mengakses area pustakawan!');
        }

        return $next($request);
    }
}
