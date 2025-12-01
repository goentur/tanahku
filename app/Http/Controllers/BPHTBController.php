<?php

namespace App\Http\Controllers;

use App\Enums\Dat\StatusBerkas;
use App\Enums\Dat\StatusLampiran;
use App\Enums\Dat\StatusPenelitian;
use App\Enums\Dat\StatusPengendalian;
use App\Enums\Dat\StatusSelesai;
use App\Enums\Dat\StatusVerifikasi;
use App\Models\BPHTB\DatPerolehanHak;
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

  public function berkas(Request $request): View
  {
    $perPage = (int) $request->get('per_page', 25);
    $status = (string) $request->get('status', '0');

    $query = DatPerolehanHak::with('refJph', 'ppat', 'wpPenerimaHak');

    if ($status !== '') {
      $this->posisiBerkas($query, $status);
    }

    $datas = $query->where('tahun_perolehan', date('Y'))->orderBy('id', 'desc')
      ->paginate($perPage)
      ->appends([
        'status' => $status,
        'per_page' => $perPage,
      ]);

    return view('bphtb.berkas.data', compact('datas'));
  }

  public function posisiBerkas($query, $value)
  {
    switch ($value) {
      case '0':
        $query->where('status_berkas', StatusBerkas::Konsep);
        break;

      case '1':
        $query->where('status_berkas', StatusBerkas::Baru)
          ->where('status_penelitian', StatusPenelitian::Belum);
        break;

      case '2':
        $query->whereIn('status_berkas', [StatusBerkas::Koreksi, StatusBerkas::Proses])
          ->whereIn('status_penelitian', [StatusPenelitian::Proses])
          ->where('status_verifikasi', StatusVerifikasi::Belum);
        break;

      case '3':
        $query->whereIn('status_berkas', [StatusBerkas::Baru, StatusBerkas::Koreksi, StatusBerkas::Proses])
          ->where('status_penelitian', StatusPenelitian::Selesai)
          ->where('status_verifikasi', StatusVerifikasi::Belum);
        break;

      case '31':
        $query->where('status_verifikasi', StatusVerifikasi::Selesai);
        break;

      case '32':
        $query->where('status_verifikasi', StatusVerifikasi::Tolak);
        break;

      case '4':
        $query->whereHas('sptpd', fn($q) => $q->whereDoesntHave('sspd'));
        break;

      case '5':
        $query->whereHas('sptpd.sspd')
          ->where('status_pengendalian', StatusPengendalian::Belum);
        break;

      case '6':
        $query->where('status_pengendalian', StatusPengendalian::Pemeriksaan)
          ->whereDoesntHave('datPemeriksaan');
        break;

      case '7':
        $query->where('status_pengendalian', StatusPengendalian::Pemeriksaan)
          ->whereHas('skpdkb', fn($q) => $q->whereDoesntHave('sspd'));
        break;

      case '8':
        $query->where('status_berkas', StatusBerkas::Selesai)
          ->whereIn('status_selesai', [StatusSelesai::Belum, StatusSelesai::Selesai]);
        break;

      case '9':
        $query->where(function ($q) {
          $q->whereIn('status_berkas', [StatusBerkas::Koreksi])
            ->orWhereIn('status_lampiran', [StatusLampiran::Koreksi])
            ->orWhereIn('status_penelitian', [StatusPenelitian::Koreksi]);
        });
        break;

      case '10':
        $query->where(function ($q) {
          $q->whereIn('status_berkas', [StatusBerkas::Selesai, StatusBerkas::Tolak, StatusBerkas::Batal])
            ->orWhereIn('status_penelitian', [StatusPenelitian::Tolak, StatusPenelitian::Tunda, StatusPenelitian::Batal])
            ->orWhereIn('status_selesai', [StatusSelesai::Selesai, StatusSelesai::Tolak, StatusSelesai::Tunda, StatusSelesai::Batal]);
        });
        break;
    }
  }
  public function bpn(Request $request): View
  {
    $perPage = (int) $request->get('per_page', 25);
    $search = trim((string) $request->get('search', ''));

    $query = DatPerolehanHak::with('refJph', 'ppat', 'wpPenerimaHak', 'sptpd')
      ->whereHas('sptpd', function ($q) {
        $q->whereNotNull('tgl_akses_bpn')
          ->whereNull('tgl_selesai_bpn');
      })
      ->where('tahun_perolehan', date('Y'));

    if ($search !== '') {
      $query->where(function ($q) use ($search) {
        $q->whereHas('wpPenerimaHak', fn($wp) => $wp->where('nama', 'LIKE', "%{$search}%"))
          ->orWhereHas('ppat', fn($ppat) => $ppat->where('nama', 'LIKE', "%{$search}%"));
      });
    }

    $datas = $query->orderBy('id', 'desc')
      ->paginate($perPage)
      ->appends(array_filter([
        'search' => $search,
        'per_page' => $perPage,
      ]));

    return view('bphtb.bpn.data', compact('datas'));
  }
  public function selesai(Request $request): View
  {
    $perPage = (int) $request->get('per_page', 25);
    $search = trim((string) $request->get('search', ''));

    $query = DatPerolehanHak::with('refJph', 'ppat', 'wpPenerimaHak', 'sptpd')
      ->whereHas('sptpd', function ($q) {
        $q->whereNotNull('tgl_selesai_bpn');
      })
      ->where('tahun_perolehan', date('Y'));

    if ($search !== '') {
      $query->where(function ($q) use ($search) {
        $q->whereHas('wpPenerimaHak', fn($wp) => $wp->where('nama', 'LIKE', "%{$search}%"))
          ->orWhereHas('ppat', fn($ppat) => $ppat->where('nama', 'LIKE', "%{$search}%"));
      });
    }

    $datas = $query->orderBy('id', 'desc')
      ->paginate($perPage)
      ->appends(array_filter([
        'search' => $search,
        'per_page' => $perPage,
      ]));

    return view('bphtb.selesai.data', compact('datas'));
  }
}
