<?php

namespace App\Http\Controllers;

use App\Models\BPHTB\DatPerolehanHak;
use App\Repositories\BphtbRepository;
use App\Services\Geoserver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BerandaController extends Controller
{
    public function __construct(
        protected Geoserver $service,
        protected BphtbRepository $bphtb_repository,
    ) {}

    public function index(): View
    {
        return view('beranda.index');
    }

    public function feature(Request $request, string $layer)
    {
        return $this->service->getFeature(array_merge($request->query(), [
            'typeNames' => $layer,
        ]));
    }

    public function peta(): View
    {
        return view('beranda.peta');
    }

    public function dataPeta(Request $request)
    {
        return $this->feature($request, 'bpn:Join_gamer');
    }

    public function dataBPHTB()
    {
        $allowedStatuses = ['4', '5', '6', '7', '11', '12'];
        $statusPriority = [
            '4'  => 1,
            '5'  => 2,
            '6'  => 3,
            '7'  => 4,
            '11' => 5,
            '12' => 6,
        ];

        // Gunakan map: kunci = NOP lengkap, nilai = data dengan status tertinggi
        $uniqueData = [];

        foreach ($allowedStatuses as $status) {
            if ($status == '11') {
                $query = DatPerolehanHak::select(
                    'id',
                    'kd_propinsi',
                    'kd_dati2',
                    'kd_kecamatan',
                    'kd_kelurahan',
                    'kd_blok',
                    'no_urut',
                    'kd_jns_op'
                )->whereHas('sptpd', function ($q) {
                    $q->whereNotNull('tgl_akses_bpn')
                        ->whereNull('tgl_selesai_bpn');
                })->where('tahun_perolehan', date('Y'));

                $data = $query->orderBy('id', 'desc')->get();
            } elseif ($status == '12') {
                $data = DatPerolehanHak::select(
                    'dat_perolehan_hak.id',
                    'dat_perolehan_hak.kd_propinsi',
                    'dat_perolehan_hak.kd_dati2',
                    'dat_perolehan_hak.kd_kecamatan',
                    'dat_perolehan_hak.kd_kelurahan',
                    'dat_perolehan_hak.kd_blok',
                    'dat_perolehan_hak.no_urut',
                    'dat_perolehan_hak.kd_jns_op'
                )->join('bphtb.sptpd', 'bphtb.dat_perolehan_hak.id', '=', 'bphtb.sptpd.dat_perolehan_hak_id')
                    ->whereNotNull('bphtb.sptpd.tgl_selesai_bpn')
                    ->where('tahun_perolehan', date('Y'))
                    ->orderByRaw('CASE WHEN bphtb.sptpd.tgl_singkron IS NULL THEN 0 ELSE 1 END')
                    ->orderBy('bphtb.sptpd.tgl_selesai_bpn', 'ASC')
                    ->get();
            } else {
                $query = DatPerolehanHak::with('sptpd', 'skpdkb')
                    ->select(
                        'id',
                        'kd_propinsi',
                        'kd_dati2',
                        'kd_kecamatan',
                        'kd_kelurahan',
                        'kd_blok',
                        'no_urut',
                        'kd_jns_op'
                    );

                $this->bphtb_repository->posisiBerkas($query, $status);

                $data = $query->where('tahun_perolehan', date('Y'))
                    ->orderBy('id', 'desc')
                    ->get();
            }

            foreach ($data as $item) {
                // Format NOP lengkap (pastikan ini unik dan konsisten)
                $nop = $item->kd_propinsi .
                    $item->kd_dati2 .
                    $item->kd_kecamatan .
                    $item->kd_kelurahan .
                    $item->kd_blok .
                    $item->no_urut .
                    $item->kd_jns_op;

                // Siapkan data
                $item->noptanpaFormat = $nop;
                $item->status = $status;
                $item->makeHidden(['sptpd', 'skpdkb']);

                // Jika NOP belum ada, atau status baru LEBIH TINGGI, ganti
                if (!isset($uniqueData[$nop]) || $statusPriority[$status] > $statusPriority[$uniqueData[$nop]->status]) {
                    $uniqueData[$nop] = $item;
                }
            }
        }

        // Ambil nilai akhir sebagai koleksi
        $result = collect(array_values($uniqueData));

        return response()->json($result);
    }
}
