<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BerandaController extends Controller
{
    public function __construct() {}

    public function index(): View
    {
        return view('beranda.index');
    }

    public function peta(): View
    {
        return view('beranda.peta');
    }
}
