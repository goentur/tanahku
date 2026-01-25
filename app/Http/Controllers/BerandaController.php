<?php

namespace App\Http\Controllers;

use App\Models\PBB\DatObjekPajak;
use App\Models\PBB\Sppt;
use App\Services\Geoserver;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class BerandaController extends Controller
{
    public function __construct(
        protected Geoserver $service,
    ) {}

    public function index(): View
    {
        return view('beranda.index');
    }

    public function peta(): View
    {
        return view('beranda.peta');
    }

    public function feature(Request $request, string $layer)
    {
        return $this->service->getFeature(array_merge($request->query(), [
            'typeNames' => $layer,
        ]));
    }

    public function cari(Request $request): JsonResponse
    {
        $nop = $request->nop;
        $nib = $request->nib;

        $data = null;

        if ($nop) {
            $nop1 = substr($nop, 0, 2);
            $nop2 = substr($nop, 3, 2);
            $nop3 = substr($nop, 6, 3);
            $nop4 = substr($nop, 10, 3);
            $nop5 = substr($nop, 14, 3);
            $nop6 = substr($nop, 18, 4);
            $nop7 = substr($nop, 23, 1);

            $objekPajak = DatObjekPajak::with('datSubjekPajak')
                ->where('kd_kecamatan', $nop3)
                ->where('kd_kelurahan', $nop4)
                ->where('kd_blok', $nop5)
                ->where('no_urut', $nop6)
                ->where('kd_jns_op', $nop7)
                ->first();
            if ($objekPajak) {
                if ($nop3 == '020') {
                    if ($nop4 == '013') {
                        $dataPeta = $this->feature($request, 'bpn:Join_gamer');
                    }
                    if ($nop4 == '002' || $nop4 == '003') {
                        $dataPeta = $this->feature($request, 'bpn:Join_Kalibaros');
                    }
                    if ($nop4 == '011') {
                        $dataPeta = $this->feature($request, 'bpn:Join_Klego');
                    }
                    if ($nop4 == '001' || $nop4 == '005') {
                        $dataPeta = $this->feature($request, 'bpn:Join_Noyontaansari');
                    }
                    if ($nop4 == '010') {
                        $dataPeta = $this->feature($request, 'bpn:Join_Poncol');
                    }
                    if ($nop4 == '006' || $nop4 == '007' || $nop4 == '008' || $nop4 == '009') {
                        $dataPeta = $this->feature($request, 'bpn:Join_Kauman');
                    }
                }
                if (!empty($dataPeta['features']) && is_array($dataPeta['features'])) {
                    $hasil = str_replace(['.', '-'], '', $request->nop);
                    $nibBaru = explode('.', $request->nib);
                    $dataTerpilih = null;
                    foreach ($dataPeta['features'] as $feature) {
                        if (
                            ($hasil && isset($feature['properties']['d_nop']) && $feature['properties']['d_nop'] == $hasil)
                            &&
                            ($nibBaru && isset($feature['properties']['NIB']) && $feature['properties']['NIB'] == $nibBaru[1])
                        ) {
                            $dataTerpilih = $feature;
                            break;
                        }
                    }
                    $folder = $nop1 . $nop2 . '/' . $nop3 . $nop4 . '/' . $nop5;

                    $files = Storage::disk('foto_nfs')->files($folder);

                    $largestNumber = -1;
                    $largestFile = null;
                    $allowedExtensions = ['jpg', 'jpeg', 'png'];

                    foreach ($files as $file) {
                        $basename = basename($file);
                        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                        // Filter berdasarkan NOP dan ekstensi
                        if (strpos($basename, $nop) === 0 && in_array($extension, $allowedExtensions)) {

                            // Ekstrak angka di akhir nama file (sebelum .jpg)
                            // Contoh: 337501000700300090002.jpg → ambil "2"
                            $filenameWithoutExt = pathinfo($basename, PATHINFO_FILENAME); // → "337501000700300090002"

                            // Ambil 1-2 digit terakhir (asumsi nomor urut hanya 1 atau 2 digit)
                            $suffix = substr($filenameWithoutExt, -2); // ambil 2 digit terakhir

                            // Jika hanya 1 digit, ambil 1 digit terakhir
                            if (!ctype_digit($suffix)) {
                                $suffix = substr($filenameWithoutExt, -1);
                            }

                            // Pastikan suffix adalah angka
                            if (ctype_digit($suffix)) {
                                $number = (int)$suffix;

                                if ($number > $largestNumber) {
                                    $largestNumber = $number;
                                    $largestFile = $file;
                                }
                            }
                        }
                    }

                    $urls = [];
                    if ($largestFile) {
                        $urls[] = '/storage/foto/' . $folder . '/' . basename($largestFile);
                    }
                    $dataKirim = $dataTerpilih['properties'];
                    $sppt = Sppt::with('pembayaranSppt')
                        ->where('kd_propinsi', $nop1)
                        ->where('kd_dati2', $nop2)
                        ->where('kd_kecamatan', $nop3)
                        ->where('kd_kelurahan', $nop4)
                        ->where('kd_blok', $nop5)
                        ->where('no_urut', $nop6)
                        ->where('kd_jns_op', $nop7)
                        ->where('thn_pajak_sppt', '>=', 2008)
                        ->get();
                    $html = view('beranda.peta-detail', compact(['objekPajak', 'sppt', 'urls', 'dataKirim', 'hasil']))->render();
                    return response()->json([
                        'success' => true,
                        'html' => $html,
                        'geometry' => $dataTerpilih['geometry'],
                    ]);
                }
            }
        }

        return response()->json(['success' => false, 'html' => '']);
    }
    public function sppt(Request $request)
    {
        dd($request->token);
    }
}
