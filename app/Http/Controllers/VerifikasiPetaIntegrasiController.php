<?php

namespace App\Http\Controllers;

use App\Models\DatAtrbpn;
use App\Models\Kelurahan;
use App\Models\PBB\DatObjekPajak;
use App\Services\Geoserver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class VerifikasiPetaIntegrasiController extends Controller
{
    public function __construct(
        protected Geoserver $service,
    ) {}
    public function index(): View
    {
        $barat = Kelurahan::where('kd_kecamatan', '010')->whereNull('header_id')->orderBy('id')->get();
        $timur = Kelurahan::where('kd_kecamatan', '020')->whereNull('header_id')->orderBy('id')->get();
        $selatan = Kelurahan::where('kd_kecamatan', '030')->whereNull('header_id')->orderBy('id')->get();
        $utara = Kelurahan::where('kd_kecamatan', '040')->whereNull('header_id')->orderBy('id')->get();
        return view('verifikasi.peta-integrasi', compact('barat', 'timur', 'selatan', 'utara'));
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

    public function dataSudahVerifikasi(Request $request)
    {
        $request->validate([
            'wilayah' => 'required|string',
        ]);
        $wilayah = explode(':', $request->wilayah);
        $datAtrBpn = DatAtrbpn::where('kode_wilayah', $wilayah[1])->whereNotNull('nib')->get();
        return response()->json($datAtrBpn);
    }
    public function informasiPertanahan(Request $request): View
    {
        $request->validate([
            'datakirim' => 'required|array',
            'nop' => 'nullable|string',
        ]);
        $objekPajak = null;
        $dataKirim = $request->datakirim;
        $urls = [];
        if ($request->nop) {
            $nop = $request->nop;
            $nop1 = substr($nop, 0, 2);
            $nop2 = substr($nop, 2, 2);
            $nop3 = substr($nop, 4, 3);
            $nop4 = substr($nop, 7, 3);
            $nop5 = substr($nop, 10, 3);
            $nop6 = substr($nop, 13, 4);
            $nop7 = substr($nop, 17, 1);

            $objekPajak = DatObjekPajak::with('datSubjekPajak')
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
        return view('verifikasi.informasi-data', compact('objekPajak', 'dataKirim', 'urls'));
    }
    public function simpanInformasiPertanahan(Request $request)
    {
        $request->validate([
            'modal_kode_wilayah'  => 'required|numeric|min_digits:8',
            'modal_nib'           => 'required|numeric|min_digits:5',
            'modal_nop'           => 'required|numeric|digits:18',
            'modal_pemilik_awal'  => 'nullable|string',
            'modal_pemilik_akhir' => 'nullable|string',
            'modal_luas'          => 'required|numeric',
            'modal_tipe_hak'      => 'required|string',
            'modal_no_hak'        => 'nullable|string',
        ]);

        try {
            $cekDatAtrBpn = DatAtrbpn::where([
                'kode_wilayah' => $request->modal_kode_wilayah,
                'nib'          => $request->modal_nib,
                'nop'          => $request->modal_nop,
            ])->first();

            if ($cekDatAtrBpn) {
                $cekDatAtrBpn->update([
                    'luas'               => $request->modal_luas,
                    'jenis_hak'          => $request->modal_tipe_hak,
                    'nomor_hak'          => $request->modal_no_hak,
                ]);
                $message = 'Informasi pertanahan berhasil diperbarui.';
            } else {
                $nop = $request->modal_nop;
                $nop1 = substr($nop, 0, 2);
                $nop2 = substr($nop, 2, 2);
                $nop3 = substr($nop, 4, 3);
                $nop4 = substr($nop, 7, 3);
                $nop5 = substr($nop, 10, 3);
                $nop6 = substr($nop, 13, 4);
                $nop7 = substr($nop, 17, 1);

                DatAtrbpn::create([
                    'kode_wilayah'       => $request->modal_kode_wilayah,
                    'nib'                => $request->modal_nib,
                    'nop'                => $request->modal_nop,
                    'kd_propinsi'        => $nop1,
                    'kd_dati2'           => $nop2,
                    'kd_kecamatan'       => $nop3,
                    'kd_kelurahan'       => $nop4,
                    'kd_blok'            => $nop5,
                    'no_urut'            => $nop6,
                    'kd_jns_op'          => $nop7,
                    'sumber_data'        => 'PETA BIDANG TANAH BPN',
                    'status'             => 'DATA AWAL',
                    'nama_pemilik_awal'  => $request->modal_pemilik_awal,
                    'nama_pemilik_akhir' => $request->modal_pemilik_akhir,
                    'luas'               => $request->modal_luas,
                    'jenis_hak'          => $request->modal_tipe_hak,
                    'nomor_hak'          => $request->modal_no_hak,
                ]);
                $message = 'Informasi pertanahan baru berhasil disimpan.';
            }
            return response()->json([
                'status'  => 'success',
                'message' => $message
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    public function verifikasiInformasiPertanahan(Request $request)
    {
        // Validasi data kiriman frontend
        $request->validate([
            'kode_wilayah'  => 'required|numeric|min_digits:8',
            'nib'           => 'required|numeric|min_digits:5',
            'nop'           => 'required|numeric|digits:18',
        ]);

        try {
            // Cari data berdasarkan kode_wilayah dan NIB
            $updatedRows = DatAtrbpn::where([
                'kode_wilayah' => $request->kode_wilayah,
                'nib'          => $request->nib,
                'nop'          => $request->nop,
            ])->update([
                'status' => 'TERVERIFIKASI',
            ]);

            // Jika baris yang terupdate adalah 0, berarti data tidak ditemukan
            if ($updatedRows === 0) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Informasi pertanahan berhasil diverifikasi.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
