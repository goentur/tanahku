@if ($dataKirim['status'] == 'final')
<div class="alert alert-success" role="alert"><i class="fa fa-check"></i> DATA SUDAH TERFERIFIKASI</div>
@else
<div class="d-grid gap-2 mb-2">
	<button class="btn btn-primary btn-sm btn-edit-pbb"
		type="button"
		data-bs-toggle="modal"
		data-bs-target="#exampleModal">
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
</div>
@if (($dataKirim['status'] == 'belum' && $objekPajak) || ($dataKirim['status'] == 'final' && $objekPajak && $sudahVerifikasi))
<div class="accordion mb-2" id="accordionVerifikasi">
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-varifikasi" aria-expanded="false" aria-controls="panelsStayOpen-varifikasi">
				VERIFIKASI{{ $dataKirim['status'] == 'final' && $objekPajak && $sudahVerifikasi ?' ULANG':'' }} DATA
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
									<input class="form-check-input" type="radio" name="lokasi" id="lokasi_ya" value="Y" checked>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="lokasi_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="lokasi" id="lokasi_tidak" value="T">
								</label>
							</td>
						</tr>
						<tr>
							<td>NAMA SESUAI</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nama_sesuai_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nama_sesuai" id="nama_sesuai_ya" value="Y" checked>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nama_sesuai_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nama_sesuai" id="nama_sesuai_tidak" value="T">
								</label>
							</td>
						</tr>
						<tr>
							<td>LUAS TANAH PBB X BPN</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="luas_tanah_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="luas_tanah" id="luas_tanah_ya" value="Y" checked>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="luas_tanah_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="luas_tanah" id="luas_tanah_tidak" value="T">
								</label>
							</td>
						</tr>
						<tr>
							<td>BANGUANAN SESUAI</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="bangunan_sesuai_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="bangunan_sesuai" id="bangunan_sesuai_ya" value="Y" checked>
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="bangunan_sesuai_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="bangunan_sesuai" id="bangunan_sesuai_tidak" value="T">
								</label>
							</td>
						</tr>
						<tr>
							<td class="align-middle">LUAS BANGUNAN</td>
							<td colspan="2">
									<input class="form-control form-control-sm" type="text" name="luas_bangunan" id="luas_bangunan" value="{{ $objekPajak?->total_luas_bng }}">
							</td>
						</tr>
						<tr>
							<td>NOP GABUNGAN</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_gabungan_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_gabungan" id="nop_gabungan_ya" value="Y">
								</label>
							</td>
							<!-- Catatan: ganti 'selected' dengan 'checked' untuk tag radio -->
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_gabungan_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_gabungan" id="nop_gabungan_tidak" value="T" checked>
								</label>
							</td>
						</tr>
						<tr>
							<td>NOP PECAHAN</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_pecahan_ya" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_pecahan" id="nop_pecahan_ya" value="Y">
								</label>
							</td>
							<td class="text-center" style="cursor: pointer;">
								<label for="nop_pecahan_tidak" class="w-100 m-0 p-0" style="cursor: pointer;">
									<input class="form-check-input" type="radio" name="nop_pecahan" id="nop_pecahan_tidak" value="T" checked>
								</label>
							</td>
						</tr>
					</table>
					<div class="d-grid gap-2 mb-2">
						<button class="btn btn-success btn-sm btn-edit-pbb" type="submit" id="btnVerifikasiDataPertanahan">
							<i class="fa fa-check"></i> VERIFIKASI DATA
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif