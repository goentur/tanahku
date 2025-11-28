<?php

use App\Http\Controllers\BPHTBController;
use App\Http\Controllers\PetaIntegrasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('bphtb')->name('bphtb.')->group(function () {
  Route::get('/', [BPHTBController::class, 'index'])->name('profile.edit');
  Route::get('ppat', [BPHTBController::class, 'ppat'])->name('ppat');
});



Route::middleware(['auth', 'verified'])->group(function () {
  Route::get('/proxy/geoserver', function (Request $request) {
    $url = 'http://192.168.75.15/geoserver/bpn/wms?' . http_build_query($request->all());

    try {
      $response = Http::timeout(10)->get($url);
      return response($response->body(), $response->status())
        ->header('Content-Type', $response->header('Content-Type') ?? 'text/html');
    } catch (\Exception $e) {
      return response('Proxy error: ' . $e->getMessage(), 500);
    }
  })->name('proxy.geoserver');
  Route::get('peta-integrasi', [PetaIntegrasiController::class, 'peta'])->name('peta-integrasi');
});
