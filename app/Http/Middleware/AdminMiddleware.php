<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
public function handle(Request $request, Closure $next): Response
    {
    if (!Auth::check() || Auth::user()->role !== 'pustakawan') {
        return redirect()->route('admin.login')
            ->with('error', 'Silakan login terlebih dahulu untuk mengakses area pustakawan!');
    }

    return $next($request);
}
}
