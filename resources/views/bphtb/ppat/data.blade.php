@extends('layouts/contentNavbarLayout')

@section('title', 'PPAT')

@section('content')
<div class="card">
    <h5 class="card-header">DATA PPAT</h5>

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
                    placeholder="Cari nama, alamat, atau telp..."
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
                <a href="{{ route('bphtb.ppat') }}" class="btn btn-outline-secondary"><span class="bx bx-repeat me-1"></span>ULANGI</a>
              </div>
            @endif
        </form>
    </div>
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>NO</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th class="w-1">TELP</th>
                <th class="W-1 text-nowrap">AKTIF ?</th>
            </tr>
        </thead>
        <tbody class="table-border-bottom-0">
            @forelse ($ppats as $ppat)
                <tr>
                    <td class="text-center w-1">{{ $loop->iteration + ($ppats->currentPage() - 1) * $ppats->perPage() }}</td>
                    <td>{{ $ppat->nama ?? '-' }}</td>
                    <td>{{ $ppat->alamat_lengkap ?? '-' }}</td>
                    <td>{{ $ppat->telp ?? '-' }}</td>
                    <td class="text-center">
                        @if ($ppat->status_aktif)
                            <span class="badge bg-label-success me-1">YA</span>
                        @else
                            <span class="badge bg-label-danger me-1">TIDAK</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Tidak ada data PPAT.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!-- Pagination -->
    <div class="card-footer d-flex justify-content-end">
        {{ $ppats->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection