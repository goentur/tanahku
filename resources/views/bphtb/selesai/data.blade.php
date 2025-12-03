@extends('layouts/contentNavbarLayout')

@section('title', 'SELESAI ATR/BPN')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')
<div class="card">
    <h5 class="card-header">DATA SELESAI ATR/BPN</h5>

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
                <a href="{{ route('bphtb.selesai') }}" class="btn btn-outline-secondary"><span class="bx bx-repeat me-1"></span>ULANGI</a>
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
                <th>TANGGAL SELESAI</th>
                <th>AKSI</th>
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
                    <td>
                        {{ $data->refJph->nama }}
                    </td>
                    <td>
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
                        {{ \App\Support\Facades\Helper::formatTanggalIndo($data->sptpd->tgl_selesai_bpn) }}
                    </td>
                    <td class="w-1">
                        @if ($data->sptpd->tgl_singkron == null)
                            @if ($data->status_pecahan)
                            <button type="button" class="btn btn-danger me-2" disabled>
                                <span class="icon-base bx bx-sync me-1"></span>SYNC
                            </button>
                            @else
                            <button type="button" onclick="singkronData({{ $data->id }})" class="btn btn-primary me-2">
                                    <span class="icon-base bx bx-sync me-1"></span>SYNC
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada berkas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@section('page-script')
<script>
    function singkronData(id) {
        Swal.fire({
            title: "Apakah Anda serius?",
            text: "Untuk menyingkronkan data ini!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#0d6efd",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            showClass: {
                popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                `
            },
            hideClass: {
                popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                `
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: `{{ route('bphtb.singkronisasi') }}`,
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: response.message || "Data berhasil disinkronkan.",
                            icon: "success"
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Gagal!",
                            text: xhr.responseJSON?.message || "Terjadi kesalahan.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }
</script>
@endsection