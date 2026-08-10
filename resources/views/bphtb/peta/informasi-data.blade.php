@if ($dataKirim['status'] == 'TERVERIFIKASI')
<div class="alert alert-info mb-2" style="font-size: 13px" role="alert">
	<b>STATUS NIB-NOP</b> : SUDAH TERPADAN
</div>		
@else
<div class="alert alert-warning mb-2" style="font-size: 13px" role="alert">
	<b>STATUS NIB-NOP</b> : BELUM TERPADAN
</div>		
@endif
<div class="accordion" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
        INFORMASI PBB
      </button>
    </h2>
    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
      <div class="accordion-body">
				<table style="font-size: 12px">
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NOP</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak?->nop }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">LOKASI</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak?->alamatLengkap }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">WAJIB PAJAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>
							{{ $objekPajak?->datSubjekPajak->nm_wp }}
						</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">ALAMAT</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak?->datSubjekPajak->alamatLengkap }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">TANAH</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak?->total_luas_bumi }} m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">BANGUNAN</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak?->total_luas_bng }} m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">FOTO</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>
              @forelse ($urls as $url)
                <a href="{{ $url }}" data-fancybox="gallery"><img src="{{ $url }}" class="img-fluid" width="50%" alt="" srcset=""></a>
              @empty
                  <span class="text-danger">FOTO TIDAK ADA</span>
              @endforelse
            </td>
					</tr>
				</table>
			</div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
        INFORMASI PERTANAHAN
      </button>
    </h2>
    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
      <div class="accordion-body">
        <table style="font-size: 12px">
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">KODE WILAYAH</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['KODEWILAYA'] ?? '-' }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NIB</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['NIB'] ?? '-' }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">LUAS TANAH</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['LUASTERTUL'] ?? '-' }} m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">TIPE HAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['TIPEHAK'] ?? '-' }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NO HAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Nomor_Hak'] ?? '-' }}</td>
					</tr>
				</table>
      </div>
    </div>
  </div>
</div>