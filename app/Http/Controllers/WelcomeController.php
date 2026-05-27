<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        // 1. Hitung total seluruh koleksi buku di database
        $totalBooks = Book::count();

        // 2. Hitung total pengguna yang melakukan login/aktivitas PADA HARI INI
        $activeUsersToday = User::whereDate('last_login_at', Carbon::today())->count();

        return view('welcome', compact('totalBooks', 'activeUsersToday'));
    }
}
