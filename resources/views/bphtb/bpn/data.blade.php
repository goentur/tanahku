@extends('layouts/contentNavbarLayout')

@section('title', 'PROSES ATR/BPN')

@section('content')
<div class="card">
    <h5 class="card-header">DATA PROSES ATR/BPN</h5>

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
            <div class="col-md-4">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Cari nama wajib pajak, nama PPAT..."
                    value="{{ request('search') }}"
                >
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary me-2">
                  <span class="bx bx-search me-1"></span>CARI
                </button>
            </div>
            @if(request('search') || request('per_page'))
              <div class="col-md-1">
                <a href="{{ route('bphtb.bpn') }}" class="btn btn-outline-secondary"><span class="bx bx-repeat me-1"></span>ULANGI</a>
              </div>
            @endif
        </form>
        {{ $datas->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
    <table class="table table-bordered table-sm">
        <thead>
            <tr class="text-center">
                <th>BERKAS</th>
                <th>JENIS</th>
                <th>PENERIMA</th>
                <th>PAJAK</th>
                <th>PPAT</th>
                <th>TANGGAL PROSES</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($datas as $data)
                <tr>
                    <td class="w-1">
                        <p class="m-0 fw-bold">{{ $data->nomor }}</p>
                        <small class="text-muted text-nowrap mb-0">
                            {{ $data->nop }}
                        </small>
                    </td>
                    <td class="text-nowrap w-1">
                        {{ $data->refJph->nama }}
                    </td>
                    <td class="text-nowrap w-1">
                        <p class="m-0 fw-bold">{{ $data->wpPenerimaHak->nama }}</p>
                        <small class="text-muted mb-0">
                        {{ $data->wpPenerimaHak->nid}}
                        </small>
                    </td>
                    <td class="text-nowrap w-1 text-end">
                        @if ($data->skpdkb)
                            <p class="m-0">
                                {{ \App\Support\Facades\Helper::ribuan($data->skpdkb->bphtb_skpdkb - $data->skpdkb->faktor_pengurang_skpdkb) }}
                            </p>
                            <small class="text-muted mb-0 text-decoration-line-through">
                                {{\App\Support\Facades\Helper::ribuan($data->skpdkb->bphtb_yg_telah_dibayar)}}
                            </small>
                        @else
                            <p class="m-0">
                                {{\App\Support\Facades\Helper::ribuan($data->bphtb_yg_harus_dibayar)}}
                            </p>
                            @if ($data->faktor_pengurang_bphtb > 0)
                                <small class="text-muted mb-0 text-decoration-line-through">
                                    {{\App\Support\Facades\Helper::ribuan($data->bphtb)}}
                                </small>
                            @endif
                        @endif
                    </td>
                    <td>
                        <p class="m-0 fw-bold">{{ $data->ppat->nama }}</p>
                        <small class="text-muted mb-0">
                        {{\App\Support\Facades\Helper::formatTanggalIndo($data->created_at)}}
                        </small>
                    </td>
                    <td class="text-nowrap w-1">
                        {{ \App\Support\Facades\Helper::formatTanggalIndo($data->sptpd->tgl_akses_bpn) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Tidak ada berkas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection