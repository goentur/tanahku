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
						<td>{{ $objekPajak->nop }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">LOKASI</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak->alamatLengkap }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">WAJIB PAJAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak->datSubjekPajak->nm_wp }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">ALAMAT</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak->datSubjekPajak->alamatLengkap }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">TANAH</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak->total_luas_bumi }} m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">BANGUNAN</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak->total_luas_bng }} m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">PAJAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ \App\Support\Facades\Helper::ribuan($sppt?->pbb_terhutang_sppt) }} {{ $sppt?->pembayaranSppt?->sum('jml_sppt_yg_dibayar') >= $sppt?->pbb_terhutang_sppt ? 'Sudah Bayar' : 'Belum Bayar' }}</td>
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
						<td style="vertical-align: top" class="fw-bold text-nowrap">LUAS TANAH</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['LUASTERTUL'] ?? '-' }} m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NIB</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['NIB'] ?? '-' }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NO HAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Nomor_Hak'] ?? '-' }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">TIPE HAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['TIPEHAK'] ?? '-' }}</td>
					</tr>
				</table>
      </div>
    </div>
  </div>
	@if (@$bphtb)
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        BPHTB
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
      <table style="font-size: 12px" class="table table-bordered table-sm">
        <tr>
          <th>Nomor</th>
          <th>NJOP</th>
          <th>NiPer</th>
          <th>Tanah</th>
        </tr>
        @foreach ($bphtb as $item)
        <tr>
          <td>{{ $item->datPerolehanHak->tahun_perolehan }}.{{ $item->datPerolehanHak->bundel_perolehan }}{{ $item->datPerolehanHak->no_urut_perolehan }}</td>
          <td>{{ number_format($item->njop_pbb) }}</td>
          <td>{{ number_format($item->nilai_perolehan) }}</td>
          <td>{{ number_format($item->luas_bumi) }}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
	@endif
</div>