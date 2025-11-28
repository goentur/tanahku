<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PetaIntegrasiController extends Controller
{
    public function peta(): View
    {
        return view('bphtb.peta-integrasi');
    }
}
