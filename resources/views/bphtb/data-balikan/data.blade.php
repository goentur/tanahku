@extends('layouts/contentNavbarLayout')

@section('title', 'BALIKAN')

@section('content')
<div class="card">
    <h5 class="card-header">DATA BALIKAN</h5>

    <!-- Form Pencarian & Jumlah Data -->
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-1">
                <select name="per_page" class="form-select" onchange="this.form.submit()">
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    <option value="75" {{ request('per_page') == '75' ? 'selected' : '' }}>75</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                </select>
            </div>
        </form>
        {{ $datas->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
    <table class="table table-bordered table-sm">
        <thead>
            <tr class="text-center">
                <th>NTPD</th>
                <th>NOMOR AKTA</th>
                <th>JENIS</th>
                <th>NAMA</th>
                <th>LUAS</th>
                <th>TANGGAL AKSES</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($datas as $data)
            @php
                $nop = $data->nop;
                $nop1 = substr($nop, 0, 2);
                $nop2 = substr($nop, 2, 2);
                $nop3 = substr($nop, 4, 3);
                $nop4 = substr($nop, 7, 3);
                $nop5 = substr($nop, 10, 3);
                $nop6 = substr($nop, 13, 4);
                $nop7 = substr($nop, 17, 1);
                
                $ntpd = $data->ntpd;
                $ntpd1 = substr($ntpd, 0, 2);
                $ntpd2 = substr($ntpd, 2, 2);
                $ntpd3 = substr($ntpd, 4, 4); // TAHUN
                $ntpd4 = substr($ntpd, 8, 4); // BUNDEL
                $ntpd5 = substr($ntpd, 12, 3); // NO URUT
            @endphp
                <tr>
                    <td class="w-1">
                        <p class="m-0 fw-bold">{{ $ntpd3 }}.{{ $ntpd4 }}.{{ $ntpd5 }}</p>
                        <small class="text-muted text-nowrap mb-0">
                            {{ $nop1 }}.{{ $nop2 }}.{{ $nop3 }}.{{ $nop4 }}.{{ $nop5 }}-{{ $nop6 }}.{{ $nop7 }}
                        </small>
                    </td>
                    <td class="w-1">
                        <p class="m-0 fw-bold">{{ $data->nomor_akta }}</p>
                        <small class="text-muted text-nowrap mb-0">
                            {{ $data->tanggal_akta }}
                        </small>
                    </td>
                    <td>
                        <p class="m-0 fw-bold">{{ $data->jenis_hak }}</p>
                        <small class="text-muted text-nowrap mb-0">
                            NIB : {{ $data->nomor_induk_bidang }}
                        </small>
                    </td>
                    <td>
                        <p class="m-0 fw-bold">{{ $data->nama_wp }}</p>
                        <small class="text-muted text-nowrap mb-0">
                            NID : {{ $data->nik }}
                        </small>
                    </td>
                    <td class="text-center">{{ $data->luastanah_op }} m<sup>2</sup></td>
                    <td>{{ $data->tanggal_akses }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection