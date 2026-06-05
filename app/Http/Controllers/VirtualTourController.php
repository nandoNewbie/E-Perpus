<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VirtualTourController extends Controller
{
    public function index()
    {
        // Langsung arahkan ke file view blade biasa
        return view('virtual-tour');
    }
}
