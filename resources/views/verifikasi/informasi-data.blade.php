@if ($dataKirim['status'] == 'final')
<div class="alert alert-success" role="alert"><i class="fa fa-check"></i> DATA SUDAH TERFERIFIKASI</div>
@else
@if ($dataKirim['status'] == 'belum' && $objekPajak)
<div class="d-grid gap-2 mb-2">
	<button class="btn btn-success btn-sm btn-edit-pbb"
		type="button"
		id="btnVerifikasiDataPertanahan">
		<i class="fa fa-check"></i> VERIFIKASI DATA
	</button>
</div>
@endif
<div class="d-grid gap-2 mb-2">
	<button class="btn btn-primary btn-sm btn-edit-pbb"
		type="button"
		data-bs-toggle="modal"
		data-bs-target="#exampleModal"'>
		<i class="fa fa-pencil"></i> UBAH DATA
	</button>
</div>
@endif
<div class="accordion" id="accordionPanelsStayOpenExample">
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button {{ $objekPajak ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="{{ $objekPajak ? 'true' : 'false' }}" aria-controls="panelsStayOpen-collapseOne">
				INFORMASI PBB
			</button>
		</h2>
		<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse {{ $objekPajak ? 'show' : '' }}">
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
			<button class="accordion-button {{ $objekPajak ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="{{ $objekPajak ? 'false' : 'true' }}" aria-controls="panelsStayOpen-collapseTwo">
				INFORMASI PERTANAHAN
			</button>
		</h2>
		<div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse {{ $objekPajak ? '' : 'show' }}">
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
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">PEMILIK PERTAMA</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Pemilik_Pe'] ?? '-' }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">PEMILIK AKHIR</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Pemilik_Ak'] ?? '-' }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>