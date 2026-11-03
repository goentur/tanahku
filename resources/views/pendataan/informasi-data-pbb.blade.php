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