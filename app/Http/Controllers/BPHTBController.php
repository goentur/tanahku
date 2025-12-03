<?php

namespace App\Http\Controllers;

use App\Enums\Dat\StatusBerkas;
use App\Enums\Dat\StatusLampiran;
use App\Enums\Dat\StatusPenelitian;
use App\Enums\Dat\StatusPengendalian;
use App\Enums\Dat\StatusSelesai;
use App\Enums\Dat\StatusVerifikasi;
use App\Models\BPHTB\DataAtrBpn;
use App\Models\BPHTB\DatPerolehanHak;
use App\Models\BPHTB\PPAT;
use App\Models\BPHTB\Sptpd;
use App\Models\PBB\DatObjekPajak;
use App\Models\PBB\DatOpBangunan;
use App\Models\PBB\DatOpBumi;
use App\Models\PBB\DatSubjekPajak;
use App\Models\PBB\PendataanLspop;
use App\Models\PBB\PendataanSpop;
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
		$search = trim((string) $request->get('search', ''));

		$query = DatPerolehanHak::with('refJph', 'ppat', 'wpPenerimaHak');

		if ($status !== '') {
			$this->posisiBerkas($query, $status);
		}
		if ($search !== '') {
			$query->where(function ($q) use ($search) {
				$q->whereHas('wpPenerimaHak', fn($wp) => $wp->where('nama', 'LIKE', "%{$search}%"))
					->orWhereHas('ppat', fn($ppat) => $ppat->where('nama', 'LIKE', "%{$search}%"));
			});
		}

		$datas = $query->where('tahun_perolehan', date('Y'))->orderBy('id', 'desc')
			->paginate($perPage)
			->appends([
				'search' => $search,
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
			->join('bphtb.sptpd', 'bphtb.dat_perolehan_hak.id', '=', 'bphtb.sptpd.dat_perolehan_hak_id')
			->whereNotNull('bphtb.sptpd.tgl_selesai_bpn')
			->where('bphtb.dat_perolehan_hak.tahun_perolehan', date('Y'))
			->select('bphtb.dat_perolehan_hak.*');

		if ($search !== '') {
			$query->where(function ($q) use ($search) {
				$q->whereHas('wpPenerimaHak', fn($wp) => $wp->where('nama', 'LIKE', "%{$search}%"))
					->orWhereHas('ppat', fn($ppat) => $ppat->where('nama', 'LIKE', "%{$search}%"));
			});
		}

		$datas = $query
			->orderByRaw('CASE WHEN bphtb.sptpd.tgl_singkron IS NULL THEN 0 ELSE 1 END')
			->orderBy('bphtb.sptpd.tgl_selesai_bpn', 'ASC')
			->paginate($perPage)
			->appends(array_filter([
				'search' => $search,
				'per_page' => $perPage,
			]));

		return view('bphtb.selesai.data', compact('datas'));
	}
	public function singkronisasi(Request $request)
	{
		$sptpd = Sptpd::with('skpdkb', 'sspd')->where('dat_perolehan_hak_id', $request->id)->firstOrFail();

		DB::beginTransaction();

		try {
			$kodeNTPD = 0;
			if ($sptpd->skpdkb) {
				$kodeNTPD = $sptpd->skpdkb->tahun_skpdkb . '' . $sptpd->skpdkb->bundel_skpdkb . '' . $sptpd->skpdkb->no_urut_skpdkb;
			} else {
				$kodeNTPD = $sptpd->tahun_sptpd . '' . $sptpd->bundel_sptpd . '' . $sptpd->no_urut_sptpd;
			}
			$dataAtrBpn = DataAtrBpn::where([
				'nop' => $sptpd->kd_propinsi . '' . $sptpd->kd_dati2 . '' . $sptpd->kd_kecamatan . '' . $sptpd->kd_kelurahan . '' . $sptpd->kd_blok . '' . $sptpd->no_urut . '' . $sptpd->kd_jns_op,
				'ntpd' => $sptpd->kd_propinsi . '' . $sptpd->kd_dati2 . '' . $kodeNTPD
			])->first();
			if ($dataAtrBpn) {
				$subjekData = [
					'subjek_pajak_id' => $sptpd->nid_wp_sptpd,
					'nm_wp' => $sptpd->nama_wp_sptpd,
					'jalan_wp' => $sptpd->alamat_wp_sptpd,
					'blok_kav_no_wp' => null,
					'rw_wp' => $sptpd->rw_wp_sptpd,
					'rt_wp' => $sptpd->rt_wp_sptpd,
					'kelurahan_wp' => $sptpd->kelurahan_wp_sptpd,
					'kota_wp' => $sptpd->kota_wp_sptpd,
					'kd_pos_wp' => $sptpd->kode_pos_wp_sptpd,
					'telp_wp' => null,
					'npwp' => null,
					'status_pekerjaan_wp' => 5,
				];

				// $subjekPajak = DatSubjekPajak::updateOrCreate(
				// 	['subjek_pajak_id' => $sptpd->nid_wp_sptpd],
				// 	$subjekData
				// );
				// pendataan_lspop 

				$pendataanLspop = PendataanLspop::where([
					'kd_propinsi' => $sptpd->kd_propinsi,
					'kd_dati2' => $sptpd->kd_dati2,
					'kd_kecamatan' => $sptpd->kd_kecamatan,
					'kd_kelurahan' => $sptpd->kd_kelurahan,
					'kd_blok' => $sptpd->kd_blok,
					'no_urut' => $sptpd->no_urut,
					'kd_jns_op' => $sptpd->kd_jns_op,
				])->orderBy('no_bng')->get();
				$selisih = 0;
				if ($pendataanLspop->isNotEmpty()) {
					$totalLuasBng = $pendataanLspop->sum('luas_bng');

					if ($totalLuasBng != $sptpd->luas_bng) {
						$selisih = $sptpd->luas_bng - $totalLuasBng;

						// Ambil record pertama dari koleksi (yang no_bng paling kecil, biasanya 1)
						$targetRecord = $pendataanLspop->first();

						// Pastikan recordnya ada (sudah dicek di isNotEmpty, jadi aman)
						// $targetRecord->update([
						// 	'luas_bng' => $targetRecord->luas_bng + $selisih,
						// ]);
					}
				}

				// pendataan_spop 
				$pendataanSpop = PendataanSpop::where([
					'kd_propinsi' => $sptpd->kd_propinsi,
					'kd_dati2' => $sptpd->kd_dati2,
					'kd_kecamatan' => $sptpd->kd_kecamatan,
					'kd_kelurahan' => $sptpd->kd_kelurahan,
					'kd_blok' => $sptpd->kd_blok,
					'no_urut' => $sptpd->no_urut,
					'kd_jns_op' => $sptpd->kd_jns_op,
				])->first();
				if ($pendataanSpop) {
					// $pendataanSpop->update([
					// 	'subjek_pajak_id' => $sptpd->nid_wp_sptpd,
					// 	'nm_wp' => $sptpd->nama_wp_sptpd,
					// 	'jalan_wp' => $sptpd->alamat_wp_sptpd,
					// 	'blok_kav_no_wp' => null,
					// 	'rw_wp' => $sptpd->rw_wp_sptpd,
					// 	'rt_wp' => $sptpd->rt_wp_sptpd,
					// 	'kelurahan_wp' => $sptpd->kelurahan_wp_sptpd,
					// 	'kota_wp' => $sptpd->kota_wp_sptpd,
					// 	'kd_pos_wp' => $sptpd->kode_pos_wp_sptpd,
					// 	'telp_wp' => null,
					// 	'npwp' => null,
					// 	'status_pekerjaan_wp' => 5,
					// 	'total_luas_bumi' => $dataAtrBpn->luastanah_op,
					// 	'total_luas_bng' => $pendataanSpop->total_luas_bng + $selisih,
					// ]);
				}
				$opBangunan = DatOpBangunan::where([
					'kd_propinsi' => $sptpd->kd_propinsi,
					'kd_dati2' => $sptpd->kd_dati2,
					'kd_kecamatan' => $sptpd->kd_kecamatan,
					'kd_kelurahan' => $sptpd->kd_kelurahan,
					'kd_blok' => $sptpd->kd_blok,
					'no_urut' => $sptpd->no_urut,
					'kd_jns_op' => $sptpd->kd_jns_op,
				])->orderBy('no_bng')->get();
				$selisih = 0;
				if ($opBangunan->isNotEmpty()) {
					$totalLuasBng = $opBangunan->sum('luas_bng');

					if ($totalLuasBng != $sptpd->luas_bng) {
						$selisih = $sptpd->luas_bng - $totalLuasBng;

						// Ambil record pertama dari koleksi (yang no_bng paling kecil, biasanya 1)
						$targetRecord = $opBangunan->first();

						// Pastikan recordnya ada (sudah dicek di isNotEmpty, jadi aman)
						// $targetRecord->update([
						// 	'luas_bng' => $targetRecord->luas_bng + $selisih,
						// ]);
					}
				}

				// Update DatOpBumi
				$opBumi = DatOpBumi::where([
					'kd_propinsi' => $sptpd->kd_propinsi,
					'kd_dati2' => $sptpd->kd_dati2,
					'kd_kecamatan' => $sptpd->kd_kecamatan,
					'kd_kelurahan' => $sptpd->kd_kelurahan,
					'kd_blok' => $sptpd->kd_blok,
					'no_urut' => $sptpd->no_urut,
					'kd_jns_op' => $sptpd->kd_jns_op,
					'no_bumi' => 1,
				])->first();
				if ($opBumi) {
					// $opBumi->update([
					// 	'luas_bumi' => $dataAtrBpn->luastanah_op,
					// ]);
				}

				// Update DatObjekPajak
				$objekPajak = DatObjekPajak::where([
					'kd_propinsi' => $sptpd->kd_propinsi,
					'kd_dati2' => $sptpd->kd_dati2,
					'kd_kecamatan' => $sptpd->kd_kecamatan,
					'kd_kelurahan' => $sptpd->kd_kelurahan,
					'kd_blok' => $sptpd->kd_blok,
					'no_urut' => $sptpd->no_urut,
					'kd_jns_op' => $sptpd->kd_jns_op,
				])->first();
				if ($objekPajak) {
					// $objekPajak->update([
					// 	'subjek_pajak_id' => $sptpd->nid_wp_sptpd,
					// 	'total_luas_bumi' => $dataAtrBpn->luastanah_op,
					// 	'total_luas_bng' => $objekPajak->total_luas_bng + $selisih,
					// ]);
				}

				// DB::statement(
				// 	"BEGIN PENENTUAN_NJOP(:p_prop, :p_dati2, :p_kec, :p_kel, :p_blok, :p_urut, :p_jns, :p_thn, :p_flag); END;",
				// 	[
				// 		'p_prop'  => $sptpd->kd_propinsi,
				// 		'p_dati2' => $sptpd->kd_dati2,
				// 		'p_kec'   => $sptpd->kd_kecamatan,
				// 		'p_kel'   => $sptpd->kd_kelurahan,
				// 		'p_blok'  => $sptpd->kd_blok,
				// 		'p_urut'  => $sptpd->no_urut,
				// 		'p_jns'   => $sptpd->kd_jns_op,
				// 		'p_thn'   => $sptpd->tahun_sptpd,
				// 		'p_flag'  => 1,
				// 	]
				// );
				// $sptpd->update([
				// 	'tgl_singkron' => now(),
				// ]);
			}

			DB::commit();

			return response()->json(['message' => 'Data berhasil diperbarui.'], 200);
		} catch (\Exception $e) {
			DB::rollback();

			// Opsional: log error
			\Log::error('Gagal update data terkait: ' . $e->getMessage());

			return response()->json(['message' => 'Terjadi kesalahan saat memperbarui data.'], 500);
		}
	}
}
