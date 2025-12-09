<div class="accordion" id="accordionExample">
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#informasiPbb" aria-expanded="true" aria-controls="informasiPbb">
				INFORMASI PBB
			</button>
		</h2>
		<div id="informasiPbb" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
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
						<td>{{ $objekPajak->total_luas_bumi }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">BANGUNAN</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $objekPajak->total_luas_bng }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#informasiPertanahan" aria-expanded="true" aria-controls="informasiPertanahan">
				INFORMASI PERTANAHAN
			</button>
		</h2>
		<div id="informasiPertanahan" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
			<div class="accordion-body">
				<table style="font-size: 12px">
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">LUAS TER TUL</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['LUASTERTUL'] }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NIB</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['NIB'] }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">NO HAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Nomor_Hak'] }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">PEMILIK AKHIR</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Pemilik_Ak'] }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">SURAT UKUR</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['Surat_Ukur'] }}</td>
					</tr>
					<tr>
						<td style="vertical-align: top" class="fw-bold text-nowrap">TIPE HAK</td>
						<td style="vertical-align: top" class="w-1">:</td>
						<td>{{ $dataKirim['TIPEHAK'] }}</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	@if (@$bphtb)
	<div class="accordion-item">
		<h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#informasiHistoryBphtb" aria-expanded="false" aria-controls="informasiHistoryBphtb">
				LOG BPHTB
			</button>
		</h2>
		<div id="informasiHistoryBphtb" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
			<div class="accordion-body">
				<ul class="list-unstyled">
    @forelse ($bphtb as $item)
	@php
        $warna = 'secondary'; // nilai default

        if ($item->status === 'SELESAI') {
            $warna = 'success';
        } elseif (in_array($item->status, [
            'MENUNGGU PENELITIAN',
            'DALAM PENELITIAN',
            'MENUNGGU VERIFIKASI',
            'MENUNGGU PEMERIKSAAN',
            'MENUNGGU TTE',
            'KONSEP'
        ])) {
            $warna = 'info';
        } elseif (in_array($item->status, [
            'BELUM LENGKAP',
            'KOREKSI',
            'BELUM BAYAR',
            'KURANG BAYAR'
        ])) {
            $warna = 'warning';
        } elseif ($item->status === 'DITOLAK') {
            $warna = 'danger';
        }
    @endphp
        <li class="mb-1 border-bottom">
            <div class="d-flex">
                <div class="me-3 mt-1">
                    <span class="dot bg-{{ $warna }} rounded-circle d-inline-block" style="width: 20px; height: 20px;"></span>
                </div>
                <div>
                    <div class="d-flex justify-content-between" style="font-size: 12px;">
						<div class="fw-bold text-dark text-break">
							{{ $item->status }}
						</div>
						<div class="text-muted fw-normal" style="white-space: nowrap;">
							<small>{{ $item->updated_at }}</small>
						</div>
					</div>
                    @if($item->keterangan)
                        <div class="text-muted" style="font-size: 0.75rem;">
                            {{ $item->keterangan }}
                        </div>
                    @endif
                </div>
            </div>
        </li>
    @empty
        <li class="text-center py-3 text-muted">
            <em>Tidak ada riwayat data.</em>
        </li>
    @endforelse
</ul>
			</div>
		</div>
	</div>
	@endif
</div>