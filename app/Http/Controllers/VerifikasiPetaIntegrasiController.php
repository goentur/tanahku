<?php

namespace App\Http\Controllers;

use App\Models\BPHTB\Sptpd;
use App\Models\DatAtrbpn;
use App\Models\Kelurahan;
use App\Models\PBB\DatObjekPajak;
use App\Models\PBB\PembayaranSppt;
use App\Models\PBB\Sppt;
use App\Services\Geoserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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
        $kodeWilayah = $wilayah[1] ?? null;
        $kelurahan = Kelurahan::where('kd_wilayah', $kodeWilayah)->first();
        if ($kelurahan && is_null($kelurahan->kd_kelurahan)) {
            $subKelurahan = Kelurahan::where('header_id', $kelurahan->id)->pluck('kd_wilayah');
            $datAtrBpn = DatAtrbpn::select('kode_wilayah', 'nib', 'nop', 'sumber_data', 'status')->whereIn('kode_wilayah', $subKelurahan)->whereNotNull('nib')->get();
        } else {
            $datAtrBpn = DatAtrbpn::select('kode_wilayah', 'nib', 'nop', 'sumber_data', 'status')->where('kode_wilayah', $kodeWilayah)->whereNotNull('nib')->get();
        }
        return response()->json($datAtrBpn);
    }
    public function informasiPertanahan(Request $request): View
    {
        $request->validate([
            'datakirim' => 'required|array',
        ]);
        $objekPajak = null;
        $sudahVerifikasi = false;
        $dataKirim = $request->datakirim;
        $urls = [];
        $bphtb = [];
        $datAtrBpn = [];
        $sppt = [
            'total' => 0,
            'tunggakan' => 0,
            'bayar' => 0,
        ];
        $datAtrBpn = DatAtrbpn::where('kode_wilayah', $dataKirim['KODEWILAYA'])
            ->where('nib', $dataKirim['NIB'])->first();
        if ($datAtrBpn && $datAtrBpn->nop) {
            $nop = $datAtrBpn->nop;
            $nop1 = substr($nop, 0, 2);
            $nop2 = substr($nop, 2, 2);
            $nop3 = substr($nop, 4, 3);
            $nop4 = substr($nop, 7, 3);
            $nop5 = substr($nop, 10, 3);
            $nop6 = substr($nop, 13, 4);
            $nop7 = substr($nop, 17, 1);

            $objekPajak = DatObjekPajak::with('datSubjekPajak', 'refKelurahan')
                ->where('kd_propinsi', $nop1)
                ->where('kd_dati2', $nop2)
                ->where('kd_kecamatan', $nop3)
                ->where('kd_kelurahan', $nop4)
                ->where('kd_blok', $nop5)
                ->where('no_urut', $nop6)
                ->where('kd_jns_op', $nop7)
                ->first();

            $dataSppt = Sppt::with('pembayaranSppt')
                ->where([
                    'kd_propinsi'  => $nop1,
                    'kd_dati2'     => $nop2,
                    'kd_kecamatan' => $nop3,
                    'kd_kelurahan' => $nop4,
                    'kd_blok'      => $nop5,
                    'no_urut'      => $nop6,
                    'kd_jns_op'    => $nop7,
                ])
                ->whereIn('status_pembayaran_sppt', [0, 1])
                ->where('thn_pajak_sppt', '>=', 2008)
                ->get();

            $totalBayar = $dataSppt->flatMap->pembayaranSppt->sum('jml_sppt_yg_dibayar') - $dataSppt->flatMap->pembayaranSppt->sum('denda_sppt');

            $sppt = [
                'total'     => $dataSppt->count(),
                'tunggakan' => $dataSppt->sum('pbb_yg_harus_dibayar_sppt'),
                'bayar'     => $totalBayar,
            ];
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
            if ($dataKirim['bphtb'] === 'YA') {
                $bphtb = Sptpd::select('*')->with('datPerolehanHak')
                    ->where('kd_propinsi', $nop1)
                    ->where('kd_dati2', $nop2)
                    ->where('kd_kecamatan', $nop3)
                    ->where('kd_kelurahan', $nop4)
                    ->where('kd_blok', $nop5)
                    ->where('no_urut', $nop6)
                    ->where('kd_jns_op', $nop7)
                    ->where('tahun_sptpd', '>=', 2020)
                    ->where('status_pembayaran_sptpd', '=', 1)
                    ->orderBy('id')->get();
            }
        }

        return view('verifikasi.informasi-data', compact('objekPajak', 'dataKirim', 'urls', 'sudahVerifikasi', 'bphtb', 'sppt', 'datAtrBpn'));
    }
    public function simpanInformasiPertanahan(Request $request)
    {
        $request->validate([
            'modal_id_peta_bidang' => 'required',
            'modal_kode_wilayah' => 'required|numeric|min_digits:8',
            'modal_nib' => 'required|numeric|min_digits:5',
            'modal_nop' => 'required|string',
            'modal_pemilik_awal' => 'nullable|string',
            'modal_pemilik_akhir' => 'nullable|string',
            'modal_luas' => 'required|numeric',
            'modal_tipe_hak' => 'required|string',
            'modal_no_hak' => 'nullable|string',
        ]);
        DB::beginTransaction();
        try {
            $nop = str_replace('.', '', str_replace('-', '', $request->modal_nop));
            $nop1 = substr($nop, 0, 2);
            $nop2 = substr($nop, 2, 2);
            $nop3 = substr($nop, 4, 3);
            $nop4 = substr($nop, 7, 3);
            $nop5 = substr($nop, 10, 3);
            $nop6 = substr($nop, 13, 4);
            $nop7 = substr($nop, 17, 1);
            $cekDatAtrBpn = DatAtrbpn::where([
                'kode_wilayah' => $request->modal_kode_wilayah,
                'nib'          => $request->modal_nib,
            ])->first();

            if ($cekDatAtrBpn) {
                DatAtrbpn::where([
                    'kode_wilayah' => $request->modal_kode_wilayah,
                    'nib'          => $request->modal_nib,
                ])->update([
                    'nop'          => $nop,
                    'kd_propinsi'  => $nop1,
                    'kd_dati2'     => $nop2,
                    'kd_kecamatan' => $nop3,
                    'kd_kelurahan' => $nop4,
                    'kd_blok'      => $nop5,
                    'no_urut'      => $nop6,
                    'kd_jns_op'    => $nop7,
                ]);
                $message = 'Informasi pertanahan berhasil diperbarui.';
            } else {
                DatAtrbpn::create([
                    'kode_wilayah'       => $request->modal_kode_wilayah,
                    'nib'                => $request->modal_nib,
                    'nop'                => $nop,
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
                $message = 'Informasi pertanahan berhasil disimpan.';
            }

            $kelurahan = Kelurahan::where('kd_wilayah', $request->modal_kode_wilayah)->first();
            if ($kelurahan && !empty($kelurahan->header_id)) {
                $kelurahanHeader = Kelurahan::find($kelurahan->header_id);
                $layer = 'bpn:' . $kelurahanHeader->kd_wilayah;
            } else {
                $layer = 'bpn:' . $request->modal_kode_wilayah;
            }
            $modal_id_peta_bidang = explode('.', $request->modal_id_peta_bidang);
            $idGeoServer = $modal_id_peta_bidang[1];

            $xmlUtama = '<?xml version="1.0" encoding="UTF-8"?>
                <wfs:Transaction service="WFS" version="1.1.0"
                    xmlns:wfs="http://www.opengis.net/wfs"
                    xmlns:ogc="http://www.opengis.net/ogc"
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xsi:schemaLocation="http://www.opengis.net/wfs http://schemas.opengis.net/wfs/1.1.0/wfs.xsd">
                    <wfs:Update typeName="' . $layer . '">
                        <wfs:Property>
                            <wfs:Name>d_nop</wfs:Name>
                            <wfs:Value>' . htmlspecialchars($nop) . '</wfs:Value>
                        </wfs:Property>
                        <ogc:Filter>
                            <ogc:FeatureId fid="' . $idGeoServer . '"/>
                        </ogc:Filter>
                    </wfs:Update>
                </wfs:Transaction>';

            $urlGeoServer = 'http://192.168.75.15:8080/geoserver/wfs';
            $responseGeoServer = Http::timeout(15)
                ->withBasicAuth('admin', 'geoserver')
                ->withBody($xmlUtama, 'text/xml')
                ->post($urlGeoServer);
            if ($responseGeoServer->failed()) {
                throw new \Exception('Gagal menyambung ke GeoServer atau sambungan terputus.');
            }
            $resBody = $responseGeoServer->body();
            if (str_contains($resBody, 'ExceptionReport')) {
                throw new \Exception('Error GeoServer: ' . strip_tags($resBody));
            }
            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => $message
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data (Proses Dibatalkan): ' . $e->getMessage()
            ], 500);
        }
    }
    public function verifikasiInformasiPertanahan(Request $request)
    {
        $request->validate([
            'kode_wilayah' => 'required|numeric|min_digits:8',
            'nib' => 'required|numeric|min_digits:5',
            'nop' => 'required|string',
            'lokasi' => 'required|string|in:Y,T',
            'nama_sesuai' => 'required|string|in:Y,T',
            'luas_tanah' => 'required|string|in:Y,T',
            'bangunan_sesuai' => 'required|string|in:Y,T',
            'luas_bangunan' => 'required|numeric',
            'nop_gabungan' => 'required|string|in:Y,T',
            'nop_pecahan' => 'required|string|in:Y,T',
        ]);

        DB::beginTransaction();
        try {
            $nop = str_replace('.', '', str_replace('-', '', $request->nop));
            $updatedRows = DatAtrbpn::where([
                'kode_wilayah' => $request->kode_wilayah,
                'nib'          => $request->nib,
                'nop'          => $nop,
            ])->update([
                'status' => 'TERVERIFIKASI',
                'lokasi' => $request->lokasi,
                'nama_sesuai' => $request->nama_sesuai,
                'luas_sesuai' => $request->luas_tanah,
                'bangunan_sesuai' => $request->bangunan_sesuai,
                'luas_bangunan' => $request->luas_bangunan,
                'nop_gabungan' => $request->nop_gabungan,
                'nop_pecah' => $request->nop_pecahan
            ]);

            if ($updatedRows === 0) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Informasi pertanahan berhasil diverifikasi.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
    public function batalVerifikasiInformasiPertanahan(Request $request)
    {
        $request->validate([
            'kode_wilayah' => 'required|numeric|min_digits:8',
            'nib' => 'required|numeric|min_digits:5',
            'nop' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $nop = str_replace('.', '', str_replace('-', '', $request->nop));
            $updatedRows = DatAtrbpn::where([
                'kode_wilayah' => $request->kode_wilayah,
                'nib'          => $request->nib,
                'nop'          => $nop,
            ])->update([
                'status' => 'DATA AWAL',
                'lokasi' => null,
                'nama_sesuai' => null,
                'luas_sesuai' => null,
                'bangunan_sesuai' => null,
                'luas_bangunan' => null,
                'nop_gabungan' => null,
                'nop_pecah' => null
            ]);

            if ($updatedRows === 0) {
                return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
            }
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Informasi pertanahan berhasil diverifikasi.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
