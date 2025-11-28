<?php

namespace App\Http\Controllers;

use App\Models\BPHTB\PPAT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BPHTBController extends Controller
{
  public function ppat(Request $request): View
  {
    $perPage = $request->get('per_page', 25);
    $search = $request->get('search', '');

    $query = PPAT::query();

    if ($search) {
      $query->where(function ($q) use ($search) {
        $q->where(DB::raw('LOWER(nama)'), 'LIKE', "%{$search}%")
          ->orWhere(DB::raw('LOWER(alamat)'), 'LIKE', "%{$search}%")
          ->orWhere(DB::raw('LOWER(telp)'), 'LIKE', "%{$search}%");
      });
    }

    $ppats = $query->orderBy('id', 'desc')->paginate((int) $perPage)->appends([
      'search' => $search,
      'per_page' => $perPage,
    ]);

    return view('bphtb.ppat.data', compact('ppats'));
  }
}
