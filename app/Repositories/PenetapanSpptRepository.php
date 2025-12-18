<?php

namespace App\Repositories;

use App\Models\PBB\DatObjekPajak;
use App\Models\PBB\DatOpBumi;
use App\Models\PBB\Njoptkp;
use App\Models\PBB\PbbMinimal;
use App\Models\PBB\RefBuku;
use App\Models\PBB\Sppt;
use App\Models\PBB\TempatPembayaranSpptMasal;
use App\Procedures\PenetapanMassal;
use App\Support\AnonymousAttributes;
use App\Support\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class PenetapanSpptRepository
{
    public function satuan(array $data)
    {
        $tahun = date('Y');
        $kd_propinsi = $request->get('kd_propinsi');
        $kd_dati2 = $request->get('kd_dati2');
        $kd_kecamatan = $request->get('kd_kecamatan');
        $kd_kelurahan = $request->get('kd_kelurahan');
        $kd_blok_min = $request->get('kd_blok_min');
        $no_urut_min = $request->get('no_urut_min');
        $kd_jns_op_min = $request->get('kd_jns_op_min');
        $kd_blok_max = $request->get('kd_blok_max');
        $no_urut_max = $request->get('no_urut_max');
        $kd_jns_op_max = $request->get('kd_jns_op_max');
        $tgl_jatuh_tempo_sppt = Carbon::createFromFormat('DD/MM/YYYY', '30/09/' . date('Y'));
        $tgl_terbit_sppt = Carbon::createFromFormat('DD/MM/YYYY', date('dd/mm/yyyy'));
        $stimulus = 0;

        $njoptkp = Njoptkp::query()
            ->where('kd_propinsi', $kd_propinsi)
            ->where('kd_dati2', $kd_dati2)
            ->where('thn_awal', '<=', $tahun)
            ->where('thn_akhir', '>=', $tahun)
            ->get()
            ->first();

        if (!$njoptkp) throw ValidationException::withMessages([
            'error' => 'Data NJOPTKP tidak ditemukan!',
        ]);

        $njoptkp_value = $njoptkp->nilai_njoptkp * 1000;

        $buku1 = RefBuku::query()->firstWhere('kd_buku', 1);
        $buku2 = RefBuku::query()->firstWhere('kd_buku', 2);
        $buku3 = RefBuku::query()->firstWhere('kd_buku', 3);
        $buku4 = RefBuku::query()->firstWhere('kd_buku', 4);
        $buku5 = RefBuku::query()->firstWhere('kd_buku', 5);

        if (!$buku1) throw ValidationException::withMessages(['error' => 'Data Buku 1 tidak ditemukan!']);
        if (!$buku2) throw ValidationException::withMessages(['error' => 'Data Buku 2 tidak ditemukan!']);
        if (!$buku3) throw ValidationException::withMessages(['error' => 'Data Buku 3 tidak ditemukan!']);
        if (!$buku4) throw ValidationException::withMessages(['error' => 'Data Buku 4 tidak ditemukan!']);
        if (!$buku5) throw ValidationException::withMessages(['error' => 'Data Buku 5 tidak ditemukan!']);

        $tpMassal = TempatPembayaranSpptMasal::query()
            ->where('kd_propinsi', $kd_propinsi)
            ->where('kd_dati2', $kd_dati2)
            ->where('kd_kecamatan', $kd_kecamatan)
            ->where('kd_kelurahan', $kd_kelurahan)
            ->where('thn_tp_sppt_masal', $tahun)
            ->get()
            ->first();

        if (!$tpMassal) throw ValidationException::withMessages(['error' => 'Data Bank Tempat Pembayaran tidak ditemukan!']);

        $pbbMin = PbbMinimal::query()
            ->where('kd_propinsi', $kd_propinsi)
            ->where('kd_dati2', $kd_dati2)
            ->where('thn_pbb_minimal', $tahun)
            ->get()
            ->first();

        $args = [
            'vls_kd_kanwil' => $tpMassal->kd_kanwil,
            'vls_kd_kppbb' => $tpMassal->kd_kppbb,
            'vls_kd_tunggal' => $tpMassal->kd_bank_tunggal,
            'vls_kd_persepsi' => $tpMassal->kd_bank_persepsi,
            'vls_kd_tp' => $tpMassal->kd_tp,
            'vli_njoptkp' => $njoptkp_value,
            'vli_min_b1' => $buku1->nilai_min_buku,
            'vli_max_b1' => $buku1->nilai_max_buku,
            'vli_min_b2' => $buku2->nilai_min_buku,
            'vli_max_b2' => $buku2->nilai_max_buku,
            'vli_min_b3' => $buku3->nilai_min_buku,
            'vli_max_b3' => $buku3->nilai_max_buku,
            'vli_min_b4' => $buku4->nilai_min_buku,
            'vli_max_b4' => $buku4->nilai_max_buku,
            'vli_min_b5' => $buku5->nilai_min_buku,
            'vli_max_b5' => $buku5->nilai_max_buku,
            'v_thn' => $tahun,
            'tgl_jtt' => $tgl_jatuh_tempo_sppt,
            'tgl_terbit' => $tgl_terbit_sppt,
            'vgs_nip_user' => '830830001',
            'pbb_min' => $pbbMin->nilai_pbb_minimal ?? 0,
        ];

        $ops = DatObjekPajak::query()
            ->where('kd_propinsi', $kd_propinsi)
            ->where('kd_dati2', $kd_dati2)
            ->where('kd_kecamatan', $kd_kecamatan)
            ->where('kd_kelurahan', $kd_kelurahan)
            ->where(function (Builder $query) use ($kd_blok_min, $no_urut_min, $kd_jns_op_min) {
                $query->where('kd_blok', '>=', $kd_blok_min)
                    ->where('no_urut', '>=', $no_urut_min)
                    ->where('kd_jns_op', '>=', $kd_jns_op_min);
            })
            ->where(function (Builder $query) use ($kd_blok_max, $no_urut_max, $kd_jns_op_max) {
                $query->where('kd_blok', '<=', $kd_blok_max)
                    ->where('no_urut', '<=', $no_urut_max)
                    ->where('kd_jns_op', '<=', $kd_jns_op_max);
            })
            ->orderBy('kd_propinsi')
            ->orderBy('kd_dati2')
            ->orderBy('kd_kecamatan')
            ->orderBy('kd_kelurahan')
            ->orderBy('kd_blok')
            ->orderBy('no_urut')
            ->orderBy('kd_jns_op')
            ->get();

        foreach ($ops as $o) {
            /** @var DatObjekPajak $o */
            if (in_array($o->kd_jns_op, ['8', '9'], false)) {
                $this->hapusPenetapanSpptNopFasum(
                    $o->kd_propinsi,
                    $o->kd_dati2,
                    $o->kd_kecamatan,
                    $o->kd_kelurahan,
                    $o->kd_blok,
                    $o->no_urut,
                    $o->kd_jns_op,
                    $tahun
                );
            } else {
                $this->penetapanNop(new AnonymousAttributes(array_merge($o->toArray(), $args)));
            }
        }
    }

    public function penetapanNop($data)
    {
        $opbumi = DatOpBumi::firstWhere([
            'kd_propinsi' => $data->get('kd_propinsi'),
            'kd_dati2' => $data->get('kd_dati2'),
            'kd_kecamatan' => $data->get('kd_kecamatan'),
            'kd_kelurahan' => $data->get('kd_kelurahan'),
            'kd_blok' => $data->get('kd_blok'),
            'no_urut' => $data->get('no_urut'),
            'kd_jns_op' => $data->get('kd_jns_op'),
        ]);

        if (! $opbumi) {
            return;
        } elseif ($opbumi->jns_bumi > 3) {
            $this->hapusPenetapanSpptNopFasum(
                $data->get('kd_propinsi'),
                $data->get('kd_dati2'),
                $data->get('kd_kecamatan'),
                $data->get('kd_kelurahan'),
                $data->get('kd_blok'),
                $data->get('no_urut'),
                $data->get('kd_jns_op'),
                $data->get('v_thn'),
            );
            return;
        } else
            PenetapanMassal::call(
                $data->get('kd_propinsi'),
                $data->get('kd_dati2'),
                $data->get('kd_kecamatan'),
                $data->get('kd_kelurahan'),
                $data->get('kd_blok'),
                $data->get('no_urut'),
                $data->get('kd_jns_op'),
                $data->get('no_persil'),
                $data->get('njop_bumi'),
                $data->get('njop_bng'),
                $data->get('total_luas_bumi'),
                $data->get('total_luas_bng'),
                $data->get('subjek_pajak_id'),
                $data->get('vls_kd_kanwil'),
                $data->get('vls_kd_kppbb'),
                $data->get('vls_kd_tunggal'),
                $data->get('vls_kd_persepsi'),
                $data->get('vls_kd_tp'),
                $data->get('vli_njoptkp'),
                $data->get('vli_min_b1'),
                $data->get('vli_max_b1'),
                $data->get('vli_min_b2'),
                $data->get('vli_max_b2'),
                $data->get('vli_min_b3'),
                $data->get('vli_max_b3'),
                $data->get('vli_min_b4'),
                $data->get('vli_max_b4'),
                $data->get('vli_min_b5'),
                $data->get('vli_max_b5'),
                $opbumi->jns_bumi,
                $data->get('v_thn'),
                $data->get('tgl_jtt') instanceof Carbon ? $data->get('tgl_jtt') : Carbon::parse($data->get('tgl_jtt')),
                $data->get('tgl_terbit') instanceof Carbon ? $data->get('tgl_terbit') : Carbon::parse($data->get('tgl_terbit')),
                $data->get('vgs_nip_user'),
                now(),
                $data->get('pbb_min', 0)
            );
    }

    public function hapusPenetapanSpptNopFasum($kd_propinsi, $kd_dati2, $kd_kecamatan, $kd_kelurahan, $kd_blok, $no_urut, $kd_jns_op, $tahun)
    {
        $sppt = Sppt::firstWhere([
            'kd_propinsi' => $kd_propinsi,
            'kd_dati2' => $kd_dati2,
            'kd_kecamatan' => $kd_kecamatan,
            'kd_kelurahan' => $kd_kelurahan,
            'kd_blok' => $kd_blok,
            'no_urut' => $no_urut,
            'kd_jns_op' => $kd_jns_op,
            'thn_pajak_sppt' => $tahun
        ]);

        if ($sppt) $sppt->delete();
    }
}
