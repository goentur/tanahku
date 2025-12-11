<?php

use App\Http\Controllers\BPHTBController;
use App\Http\Controllers\PetaIntegrasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('bphtb')->name('bphtb.')->group(function () {
  Route::get('/', [BPHTBController::class, 'index'])->name('index');
  Route::get('ppat', [BPHTBController::class, 'ppat'])->name('ppat');
  Route::get('berkas', [BPHTBController::class, 'berkas'])->name('berkas');
  Route::get('bpn', [BPHTBController::class, 'bpn'])->name('bpn');
  Route::get('selesai', [BPHTBController::class, 'selesai'])->name('selesai');
  Route::get('data-balikan', [BPHTBController::class, 'dataBalikan'])->name('data-balikan');
  Route::post('singkronisasi', [BPHTBController::class, 'singkronisasi'])->name('singkronisasi');
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
  Route::post('data-bphtb', [PetaIntegrasiController::class, 'dataBPHTB'])->name('beranda.data-bphtb');
  Route::post('data-informasi', [PetaIntegrasiController::class, 'dataInformasi'])->name('beranda.data-informasi');
});
Route::post('data-peta', [PetaIntegrasiController::class, 'dataPeta'])->name('beranda.data-peta');
