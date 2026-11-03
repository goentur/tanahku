<?php

namespace App\Http\Controllers;

use App\Models\PBB\DatObjekPajak;
use App\Services\Geoserver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PendataanController extends Controller
{
    public function __construct(
        protected Geoserver $service,
    ) {}
    public function index(): View
    {
        return view('pendataan.peta-integrasi');
    }

    public function feature(Request $request, string $layer)
    {
        return $this->service->getFeature(array_merge($request->query(), [
            'typeNames' => $layer,
        ]));
    }

    public function dataPeta(Request $request)
    {
        return $this->feature($request, $request->id);
    }
    public function detailPersil(Request $request)
    {
        $tipeLayer = $request->input('tipe_layer');

        // 1. PENANGANAN LAYER BPN
        if ($tipeLayer === 'bpn') {
            $dataKirim = [
                'nib'             => $request->input('nib'),
                'nib_el'          => $request->input('nib_el'),
                'luas'            => $request->input('luas'),
                'tipe_hak'        => $request->input('tipe_hak'),
                'nomor_hak'       => $request->input('nomor_hak'),
                'pemilik_pertama' => $request->input('pemilik_pertama'),
                'pemilik_akhir'   => $request->input('pemilik_akhir'),
                'nop'             => $request->input('nop'),
            ];

            // Cari Objek Pajak & Foto jika terdapat relasi NOP pada data BPN
            $result = $this->getObjekPajakAndFoto($request->input('nop'));

            return view('pendataan.informasi-data-bpn', [
                'dataKirim'  => $dataKirim,
                'objekPajak' => $result['objekPajak'],
                'urls'       => $result['urls']
            ]);
        }

        // 2. PENANGANAN LAYER PBB
        if ($tipeLayer === 'pbb') {
            $nopRaw = $request->input('nop');

            if (!$nopRaw) {
                return response()->html('<div class="alert alert-warning mb-0">Informasi NOP tidak ditemukan</div>');
            }

            $result = $this->getObjekPajakAndFoto($nopRaw);

            if (!$result['objekPajak']) {
                return response()->html('<div class="alert alert-warning mb-0">Informasi Objek Pajak tidak ditemukan</div>');
            }

            return view('pendataan.informasi-data-pbb', [
                'objekPajak' => $result['objekPajak'],
                'urls'       => $result['urls']
            ]);
        }

        return response()->html('<div class="alert alert-danger mb-0">Tipe layer tidak valid</div>');
    }

    private function getObjekPajakAndFoto($nopRaw)
    {
        $objekPajak = null;
        $urls = [];

        if (!$nopRaw) {
            return ['objekPajak' => null, 'urls' => []];
        }

        // Bersihkan NOP dari karakter non-digit (titik, strip, spasi)
        $nop = preg_replace('/[^0-9]/', '', $nopRaw);

        // Validasi panjang NOP harus 18 digit
        if (strlen($nop) === 18) {
            $nop1 = substr($nop, 0, 2);   // Kd Propinsi
            $nop2 = substr($nop, 2, 2);   // Kd Dati2
            $nop3 = substr($nop, 4, 3);   // Kd Kecamatan
            $nop4 = substr($nop, 7, 3);   // Kd Kelurahan
            $nop5 = substr($nop, 10, 3);  // Kd Blok
            $nop6 = substr($nop, 13, 4);  // No Urut
            $nop7 = substr($nop, 17, 1);  // Kd Jns OP

            $objekPajak = DatObjekPajak::with('datSubjekPajak', 'refKelurahan')
                ->where('kd_propinsi', $nop1)
                ->where('kd_dati2', $nop2)
                ->where('kd_kecamatan', $nop3)
                ->where('kd_kelurahan', $nop4)
                ->where('kd_blok', $nop5)
                ->where('no_urut', $nop6)
                ->where('kd_jns_op', $nop7)
                ->first();

            if ($objekPajak) {
                $folder = $nop1 . $nop2 . '/' . $nop3 . $nop4 . '/' . $nop5;

                // Pengecekan direktori storage foto
                if (Storage::disk('foto_nfs')->exists($folder)) {
                    $files = Storage::disk('foto_nfs')->files($folder);

                    $largestNumber = -1;
                    $largestFile = null;
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    foreach ($files as $file) {
                        $basename = basename($file);
                        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                        if (strpos($basename, $nop) === 0 && in_array($extension, $allowedExtensions)) {
                            $filenameWithoutExt = pathinfo($basename, PATHINFO_FILENAME);
                            $suffix = substr($filenameWithoutExt, -2);
                            if (!ctype_digit($suffix)) {
                                $suffix = substr($filenameWithoutExt, -1);
                            }
                            if (ctype_digit($suffix)) {
                                $number = (int)$suffix;
                                if ($number > $largestNumber) {
                                    $largestNumber = $number;
                                    $largestFile = $file;
                                }
                            }
                        }
                    }

                    if ($largestFile) {
                        $urls[] = '/storage/foto/' . $folder . '/' . basename($largestFile);
                    }
                }
            }
        }

        return [
            'objekPajak' => $objekPajak,
            'urls'       => $urls
        ];
    }
}
