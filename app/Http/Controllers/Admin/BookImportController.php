<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\BooksImport;
use Maatwebsite\Excel\Facades\Excel;

class BookImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file_buku' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            // Eksekusi Upsert Massal
            Excel::import(new BooksImport, $request->file('file_buku'));

            return redirect()->route('admin.books')->with('success', 'Data Excel buku berhasil di-import & di-upsert secara massal!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}