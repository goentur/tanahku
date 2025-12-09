<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, 'index'])->name('beranda.index');
Route::get('peta', [BerandaController::class, 'peta'])->name('beranda.peta');
Route::post('data-peta', [BerandaController::class, 'dataPeta'])->name('beranda.data-peta');
Route::post('data-bphtb', [BerandaController::class, 'dataBPHTB'])->name('beranda.data-bphtb');
Route::post('data-informasi', [BerandaController::class, 'dataInformasi'])->name('beranda.data-informasi');
// Route::get('/', function () {
//     return Redirect('login');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/app.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/transaksi.php';
