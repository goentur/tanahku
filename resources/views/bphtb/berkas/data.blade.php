@extends('layouts/contentNavbarLayout')

@section('title', 'BERKAS BPHTB')

@section('content')
<div class="card">
    <h5 class="card-header">DATA BERKAS</h5>

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
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>KONSEP</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>BARU</option>
                    <option value="9" {{ request('status') == '9' ? 'selected' : '' }}>KOREKSI</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>PENELITIAN</option>
                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>VERIFIKASI</option>
                    <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>BELUM BAYAR</option>
                    <option value="5" {{ request('status') == '5' ? 'selected' : '' }}>WASDAL</option>
                    <option value="6" {{ request('status') == '6' ? 'selected' : '' }}>PEMERIKSAAN</option>
                    <option value="7" {{ request('status') == '7' ? 'selected' : '' }}>KURANG BAYAR</option>
                    {{-- <option value="8" {{ request('status') == '8' ? 'selected' : '' }}>CETAK & TTE</option> --}}
                    <option value="10" {{ request('status') == '10' ? 'selected' : '' }}>SELESAI</option>
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
                <a href="{{ route('bphtb.berkas') }}" class="btn btn-outline-secondary"><span class="bx bx-repeat me-1"></span>ULANGI</a>
              </div>
            @endif
        </form>
        {{ $datas->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>BERKAS</th>
                <th>JENIS</th>
                <th>PENERIMA</th>
                <th>PAJAK</th>
                <th>PPAT</th>
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
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Tidak ada berkas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection