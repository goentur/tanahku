<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>VERIFIKASI PETA INTEGRASI</title>
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

	<!-- OpenLayers CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v9.0.0/ol.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

	<style>
		body {
			margin: 0;
			padding: 0;
		}

		#map {
			width: 100%;
			height: 100vh;
			background-color: #f0f0f0;
		}

		.sidebar {
			position: absolute;
			top: 9px;
			left: 9px;
			width: 375px;
			z-index: 1000;
		}

		.coord-footer {
			font-size: 0.85rem;
			color: #666;
			border-top: 1px solid #dee2e6;
			padding: 8px 12px;
			text-align: center;
		}

		.legend {
			position: absolute;
			bottom: 50px;
			right: 30px;
			width: auto;
			z-index: 1000;
			font-family: sans-serif;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
			border-radius: 4px;
		}
	</style>
</head>

<body>
	<div id="map"></div>
	<div class="sidebar">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);max-height: calc(100vh - 10px); overflow-y: auto;">
			<div class="accordion accordion-flush" id="accordionFlushPencarian">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-pencarian" aria-expanded="false" aria-controls="flush-pencarian">
							CARI PETA BIDANG TANAH
						</button>
					</h2>
					<div id="flush-pencarian" class="accordion-collapse collapse" data-bs-parent="#accordionFlushPencarian">
						<div class="mx-3 mt-3">
							<!-- Dropdown Field Pencarian -->
							<select id="searchField" class="form-select form-select-sm mb-1" aria-label="Field pencarian">
								<option selected value="">Pilih salah satu</option>
								<option value="d_nop">NOP</option>
								<option value="NIB">NIB</option>
								<option value="Nomor_Hak">Nomor Hak</option>
							</select>

							<!-- Input Keyword -->
							<input id="searchKeyword" class="form-control form-control-sm mb-1" type="text" placeholder="Masukkan kata kunci pencarian">

							<!-- Tombol Cari -->
							<button id="searchBtn" class="btn btn-primary btn-sm mb-1"><i class="fa fa-search"></i> Cari</button>

							<!-- Info Hasil & Navigasi (hidden by default) -->
							<div id="searchInfo" class="d-none small text-muted mb-2"></div>
							<div id="searchNav" class="d-none mb-3">
								<button id="prevBtn" class="btn btn-sm btn-outline-secondary me-1">
									<i class="fa fa-chevron-left"></i> Sebelumnya
								</button>
								<button id="nextBtn" class="btn btn-sm btn-outline-secondary">
									Selanjutnya <i class="fa fa-chevron-right"></i>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" data-bs-toggle="tab" href="#layerTab">LAYER</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="tab" href="#informasiTab">INFORMASI</a>
				</li>
			</ul>

			<div class="card-body p-3">
				<div class="tab-content">
					<div class="tab-pane fade show active" id="layerTab">
						<table>
							<tr>
								<td style="width: 100%" colspan="2" class="fw-bold">PEKALONGAN BARAT</td>
							</tr>
							@foreach ($barat as $item)
							<tr>
								<td style="width: 90%">{{ $item->nama }}</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:{{ $item->kd_wilayah }}',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							@endforeach
						</table>
						<table class="w-100 mt-3">
							<tr>
								<td style="width: 100%" colspan="2" class="fw-bold">PEKALONGAN TIMUR</td>
							</tr>
							@foreach ($timur as $item)
							<tr>
								<td style="width: 90%">{{ $item->nama }}</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:{{ $item->kd_wilayah }}',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							@endforeach
						</table>
						<table class="w-100 mt-3">
							<tr>
								<td style="width: 100%" colspan="2" class="fw-bold">PEKALONGAN SELATAN</td>
							</tr>
							@foreach ($selatan as $item)
							<tr>
								<td style="width: 90%">{{ $item->nama }}</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:{{ $item->kd_wilayah }}',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							@endforeach
						</table>
						<table class="w-100 mt-3">
							<tr>
								<td style="width: 100%" colspan="2" class="fw-bold">PEKALONGAN UTARA</td>
							</tr>
							@foreach ($utara as $item)
							<tr>
								<td style="width: 90%">{{ $item->nama }}</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:{{ $item->kd_wilayah }}',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							@endforeach
						</table>
					</div>
					<div class="tab-pane fade" id="informasiTab">
						<div id="informasidata">
							<div class="alert alert-info mb-0">
								Belum ada data yang dipilih.
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="legend">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.85); font-size: 0.85rem;">
			<div class="card-body p-2">
				<h6 class="mb-2">Legenda</h6>
				<ul class="list-unstyled mb-0">
					<li class="d-flex align-items-center justify-content-between mb-1">
						<div class="d-flex align-items-center">
							<div style="width: 15px; height: 15px; background-color: #FFFFFF; margin-right: 8px;"></div>
							<span>BIDANG BIASA</span>
						</div>
						<span class="badge bg-secondary ms-2" id="count-biasa">0</span>
					</li>
					<li class="d-flex align-items-center justify-content-between mb-1">
						<div class="d-flex align-items-center">
							<div style="width: 15px; height: 15px; background-color: #AF46FF; margin-right: 8px;"></div>
							<span>BIDANG DATA VALIDASI</span>
						</div>
						<span style="background-color: #AF46FF" class="badge text-dark ms-2" id="count-data-validasi">0</span>
					</li>
					<li class="d-flex align-items-center justify-content-between mb-1">
						<div class="d-flex align-items-center">
							<div style="width: 15px; height: 15px; background-color: #00FFFF; margin-right: 8px;"></div>
							<span>BIDANG DATA BALIKAN ATR/BPN</span>
						</div>
						<span class="badge bg-info text-dark ms-2" id="count-data-awal">0</span>
					</li>
					<li class="d-flex align-items-center justify-content-between">
						<div class="d-flex align-items-center">
							<div style="width: 15px; height: 15px; background-color: #15ff00; margin-right: 8px;"></div>
							<span>BIDANG TERVERIFIKASI</span>
						</div>
						<span class="badge bg-success ms-2" id="count-terverifikasi">0</span>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-99" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="formModalPerubahanData" method="post">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Form Perubahan Data</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3">
									<label for="modal_kode_wilayah" class="form-label">KODE WILAYAH</label>
									<input required type="text" placeholder="Masukan Kode Wilayah" name="modal_kode_wilayah" class="form-control" id="modal_kode_wilayah">
									<input type="hidden" name="modal_id_peta_bidang" class="form-control" id="modal_id_peta_bidang">
								</div>
								<div class="mb-3">
									<label for="modal_nib" class="form-label">NIB</label>
									<input required type="text" placeholder="Masukan NIB" name="modal_nib" class="form-control" id="modal_nib">
								</div>
								<div class="mb-3">
									<label for="modal_nop" class="form-label">NOP</label>
									<input required type="text" autofocus="true" placeholder="Masukan NOP" name="modal_nop" class="form-control" id="modal_nop">
								</div>
							</div>
							<div class="col-md-6">
								<div class="mb-3">
									<label for="modal_pemilik_awal" class="form-label">PEMILIK AWAL</label>
									<input type="text" placeholder="Masukan Nama Pemilik Awal" name="modal_pemilik_awal" class="form-control" id="modal_pemilik_awal">
								</div>
								<div class="mb-3">
									<label for="modal_pemilik_akhir" class="form-label">PEMILIK AKHIR</label>
									<input type="text" placeholder="Masukan Nama Pemilik Akhir" name="modal_pemilik_akhir" class="form-control" id="modal_pemilik_akhir">
								</div>
								<div class="mb-3">
									<label for="modal_luas" class="form-label">LUAS</label>
									<input required type="text" placeholder="Masukan Luas Tanah" name="modal_luas" class="form-control" id="modal_luas">
								</div>
								<div class="mb-3">
									<label for="modal_tipe_hak" class="form-label">TIPE HAK</label>
									<input required type="text" placeholder="Masukan Tipe Hak" name="modal_tipe_hak" class="form-control" id="modal_tipe_hak">
								</div>
								<div class="mb-3">
									<label for="modal_no_hak" class="form-label">NO HAK</label>
									<input type="text" placeholder="Masukan Nomor Hak" name="modal_no_hak" class="form-control" id="modal_no_hak">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Tutup</button>
						<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/ol@v9.0.0/dist/ol.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		$(document).ready(function() {
      let datSudahVerifikasi = {}; // Struktur sekarang: { 'layerId1': [data], 'layerId2': [data] }
      let selectedFeature = null;
      const baseLayers = {};
      let dataPersilTerpilih = null;
      const mapLayers = [];
			let dataBPHTB = [];

      $('#modal_nop').mask('00.00.000.000.000-0000.0');

      // =========================================================================
      // HELPER FUNGSIONAL: Mencari item terverifikasi di dalam struktur Object Key-Value
      // =========================================================================
      function findMatchedItem(futureKODEWILAYA, futureNIB) {
        let matched = null;
        // Looping setiap layerId yang ada di dalam object datSudahVerifikasi
        Object.keys(datSudahVerifikasi).forEach(layerId => {
          const arrayData = datSudahVerifikasi[layerId];
          if (Array.isArray(arrayData)) {
            const found = arrayData.find(item => 
              String(item.kode_wilayah) === String(futureKODEWILAYA) && String(item.nib) === String(futureNIB)
            );
            if (found) {
              matched = found; // Jika ketemu, simpan ke variabel
            }
          }
        });
        return matched;
      }

      function getDefaultStyle(feature, resolution) {
        const futureNIB = feature.get('NIB');
        const futureKODEWILAYA = feature.get('KODEWILAYA');
        
        // PERUBAHAN SOLUSI 2: Menggunakan fungsi helper pencarian object
        const matchedItem = findMatchedItem(futureKODEWILAYA, futureNIB);

        let strokeColor = 'white';
        let fillColor = 'rgba(255, 255, 255, 0.1)';
        let nop = '';
        if (matchedItem) {
					const { status, sumber_data } = matchedItem;
					if (['DATA AWAL', 'DATA MENTAH'].includes(status) && sumber_data?.includes('VALIDASI')) {
						strokeColor = '#AF46FF';
						fillColor = 'rgba(175, 70, 255, 0.4)';
					} else if (status === 'DATA AWAL') {
						strokeColor = '#00FFFF';
						fillColor = 'rgba(0, 255, 255, 0.4)';
					} else if (status === 'TERVERIFIKASI') {
						strokeColor = '#15ff00';
						fillColor = 'rgba(21, 255, 0, 0.4)';
					}
          nop = matchedItem.nop || '';
					
					const matchedItemBPHTB = dataBPHTB.find(item => item.nopGabungan === nop);
					if (matchedItemBPHTB) {
						strokeColor = '#ff0000';
					}
        }
        const geometry = feature.getGeometry();
        let areaM2 = 0;
        if (geometry) {
          if (geometry.getType() === 'Polygon') {
            areaM2 = ol.sphere.getArea(geometry);
          } else if (geometry.getType() === 'MultiPolygon') {
            const polygons = geometry.getPolygons();
            for (const poly of polygons) {
              areaM2 += ol.sphere.getArea(poly);
            }
          }
        }

        const pixelPerMeter = 1 / resolution;
        const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
        const MIN_AREA_PX2 = 10000;
        const showLabel = areaPx2 >= MIN_AREA_PX2;

        // --- PROSES POTONG & FORMAT TEXT LABELLING NOP ---
        const nib = feature.get('NIB') || '';
        let labelText = showLabel ? `${nib}` : null;

        if (nop) {
          const last8 = nop.slice(-8);
          const nopPotong = last8.length === 8 ? `${last8.slice(0, 3)}-${last8.slice(3, 7)}.${last8.slice(7)}` : last8;
          if (showLabel) {
						const matchedItem = dataBPHTB.find(item => item.nopGabungan === nop);
						if (matchedItem) {
							labelText = nib ? `${nopPotong}\n${nib}\n${'⭐'}` : '';
						} else {
							labelText = nib ? `${nopPotong}\n${nib}` : '';
						}
          }
        }

        // --- RETURN STYLE KE OPENLAYERS ---
        return new ol.style.Style({
          stroke: new ol.style.Stroke({
            color: strokeColor,
            width: 3
          }),
          fill: new ol.style.Fill({
            color: fillColor
          }),
          text: labelText ? new ol.style.Text({
            text: labelText,
            font: 'bold 13px Arial, sans-serif',
            fill: new ol.style.Fill({
              color: '#FFFFFF'
            }),
            stroke: new ol.style.Stroke({
              color: '#000000',
              width: 2
            }),
            overflow: true,
            textAlign: 'center',
            textBaseline: 'middle',
            maxAngle: 0,
            offsetY: -10
          }) : undefined
        });
      }


      function getSelectedStyle(feature, resolution) {
        const futureNIB = feature.get('NIB');
        const futureKODEWILAYA = feature.get('KODEWILAYA');
        
        // PERUBAHAN SOLUSI 2: Menggunakan fungsi helper pencarian object
        const matchedItem = findMatchedItem(futureKODEWILAYA, futureNIB);

        let strokeColor = '#FFD700';
        let fillColor = 'rgba(255, 215, 0, 0.2)';
        let nop = '';
        if (matchedItem) {
          nop = matchedItem.nop || '';
        }

        const geometry = feature.getGeometry();
        let areaM2 = 0;
        if (geometry) {
          if (geometry.getType() === 'Polygon') {
            areaM2 = ol.sphere.getArea(geometry);
          } else if (geometry.getType() === 'MultiPolygon') {
            const polygons = geometry.getPolygons();
            for (const poly of polygons) {
              areaM2 += ol.sphere.getArea(poly);
            }
          }
        }

        const pixelPerMeter = 1 / resolution;
        const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
        const MIN_AREA_PX2 = 10000;
        const showLabel = areaPx2 >= MIN_AREA_PX2;

        // --- PROSES POTONG & FORMAT TEXT LABELLING NOP ---
        const nib = feature.get('NIB') || '';
        let labelText = showLabel ? `${nib}` : null;

        if (nop) {
          const last8 = nop.slice(-8);
          const nopPotong = last8.length === 8 ? `${last8.slice(0, 3)}-${last8.slice(3, 7)}.${last8.slice(7)}` : last8;
          if (showLabel) {
						const matchedItem = dataBPHTB.find(item => item.nopGabungan === nop);
						if (matchedItem) {
							labelText = nib ? `${nopPotong}\n${nib}\n${'⭐'}` : '';
						} else {
							labelText = nib ? `${nopPotong}\n${nib}` : '';
						}
          }
        }
        return new ol.style.Style({
          stroke: new ol.style.Stroke({
            color: strokeColor,
            width: 3
          }),
          fill: new ol.style.Fill({
            color: fillColor
          }),
          text: labelText ? new ol.style.Text({
            text: labelText,
            font: 'bold 13px Arial, sans-serif',
            fill: new ol.style.Fill({
              color: '#FFFFFF'
            }),
            stroke: new ol.style.Stroke({
              color: '#000000',
              width: 2
            }),
            overflow: true,
            textAlign: 'center',
            textBaseline: 'middle',
            maxAngle: 0,
            offsetY: -10
          }) : undefined
        });
      }

      // Style untuk highlight hasil pencarian (BORDER GOLD)
      function getSearchHighlightStyle(feature, resolution) {
        const futureNIB = feature.get('NIB');
        const futureKODEWILAYA = feature.get('KODEWILAYA');
        
        // PERUBAHAN SOLUSI 2: Menggunakan fungsi helper pencarian object
        const matchedItem = findMatchedItem(futureKODEWILAYA, futureNIB);

        let strokeColor = '#FFD700';
        let fillColor = 'rgba(255, 215, 0, 0.5)';
        let nop = '';
        if (matchedItem) {
          nop = matchedItem.nop || '';
        }
        const geometry = feature.getGeometry();
        let areaM2 = 0;
        if (geometry) {
          if (geometry.getType() === 'Polygon') {
            areaM2 = ol.sphere.getArea(geometry);
          } else if (geometry.getType() === 'MultiPolygon') {
            const polygons = geometry.getPolygons();
            for (const poly of polygons) {
              areaM2 += ol.sphere.getArea(poly);
            }
          }
        }

        const pixelPerMeter = 1 / resolution;
        const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
        const MIN_AREA_PX2 = 10000;
        const showLabel = areaPx2 >= MIN_AREA_PX2;

        // --- PROSES POTONG & FORMAT TEXT LABELLING NOP ---
        const nib = feature.get('NIB') || '';
        let labelText = showLabel ? `${nib}` : null;

        if (nop) {
          const last8 = nop.slice(-8);
          const nopPotong = last8.length === 8 ? `${last8.slice(0, 3)}-${last8.slice(3, 7)}.${last8.slice(7)}` : last8;
          if (showLabel) {
						const matchedItem = dataBPHTB.find(item => item.nopGabungan === nop);
						if (matchedItem) {
							labelText = nib ? `${nopPotong}\n${nib}\n${'⭐'}` : '';
						} else {
							labelText = nib ? `${nopPotong}\n${nib}` : '';
						}
          }
        }
        return new ol.style.Style({
          stroke: new ol.style.Stroke({
            color: strokeColor,
            width: 4
          }),
          fill: new ol.style.Fill({
            color: fillColor
          }),
          text: labelText ? new ol.style.Text({
            text: labelText,
            font: 'bold 14px Arial, sans-serif',
            fill: new ol.style.Fill({
              color: '#FFFFFF'
            }),
            stroke: new ol.style.Stroke({
              color: '#000000',
              width: 3
            }),
            overflow: true,
            textAlign: 'center',
            textBaseline: 'middle',
            maxAngle: 0,
            offsetY: -10
          }) : undefined
        });
      }

      // === Setup Peta ===
      const googleSatelliteLayer = new ol.layer.Tile({
        source: new ol.source.XYZ({
          url: 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
          attributions: 'Map data ©2025 Google',
          maxZoom: 19
        })
      });

      const map = new ol.Map({
        target: 'map',
        layers: [googleSatelliteLayer],
        view: new ol.View({
          center: ol.proj.fromLonLat([109.6987027, -6.8871928]),
          zoom: 16
        })
      });

      map.on('click', function(evt) {
        let clickedFeature = null;
        map.forEachFeatureAtPixel(evt.pixel, function(feature) {
          clickedFeature = feature;
        });

        if (selectedFeature) {
          selectedFeature.setStyle(null);
          selectedFeature = null;
        }
        if (clickedFeature) {
          selectedFeature = clickedFeature;
          const futureNIB = clickedFeature.get('NIB');
          const futureKODEWILAYA = clickedFeature.get('KODEWILAYA');
          
          // PERUBAHAN SOLUSI 2: Menggunakan fungsi helper pencarian object
          const matchedItem = findMatchedItem(futureKODEWILAYA, futureNIB);

          selectedFeature.setStyle(function(feature, resolution) {
            return getSelectedStyle(feature, resolution);
          });
          let nop = '';
          let status = 'belum';
          if (matchedItem) {
            nop = matchedItem.nop || '';
            status = (matchedItem.status == 'TERVERIFIKASI') ? 'final' : 'belum';
          }
					const bphtbMatch = dataBPHTB.find(item => item.nopGabungan === nop);
          const datakirim = {
            'ID_PETA_BIDANG': clickedFeature.getId(),
            'KODEWILAYA': clickedFeature.get('KODEWILAYA'),
            'NIB': clickedFeature.get('NIB'),
            'LUASTERTUL': clickedFeature.get('LUASTERTUL'),
            'Nomor_Hak': clickedFeature.get('Nomor_Hak'),
            'TIPEHAK': clickedFeature.get('TIPEHAK'),
            'Pemilik_Pe': clickedFeature.get('Pemilik_Pe'),
            'Pemilik_Ak': clickedFeature.get('Pemilik_Ak'),
            'nop': nop,
            'bphtb': bphtbMatch ? 'YA' : 'NO',
            'status': status,
          };
          dataPersilTerpilih = datakirim;
          $('a[href="#informasiTab"]').tab('show');
          $('#informasidata').html(`<div class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat informasi...</p></div>`);
          loadInformasiData(datakirim);
        }
      });

      function loadInformasiData(datakirim) {
        $.ajax({
          url: '{{ route("verifikasi-peta-integrasi.informasi-pertanahan") }}',
          type: 'POST',
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            datakirim: datakirim
          },
          success: function(htmlResponse) {
            $('#informasidata').html(htmlResponse);
            $('[data-fancybox]').fancybox({
              buttons: ['zoom', 'close'],
              loop: true
            });
          },
          error: function(xhr, status, error) {
            $('#informasidata').html('<p class="text-danger">Gagal memuat informasi.</p>');
          }
        });
      }

      async function refreshDataVerifikasi(layerId) {
        try {
          datSudahVerifikasi[layerId] = await $.ajax({
            url: '{{ route("verifikasi-peta-integrasi.data-sudah-verifikasi") }}',
            method: 'POST',
            data: {
              _token: $('meta[name="csrf-token"]').attr('content'),
              'wilayah': layerId
            }
          });
          updateLegendCounters();
        } catch (error) {
          console.error("Gagal memperbarui data verifikasi:", error);
        }
      }

			$.ajax({
				url: '{{ route("beranda.data-bphtb") }}',
				method: 'POST',
				data: {
					_token: $('meta[name="csrf-token"]').attr('content')
				},
				success: function(response) {
					dataBPHTB = response;
				},
				error: function() {
					alert('Gagal memuat data BPHTB.');
				}
			});

      window.toggleLayer = async function(layerId, buttonElement) {
        if (!baseLayers[layerId]) {
          const $btn = $(buttonElement);
          $btn.prop('disabled', true);

          try {
            await refreshDataVerifikasi(layerId);

            const geojsonData = await $.ajax({
              url: '{{ route("verifikasi-peta-integrasi.data-peta") }}',
              type: 'POST',
              data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: layerId
              }
            });
            const source = new ol.source.Vector();
            const layer = new ol.layer.Vector({
              source: source,
              style: getDefaultStyle
            });

            const format = new ol.format.GeoJSON();
            const features = format.readFeatures(geojsonData, {
              dataProjection: 'EPSG:4326',
              featureProjection: 'EPSG:3857'
            });

            source.addFeatures(features);
            map.addLayer(layer);

            baseLayers[layerId] = {
              layer,
              button: buttonElement,
              visible: true
            };
            
            updateLegendCounters();

            setTimeout(function() {
              const extent = source.getExtent();
              if (extent && extent[0] !== Infinity) {
                map.getView().fit(extent, {
                  padding: [50, 50, 50, 50],
                  duration: 1000,
                  maxZoom: 18
                });
              } else if (features.length === 1) {
                const geom = features[0].getGeometry();
                if (geom) {
                  const coord = geom.getType() === 'Point' ? geom.getCoordinates() : geom.getExtent();
                  map.getView().setCenter(coord);
                  map.getView().setZoom(16);
                }
              }
            }, 100);

            $btn.removeClass('btn-primary').addClass('btn-danger')
              .find('i').removeClass('fa-eye').addClass('fa-eye-slash');

          } catch (error) {
            console.error("Gagal memproses layer:", error);
            Swal.fire("Error", "Terjadi kesalahan sistem saat memuat data dan Peta.", "error");
          } finally {
            $btn.prop('disabled', false);
          }

        } else {
          const entry = baseLayers[layerId];
          const isVisible = entry.layer.getVisible();
          const newVisible = !isVisible;

          entry.layer.setVisible(newVisible);
          entry.visible = newVisible;

          updateLegendCounters();

          const $btn = $(entry.button);
          if (newVisible) {
            $btn.removeClass('btn-primary').addClass('btn-danger');
            $btn.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
          } else {
            $btn.removeClass('btn-danger').addClass('btn-primary');
            $btn.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
          }
        }
      };

      // === FUNGSI HITUNG TOTAL BIDANG DINAMIS PER JENIS ===
      function updateLegendCounters() {
        let totalBiasa = 0;
        let totalValidasi = 0;
        let totalDataAwal = 0;
        let totalTerverifikasi = 0;

        Object.keys(baseLayers).forEach(layerId => {
          const entry = baseLayers[layerId];
          
          if (entry && entry.layer && entry.layer.getVisible()) {
            const source = entry.layer.getSource();
            if (source) {
              const features = source.getFeatures();
              
              features.forEach(feature => {
                const futureNIB = feature.get('NIB');
                const futureKODEWILAYA = feature.get('KODEWILAYA');
                
                // PERUBAHAN SOLUSI 2: Menggunakan fungsi helper pencarian object
                const matchedItem = findMatchedItem(futureKODEWILAYA, futureNIB);

                if (matchedItem) {
									const { status, sumber_data } = matchedItem;
									if (['DATA AWAL', 'DATA MENTAH'].includes(status) && sumber_data?.includes('VALIDASI')) {
										totalValidasi++;
									} else if (status === 'DATA AWAL') {
										totalDataAwal++;
									} else if (status === 'TERVERIFIKASI') {
										totalTerverifikasi++;
									}else{
										totalBiasa++;
									}
                } else {
                  totalBiasa++;
                }
              });
            }
          }
        });

        $('#count-biasa').text(totalBiasa);
        $('#count-data-validasi').text(totalValidasi);
        $('#count-data-awal').text(totalDataAwal);
        $('#count-terverifikasi').text(totalTerverifikasi);
      }

			// === FITUR PENCARIAN BERDASARKAN FIELD ===
			let searchResults = [];
			let currentResultIndex = 0;
			let searchHighlightedFeature = null;
			// Event listener tombol cari
			$('#searchBtn').on('click', function() {
				executeFieldSearch();
			});

			// Event listener Enter key
			$('#searchKeyword').on('keypress', function(e) {
				if (e.which === 13) {
					e.preventDefault();
					executeFieldSearch();
				}
			});

			// Fungsi utama pencarian
			// Fungsi utama pencarian dengan validasi layer aktif
			function executeFieldSearch() {
				const field = $('#searchField').val();
				const keyword = $('#searchKeyword').val().trim();
				const infoEl = $('#searchInfo');
				const navEl = $('#searchNav');

				// === VALIDASI 1: Input field & keyword ===
				if (!field) {
					Swal.fire("Error", "⚠️ Silakan pilih jenis pencarian (NOP / NIB / Nomor Hak).", "error");
					return;
				}
				if (!keyword) {
					Swal.fire("Error", "⚠️ Silakan masukkan kata kunci pencarian.", "error");
					return;
				}

				// === VALIDASI 2: Cek layer yang aktif (visible) ===
				const activeLayers = [];
				for (const [layerId, entry] of Object.entries(baseLayers)) {
					if (entry.layer.getVisible()) {
						activeLayers.push({
							id: layerId,
							entry: entry
						});
					}
				}

				// Jika tidak ada layer aktif
				if (activeLayers.length === 0) {
					alert('⚠️ Tidak ada layer yang aktif!\n\nSilakan aktifkan minimal 1 layer (klik tombol mata biru) sebelum melakukan pencarian.');
					return;
				}

				// Opsional: Warning jika terlalu banyak layer aktif (performa)
				if (activeLayers.length > 5) {
					const confirmSearch = confirm(`⚠️ Anda mengaktifkan ${activeLayers.length} layer.\n\nPencarian mungkin lebih lambat.\n\nLanjutkan pencarian?`);
					if (!confirmSearch) return;
				}


				// === RESET STATE SEBELUMNYA ===
				clearSearchHighlights();
				searchResults = [];
				currentResultIndex = 0;
				infoEl.addClass('d-none').text('').removeClass('text-danger');
				navEl.addClass('d-none');

				const keywordLower = keyword.toLowerCase();

				// === HANYA ITERASI LAYER YANG AKTIF ===
				activeLayers.forEach(layerObj => {
					const source = layerObj.entry.layer.getSource();
					if (!source) return;

					const features = source.getFeatures();

					features.forEach(feature => {
						const props = feature.getProperties();
						const fieldValue = props[field];

						if (!fieldValue) return;

						// Normalisasi & partial match
						const normalizedField = normalizeSearchString(fieldValue);
						const normalizedKeyword = normalizeSearchString(keyword);

						if (normalizedField.includes(normalizedKeyword)) {
							searchResults.push({
								feature: feature,
								layerId: layerObj.id // Simpan info layer asal
							});
						}
					});
				});

				// === TAMPILKAN HASIL ===
				if (searchResults.length > 0) {
					infoEl.removeClass('d-none').html(`
						✓ Ditemukan <strong>${searchResults.length}</strong> hasil 
						di <strong>${activeLayers.length}</strong> layer aktif
					`);

					if (searchResults.length > 1) {
						navEl.removeClass('d-none');
					}

					// Zoom ke hasil pertama
					navigateToResult(0);

				} else {
					// Tidak ada hasil
					const layerNames = activeLayers.map(l => {
						// Ambil nama layer dari tombol (opsional, untuk info lebih detail)
						const btn = $(l.entry.button);
						return btn.closest('tr').find('td:first').text().trim();
					}).join(', ');

					infoEl.removeClass('d-none').addClass('text-danger').html(`
						✗ Tidak ada hasil ditemukan di ${activeLayers.length} layer aktif
					`);

					setTimeout(() => {
						alert(`❌ Tidak ditemukan data dengan ${getFieldLabel(field)}: "${keyword}"\n\n📍 Layer aktif: ${activeLayers.length}\n${layerNames ? '📋 ' + layerNames : ''}\n\n💡 Tips:\n• Pastikan layer yang benar sudah diaktifkan\n• Cek ejaan dan format data\n• Coba gunakan sebagian kata kunci`);
						infoEl.addClass('d-none').removeClass('text-danger');
					}, 100);
				}
			}

			// Helper: konversi field value ke label yang ramah user
			function getFieldLabel(field) {
				const labels = {
					// 'd_nop': 'NOP',
					'NIB': 'NIB',
					// 'Nomor_Hak': 'Nomor Hak'
				};
				return labels[field] || field;
			}

			// Navigasi ke hasil pencarian + zoom + gold border
			function navigateToResult(index) {
				if (searchResults.length === 0) return;

				// Validasi index (circular navigation)
				if (index < 0) index = searchResults.length - 1;
				if (index >= searchResults.length) index = 0;

				currentResultIndex = index;
				const result = searchResults[currentResultIndex];
				const feature = result.feature;
				const layerId = result.layerId;

				// 🔥 RESET highlight sebelumnya
				if (searchHighlightedFeature && searchHighlightedFeature !== feature) {
					searchHighlightedFeature.setStyle(null);
				}

				// 🔥 APPLY GOLD BORDER
				searchHighlightedFeature = feature;
				feature.setStyle(function(feature, resolution) {
					return getSearchHighlightStyle(feature, resolution);
				});

				// 🔥 ZOOM & FIT ke bidang
				const geom = feature.getGeometry();
				const extent = geom.getExtent();

				map.getView().fit(extent, {
					padding: [100, 100, 100, 100],
					duration: 1200,
					maxZoom: 19
				});

				// Update info navigasi dengan info layer
				updateSearchInfo(layerId);

				// Tooltip
				showSearchTooltip(feature, currentResultIndex + 1, searchResults.length);
			}

			// Update info text: "Hasil 2 dari 5"
			// Update info text hasil pencarian
			function updateSearchInfo(layerId) {
				const field = $('#searchField').val();
				const fieldName = getFieldLabel(field);
				const result = searchResults[currentResultIndex];
				const value = result.feature.get(field) || '-';

				// Ambil nama layer dari tombol (jika ada)
				let layerName = '';
				if (layerId && baseLayers[layerId]) {
					const btn = $(baseLayers[layerId].button);
					layerName = btn.closest('tr').find('td:first').text().trim();
				}

				$('#searchInfo').html(`
					<strong>Hasil ${currentResultIndex + 1} dari ${searchResults.length}</strong><br>
					<span class="badge bg-info text-dark">${layerName || layerId}</span><br>
					${fieldName}: <code>${value}</code>
				`);
			}

			// Normalisasi string: hapus separator & lowercase
			function normalizeSearchString(str) {
				if (!str) return '';
				return String(str)
					.toLowerCase()
					.replace(/[\s.\-,/\\]+/g, '');
			}

			function clearSearchHighlights() {
				// Reset style fitur yang sebelumnya di-highlight
				if (searchHighlightedFeature) {
					searchHighlightedFeature.setStyle(null); // Kembali ke style default layer
					searchHighlightedFeature = null;
				}

				// Hapus tooltip jika ada
				const tooltip = document.querySelector('.search-tooltip');
				if (tooltip && tooltip.parentNode) {
					tooltip.parentNode.removeChild(tooltip);
				}
			}

			// Event listener navigasi Next/Prev
			$('#nextBtn').on('click', function() {
				navigateToResult(currentResultIndex + 1);
			});

			$('#prevBtn').on('click', function() {
				navigateToResult(currentResultIndex - 1);
			});

			// Reset search saat field atau keyword berubah
			$('#searchField, #searchKeyword').on('change input', function() {
				clearSearchHighlights();
				$('#searchInfo').addClass('d-none');
				$('#searchNav').addClass('d-none');
				searchResults = [];
			});

			$(document).on('click', '.btn-edit-pbb', function() {
				if (!dataPersilTerpilih) {
					Swal.fire("Error", "Silakan pilih salah satu bidang tanah di peta terlebih dahulu!.", "error");
					return;
				}
				const dataKirim = dataPersilTerpilih;
				$('#modal_id_peta_bidang').val(dataKirim.ID_PETA_BIDANG);
				$('#modal_kode_wilayah').val(dataKirim.KODEWILAYA);
				$('#modal_nib').val(dataKirim.NIB);
				$('#modal_nop').val(dataKirim.nop);
				$('#modal_pemilik_awal').val(dataKirim.Pemilik_Pe);
				$('#modal_pemilik_akhir').val(dataKirim.Pemilik_Ak);
				$('#modal_luas').val(dataKirim.LUASTERTUL);
				$('#modal_tipe_hak').val(dataKirim.TIPEHAK);
				$('#modal_no_hak').val(dataKirim.Nomor_Hak);
			});

			$('#exampleModal').on('shown.bs.modal', function() {
				$('#modal_nop').trigger('input');
				$('#modal_nop').trigger('focus');
			});

			$('#formModalPerubahanData').on('submit', function(e) {
				e.preventDefault();
				const btnSubmit = $(this).find('button[type="submit"]');
				btnSubmit.prop('disabled', true).text('Menyimpan...');
				const activeLayerId = "bpn:" + $('#modal_kode_wilayah').val();
				const formData = new FormData(this);
				$.ajax({
					url: '{{ route("verifikasi-peta-integrasi.simpan-informasi-pertanahan") }}',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
						'Accept': 'application/json'
					},
					success: async function(data) {
						if (data.status === 'success') {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil!',
								text: data.message,
								timer: 3000,
								showConfirmButton: true
							});
							$('#exampleModal').modal('hide'); // Tutup modal
							if (activeLayerId) {
								await refreshDataVerifikasi(activeLayerId);
								if (baseLayers[activeLayerId]) {
									baseLayers[activeLayerId].layer.getSource().changed(); 
								}
								loadInformasiData(dataPersilTerpilih);
							}
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: data.message || 'Gagal menyimpan data.'
							});
						}
					},
					error: function(xhr, status, error) {
						const response = xhr.responseJSON;
						if (xhr.status === 422 && response.errors) {
							let pesanError = '<ul style="text-align: left; list-style-position: inside;">';
							$.each(response.errors, function(key, val) {
								pesanError += `<li>${val[0]}</li>`;
							});
							pesanError += '</ul>';
							Swal.fire({
								icon: 'warning',
								title: 'Validasi Gagal',
								html: pesanError
							});

						} else if (response && response.message) {
							Swal.fire({
								icon: 'error',
								title: 'Terjadi Kesalahan',
								text: response.message
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: 'Terjadi kesalahan sistem internal.'
							});
						}
					},
					complete: function() {
						btnSubmit.prop('disabled', false).text('Simpan Perubahan');
					}
				});
			});
			$(document).on('click', '#btnVerifikasiDataPertanahan', function(e) {
				e.preventDefault();
				if (!dataPersilTerpilih) {
					Swal.fire("Peringatan", "Tidak ada data bidang tanah yang dipilih!", "warning");
					return;
				}
				const activeLayerId = "bpn:"+dataPersilTerpilih.KODEWILAYA;
				Swal.fire({
					title: "Apakah Anda Serius?",
					text: "Ingin memverifikasi data ini!",
					icon: "question",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: "Ya!",
					cancelButtonText: "Tidak jadi!"
				}).then((result) => {
					if (result.isConfirmed) {
						const btnSubmit = $(this);
						btnSubmit.prop('disabled', true).text('Memproses...');
						// 1. Ambil data dari form verifikasi yang di-load via AJAX
						const formData = $('#formVerifikasi').serialize();

						// 2. Gabungkan data form dengan data tambahan yang sudah ada
						const dataKirim = $.param({
							'kode_wilayah': dataPersilTerpilih.KODEWILAYA,
							'nib': dataPersilTerpilih.NIB,
							'nop': dataPersilTerpilih.nop
						})+ '&' + formData;

						$.ajax({
							url: '{{ route("verifikasi-peta-integrasi.verifikasi-informasi-pertanahan") }}',
							type: 'POST',
							data: dataKirim,
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
								'Accept': 'application/json'
							},
							success: async function(data) {
								if (data.status === 'success') {
									Swal.fire("Berhasil!", data.message, "success");
									dataPersilTerpilih.status = 'final';
									if (activeLayerId) {
										await refreshDataVerifikasi(activeLayerId);
										if (baseLayers[activeLayerId]) {
											baseLayers[activeLayerId].layer.getSource().changed(); 
										}
										loadInformasiData(dataPersilTerpilih);
									}
								} else {
									Swal.fire("Gagal", data.message, "error");
								}
							},
							error: function(xhr, status, error) {
								const response = xhr.responseJSON;
								if (xhr.status === 422 && response.errors) {
									let pesanError = 'Validasi Gagal:<br>';
									$.each(response.errors, function(key, val) {
										pesanError += `- ${val[0]}<br>`;
									});
									Swal.fire("Gagal Validasi", pesanError, "error");
								} else if (response && response.message) {
									Swal.fire("Kesalahan", response.message, "error");
								} else {
									Swal.fire("Error", "Terjadi kesalahan sistem internal.", "error");
								}
							},
							complete: function() {
								if(btnSubmit.length) btnSubmit.prop('disabled', false).text('Verifikasi Data');
							}
						});
					}
				});
			});
		});
	</script>
</body>

</html>