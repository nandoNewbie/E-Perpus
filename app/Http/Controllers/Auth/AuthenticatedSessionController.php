<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Jika yang login ternyata pustakawan, tendang keluar dari gerbang siswa
        if (Auth::user()->role === 'pustakawan') {
        Auth::logout();
        
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        
            return redirect()->route('login')->withErrors([
                'login_key' => 'Pustakawan harap login melalui halaman khusus Admin!'
            ]);
    }

    return redirect()->intended(route('dashboard', absolute: false));
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Menampilkan halaman login khusus Pustakawan
public function createAdmin()
{
    return view('auth.admin-login');
}

// Memproses data login Pustakawan
public function storeAdmin(LoginRequest $request)
{
    $request->authenticate();
    $request->session()->regenerate();

    // Menggunakan Auth:: resmi agar VS Code tidak bingung
    if (Auth::user()->role !== 'pustakawan') {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->withErrors([
            'login_key' => 'Akses ditolak. Anda bukan Pustakawan!'
        ]);
    }

    session([
        'admin_logged_in' => true,
        'admin_name'      => Auth::user()->name,
        'admin_email'     => Auth::user()->email,
    ]);

    return redirect()->route('admin.dashboard');
}
}

