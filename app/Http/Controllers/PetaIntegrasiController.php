<?php

namespace App\Http\Controllers;

use App\Models\BPHTB\DatPerolehanHak;
use App\Models\BPHTB\DatPerolehanHakLog;
use App\Models\BPHTB\Sptpd;
use App\Models\PBB\DatObjekPajak;
use App\Models\PBB\Sppt;
use App\Repositories\BphtbRepository;
use App\Services\Geoserver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class PetaIntegrasiController extends Controller
{
    public function __construct(
        protected Geoserver $service,
        protected BphtbRepository $bphtb_repository,
    ) {}
    public function peta(): View
    {
        return view('bphtb.peta.peta-integrasi');
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

    public function dataBPHTB()
    {
        $query = Sptpd::select('kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op')
            ->where('tahun_sptpd', '>=', 2020)
            ->where('status_pembayaran_sptpd', '=', 1)
            ->groupBy('kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op')
            ->get()->map(function ($item) {
                $item->nopGabungan =
                    $item->kd_propinsi .
                    $item->kd_dati2 .
                    $item->kd_kecamatan .
                    $item->kd_kelurahan .
                    $item->kd_blok .
                    $item->no_urut .
                    $item->kd_jns_op;
                return $item;
            });
        return response()->json($query);
    }
    public function dataInformasi(Request $request): View
    {
        $request->validate([
            'nop' => 'nullable|numeric|digits:18',
            'datakirim' => 'required|array',
            'bphtb' => 'nullable|string|in:ada,tidak',
        ]);
        $dataKirim = $request->datakirim;
        $urls = [];
        $sppt = null;
        $objekPajak = null;
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

                if ($largestFile) {
                    $urls[] = '/storage/foto/' . $folder . '/' . basename($largestFile);
                }
                $sppt = Sppt::with('pembayaranSppt')
                    ->where('kd_propinsi', $nop1)
                    ->where('kd_dati2', $nop2)
                    ->where('kd_kecamatan', $nop3)
                    ->where('kd_kelurahan', $nop4)
                    ->where('kd_blok', $nop5)
                    ->where('no_urut', $nop6)
                    ->where('kd_jns_op', $nop7)
                    ->where('thn_pajak_sppt', date('Y'))
                    ->first();
            }
        }
        if ($request->bphtb == 'ada') {
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
            return view('bphtb.peta.informasi-data', compact('objekPajak', 'dataKirim', 'urls', 'sppt', 'bphtb'));
        } else {
            return view('bphtb.peta.informasi-data', compact('objekPajak', 'dataKirim', 'urls', 'sppt'));
        }
    }

    public function updateDataNOP(Request $request)
    {
        $request->validate([
            'modal_id'    => 'required',
            'modal_layer' => 'required',
            'modal_nib'   => 'required|string|max:50',
            'modal_nop'   => 'required|string|max:50',
        ]);

        $layer = $request->modal_layer;
        $id    = $request->modal_id;
        $nop   = $request->modal_nop;
        $xmlPayload = '<?xml version="1.0" encoding="UTF-8"?>
        <wfs:Transaction service="WFS" version="1.1.0"
          xmlns:wfs="http://www.opengis.net/wfs"
          xmlns:ogc="http://www.opengis.net/ogc"
          xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
          xsi:schemaLocation="http://www.opengis.net/wfs http://schemas.opengis.net/wfs/1.1.0/wfs.xsd">
          
          <wfs:Update typeName="bpn:' . $layer . '">
            <wfs:Property>
              <wfs:Name>d_nop</wfs:Name>
              <wfs:Value>' . htmlspecialchars($nop) . '</wfs:Value>
            </wfs:Property>
            <ogc:Filter>
              <ogc:FeatureId fid="' . $id . '"/>
            </ogc:Filter>
          </wfs:Update>
        </wfs:Transaction>';

        try {
            // 3. Tembak ke API GeoServer WFS dengan Basic Auth
            $urlGeoServer = 'http://192.168.75.15:8080/geoserver/wfs'; // Sesuaikan URL GeoServer Anda

            $response = Http::withBasicAuth('admin', 'geoserver') // Sesuaikan username & password GeoServer
                ->withBody($xmlPayload, 'text/xml')
                ->post($urlGeoServer);

            // 4. Cek respon dari GeoServer
            if ($response->failed()) {
                return response()->json(['status' => 'error', 'message' => 'Gagal terhubung ke GeoServer.'], 500);
            }

            $resBody = $response->body();

            // Cek apakah ada element <wfs:Exception> di dalam return XML dari GeoServer
            if (str_contains($resBody, 'ExceptionReport')) {
                return response()->json(['status' => 'error', 'message' => 'GeoServer Error: ' . strip_tags($resBody)], 400);
            }



            return response()->json([
                'status' => 'success',
                'message' => 'Properti d_nop berhasil diupdate via WFS-T.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
