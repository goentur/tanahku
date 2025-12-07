<?php

namespace App\Repositories;

use App\Enums\Dat\StatusBerkas;
use App\Enums\Dat\StatusLampiran;
use App\Enums\Dat\StatusPenelitian;
use App\Enums\Dat\StatusPengendalian;
use App\Enums\Dat\StatusSelesai;
use App\Enums\Dat\StatusVerifikasi;
use App\Models\BPHTB\DatPerolehanHak;

class BphtbRepository
{
  public function multiPosisiBerkas($query, array $values)
  {
    $query->where(function ($q) use ($values) {
      foreach ($values as $value) {
        $q->orWhere(function ($sub) use ($value) {
          $this->posisiBerkas($sub, $value);
        });
      }
    });
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

  public function scopeForBpnStatus($query, string $status)
  {
    $query->with('refJph', 'ppat', 'wpPenerimaHak', 'sptpd')
      ->where('tahun_perolehan', date('Y'));

    if ($status === 'bpn') {
      $query->whereHas('sptpd', function ($q) {
        $q->whereNotNull('tgl_akses_bpn')
          ->whereNull('tgl_selesai_bpn');
      });
    } elseif ($status === 'selesai') {
      $query->join('bphtb.sptpd', 'bphtb.dat_perolehan_hak.id', '=', 'bphtb.sptpd.dat_perolehan_hak_id')
        ->select('bphtb.dat_perolehan_hak.*');
    }

    return $query;
  }
  public function matchesStatus(DatPerolehanHak $model, string $status): bool
  {
    switch ($status) {
      case '4':
        return $model->sptpd && !$model->sptpd->sspd()->exists();

      case '5':
        return $model->sptpd && $model->sptpd->sspd()->exists()
          && $model->status_pengendalian === StatusPengendalian::Belum;

      case '6':
        return $model->status_pengendalian === StatusPengendalian::Pemeriksaan
          && !$model->datPemeriksaan()->exists();

      case '7':
        return $model->status_pengendalian === StatusPengendalian::Pemeriksaan
          && $model->skpdkb && !$model->skpdkb->sspd()->exists();

      default:
        return false;
    }
  }
}
