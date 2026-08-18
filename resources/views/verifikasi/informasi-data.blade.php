@if ($datAtrBpn?->status == 'TERVERIFIKASI')
<div class="alert alert-success" role="alert"><i class="fa fa-check"></i> DATA SUDAH TERFERIFIKASI</div>
@else
<div class="d-grid gap-2 mb-2">
	<button class="btn btn-primary btn-edit-pbb"
		type="button"
		data-bs-toggle="modal"
		data-bs-target="#exampleModal">
		<i class="fa fa-pencil"></i> UBAH DATA
	</button>
</div>
@endif
@empty(!$datAtrBpn)
	<div class="alert alert-info mb-2" style="font-size: 13px" role="alert">
		<b>SUMBER DATA</b> : {{ $datAtrBpn->sumber_data }}
	</div>
@endempty
<div class="accordion" id="accordionPanelsStayOpenExample">
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button {{ $objekPajak ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="{{ $objekPajak ? 'true' : 'false' }}" aria-controls="panelsStayOpen-collapseOne">
				INFORMASI OBJEK PAJAK
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
						<td>{{ $objekPajak?->alamatLengkap }}, {{ $objekPajak?->refKelurahan->nm_kelurahan }}</td>
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
						<td>{{ $objekPajak?->total_luas_bumi }} m<sup>2</sup> <b>NJOP </b>: {{ $objekPajak?->njop_bumi >0 ? number_format($objekPajak?->njop_bumi/$objekPajak?->total_luas_bumi):0 }}/m<sup>2</sup></td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">BANGUNAN</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak?->total_luas_bng }} m<sup>2</sup> <b>NJOP </b>: {{ $objekPajak?->njop_bng >0 ? number_format($objekPajak?->njop_bng/$objekPajak?->total_luas_bng):0 }}/m<sup>2</sup></td>
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
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-informasiPajak" aria-expanded="false" aria-controls="panelsStayOpen-informasiPajak">
        INFORMASI PAJAK
      </button>
    </h2>
    <div id="panelsStayOpen-informasiPajak" class="accordion-collapse collapse">
      <table class="table-bordered" style="font-size: 12px; width:100%">
				<tr>
					<th class="text-center">SPPT</th>
					<th class="text-center">Ketetapan</th>
					<th class="text-center">Pembayaran</th>
					<th class="text-center">Tunggakan</th>
				</tr>
				<tr>
					<td class="text-center">{{ number_format($sppt['total']) }}</td>
					<td class="text-end">{{ number_format($sppt['tunggakan']) }}</td>
					<td class="text-end">{{ number_format($sppt['bayar']) }}</td>
					<td class="text-end">{{ number_format($sppt['tunggakan']-$sppt['bayar']) }}</td>
				</tr>
			</table>
    </div>
  </div>
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
				INFORMASI PERTANAHAN
			</button>
		</h2>
		<div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
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
	@if (@$bphtb)
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        RIWAYAT BPHTB
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
      <table style="font-size: 12px" class="table table-bordered table-sm">
        <tr>
          <th style="width: 1px">Nomor</th>
          <th style="width: 1px">NJOP</th>
          <th>Nilai Perolehan</th>
          <th>Nilai Perolehan /m</th>
        </tr>
        @foreach ($bphtb as $item)
        <tr>
          <td class="text-nowrap">{{ $item->datPerolehanHak->tahun_perolehan }}.{{ $item->datPerolehanHak->bundel_perolehan }}.{{ $item->datPerolehanHak->no_urut_perolehan }}</td>
          <td class="text-nowrap text-end">{{ number_format($item->njop_pbb) }}</td>
          <td class="text-nowrap text-end">{{ number_format($item->nilai_perolehan) }}</td>
          <td class="text-nowrap text-end">{{ $item->nilai_perolehan>0?number_format($item->nilai_perolehan/$item->luas_bumi):0 }}</td>
        </tr>
        @endforeach
      </table>
    </div>
  </div>
	@endif
</div>
@if (($objekPajak))
<div class="accordion mb-2" id="accordionVerifikasi">
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-varifikasi" aria-expanded="false" aria-controls="panelsStayOpen-varifikasi">
				VERIFIKASI{{$datAtrBpn?->status == 'TERVERIFIKASI' ?' ULANG':'' }} DATA
			</button>
		</h2>
		<div id="panelsStayOpen-varifikasi" class="accordion-collapse collapse">
			<div class="accordion-body">
				<form action="" id="formVerifikasi" method="post">
					<table class="table table-sm table-bordered" style="font-size: 12px">
						<tr>
							<th>KONDISI</th>
							<th style="width: 1px">YA</th>
							<th style="width: 1px">TIDAK</th>
						</tr>
						<tr>
							<td>LOKASI</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="lokasi_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="lokasi" id="lokasi_ya" value="Y" {{empty($datAtrBpn->lokasi) || $datAtrBpn->lokasi == 'Y' ? 'checked':'' }}>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="lokasi_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="lokasi" id="lokasi_tidak" value="T" {{!empty($datAtrBpn) && $datAtrBpn->lokasi == 'T' ? 'checked':'' }}>
								</label>
							</td>
						</tr>
						<tr>
							<td>NAMA SESUAI</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nama_sesuai_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nama_sesuai" id="nama_sesuai_ya" value="Y" {{empty($datAtrBpn->nama_sesuai) || $datAtrBpn->nama_sesuai == 'Y' ? 'checked':'' }}>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nama_sesuai_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nama_sesuai" id="nama_sesuai_tidak" value="T" {{!empty($datAtrBpn) && $datAtrBpn->nama_sesuai == 'T' ? 'checked':'' }}>
								</label>
							</td>
						</tr>
						<tr>
							<td>LUAS TANAH PBB X BPN</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="luas_tanah_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="luas_tanah" id="luas_tanah_ya" value="Y" {{empty($datAtrBpn->luas_sesuai) || $datAtrBpn->luas_sesuai == 'Y' ? 'checked':'' }}>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="luas_tanah_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="luas_tanah" id="luas_tanah_tidak" value="T" {{!empty($datAtrBpn) && $datAtrBpn->luas_sesuai == 'T' ? 'checked':'' }}>
								</label>
							</td>
						</tr>
						<tr>
							<td>BANGUANAN SESUAI</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="bangunan_sesuai_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="bangunan_sesuai" id="bangunan_sesuai_ya" value="Y" {{empty($datAtrBpn->bangunan_sesuai) || $datAtrBpn->bangunan_sesuai == 'Y' ? 'checked':'' }}>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="bangunan_sesuai_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="bangunan_sesuai" id="bangunan_sesuai_tidak" value="T" {{!empty($datAtrBpn) && $datAtrBpn->bangunan_sesuai == 'T' ? 'checked':'' }}>
								</label>
							</td>
						</tr>
						<tr>
							<td class="align-middle">LUAS BANGUNAN</td>
							<td colspan="2">
									<input class="form-control form-control-sm" type="text" name="luas_bangunan" id="luas_bangunan" value="{{ empty($datAtrBpn->luas_bangunan) ? $objekPajak?->total_luas_bng : $datAtrBpn->luas_bangunan }}">
							</td>
						</tr>
						<tr>
							<td>NOP GABUNGAN</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_gabungan_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_gabungan" id="nop_gabungan_ya" value="Y" {{!empty($datAtrBpn) && $datAtrBpn->nop_gabungan == 'Y' ? 'checked':'' }}>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_gabungan_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_gabungan" id="nop_gabungan_tidak" value="T" {{empty($datAtrBpn->nop_gabungan) || $datAtrBpn->nop_gabungan == 'T' ? 'checked':'' }}>
								</label>
							</td>
						</tr>
						<tr>
							<td>NOP PECAHAN</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_pecahan_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_pecahan" id="nop_pecahan_ya" value="Y" {{!empty($datAtrBpn) && $datAtrBpn->nop_pecah == 'Y' ? 'checked':'' }}>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_pecahan_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_pecahan" id="nop_pecahan_tidak" value="T" {{empty($datAtrBpn->nop_pecah) || $datAtrBpn->nop_pecah == 'T' ? 'checked':'' }}>
								</label>
							</td>
						</tr>
					</table>
					@if (!empty($datAtrBpn) && $datAtrBpn->verifikator != null && $datAtrBpn->tgl_verifikasi != null)
					<table class="table table-sm table-bordered" style="font-size: 12px">
						<tr>
							<th class="text-center" colspan="2">TERVERIFIKASI</th>
						</tr>
						<tr>
							<th style="width: 1px">OLEH</th>
							<th>{{ $datAtrBpn->verifikator }}</th>
						</tr>
						<tr>
							<th>TANGGAL</th>
							<th>{{ $datAtrBpn->tgl_verifikasi }}</th>
						</tr>
					</table>
					@endif
					<div class="d-grid gap-2 mb-2">
						<button class="btn btn-success btn-sm" type="submit" id="btnVerifikasiDataPertanahan">
							<i class="fa fa-check"></i> VERIFIKASI{{$datAtrBpn?->status == 'TERVERIFIKASI' ?' ULANG':'' }} DATA
						</button>
						@if ($datAtrBpn?->status == 'TERVERIFIKASI')
						<button class="btn btn-danger btn-sm" type="button" id="btnBatalVerifikasiDataPertanahan">
							<i class="fa fa-reply"></i> BATAL VERIFIKASI DATA
						</button>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif