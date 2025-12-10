<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Peta Pekalongan - OpenLayers</title>
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

	<!-- OpenLayers CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v9.0.0/ol.css" />

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

		/* Sidebar position */
		.sidebar {
			position: absolute;
			top: 9px;
			left: 9px;
			width: 375px;
			z-index: 1000;
		}

		/* Koordinat footer */
		.coord-footer {
			font-size: 0.85rem;
			color: #666;
			border-top: 1px solid #dee2e6;
			padding: 8px 12px;
			text-align: center;
		}
	</style>
</head>

<body>
	<div id="map"></div>
	<div class="sidebar">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);">
			<div class="card-header d-flex gap-2">
				<input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari Lokasi..." />
				<button id="searchBtn" class="btn btn-sm btn-primary">Cari</button>
			</div>
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" data-bs-toggle="tab" href="#dataLayerTab">DATA LAYER</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="tab" href="#informasiTab">INFORMASI</a>
				</li>
			</ul>

			<div class="card-body p-3">
				<div class="tab-content">
					<div class="tab-pane fade show active" id="dataLayerTab">
						<ul class="list-group list-group-flush">
							<li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.1);">
								<div class="row">
									<div class="col-1" style="background-color: #ff0000; height: 20px;"></div>
									<div class="col-11">BELUM BAYAR</div>
								</div>
							</li>
							<li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.1);">
								<div class="row">
									<div class="col-1" style="background-color: #39FF14; height: 20px;"></div>
									<div class="col-11">WASDAL</div>
								</div>
							</li>
							<li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.1);">
								<div class="row">
									<div class="col-1" style="background-color: #0000FF; height: 20px;"></div>
									<div class="col-11">PEMERIKSAAN</div>
								</div>
							</li>
							<li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.1);">
								<div class="row">
									<div class="col-1" style="background-color: #FFFF00; height: 20px;"></div>
									<div class="col-11">KURANG BAYAR</div>
								</div>
							</li>
							<li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.1);">
								<div class="row">
									<div class="col-1" style="background-color: #FF1493; height: 20px;"></div>
									<div class="col-11">PROSES ATR/BPN</div>
								</div>
							</li>
							<li class="list-group-item" style="background-color: rgba(255, 255, 255, 0.1);">
								<div class="row">
									<div class="col-1" style="background-color: #00FFFF; height: 20px;"></div>
									<div class="col-11">SELESAI ATR/BPN</div>
								</div>
							</li>
						</ul>
					</div>
					<div class="tab-pane fade" id="informasiTab">
						<table>
							<tr>
								<td>Kecamatan</td>
								<td class="w-1">:</td>
								<td class="fw-bold">Pekalongan Timur</td>
							</tr>
							<tr>
								<td>Kelurahan</td>
								<td class="w-1">:</td>
								<td class="fw-bold">Gamer</td>
							</tr>
						</table>
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

	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- OpenLayers -->
	<script src="https://cdn.jsdelivr.net/npm/ol@v9.0.0/dist/ol.js"></script>
	<script>
		$(document).ready(function() {
			let dataBPHTB = [];
			let selectedFeature = null; // Simpan fitur yang sedang dipilih

			// === Fungsi Style Default (dengan label dinamis) ===
			function getDefaultStyle(feature, resolution) {
				const d_nop = feature.get('d_nop');
				const matchedItem = dataBPHTB.find(item => item.noptanpaFormat === d_nop);

				let strokeColor = 'white';
				let fillColor = 'rgba(255, 255, 255, 0.1)';

				if (matchedItem && matchedItem.status) {
					const status = String(matchedItem.status);
					switch (status) {
						case '4':
							strokeColor = '#FF0000';
							fillColor = 'rgba(255, 0, 0, 0.4)';
							break;
						case '5':
							strokeColor = '#39FF14';
							fillColor = 'rgba(57, 255, 20, 0.4)';
							break;
						case '6':
							strokeColor = '#0000FF';
							fillColor = 'rgba(0, 0, 255, 0.4)';
							break;
						case '7':
							strokeColor = '#FFFF00';
							fillColor = 'rgba(255, 255, 0, 0.4)';
							break;
						case '11':
							strokeColor = '#FF1493';
							fillColor = 'rgba(255, 20, 147, 0.4)';
							break;
						case '12':
							strokeColor = '#00FFFF';
							fillColor = 'rgba(0, 255, 255, 0.4)';
							break;
						default:
							strokeColor = '#CCCCCC';
							fillColor = 'rgba(204, 204, 204, 0.2)';
							break;
					}
				}

				// --- Hitung luas untuk kontrol label ---
				const geometry = feature.getGeometry();
				let areaM2 = 0;
				if (geometry.getType() === 'Polygon') {
					areaM2 = ol.sphere.getArea(geometry);
				} else if (geometry.getType() === 'MultiPolygon') {
					const polygons = geometry.getPolygons();
					for (const poly of polygons) {
						areaM2 += ol.sphere.getArea(poly);
					}
				}
				const pixelPerMeter = 1 / resolution;
				const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
				const MIN_AREA_PX2 = 10000;
				const showLabel = areaPx2 >= MIN_AREA_PX2;

				const nop = feature.get('nop') || '';
				const nib = feature.get('NIB') || '';
				const labelText = showLabel && nop ? (nib ? `${nop}\n${nib}` : nop) : '';

				return new ol.style.Style({
					stroke: new ol.style.Stroke({
						color: strokeColor,
						width: 1
					}),
					fill: new ol.style.Fill({
						color: fillColor
					}),
					text: labelText ? new ol.style.Text({
						text: labelText,
						font: '12px Arial, sans-serif',
						fill: new ol.style.Fill({
							color: '#FFFFFF'
						}),
						stroke: new ol.style.Stroke({
							color: '#000000',
							width: 0.1
						}),
						overflow: true,
						textAlign: 'center',
						textBaseline: 'middle',
						maxAngle: 0,
						offsetY: -10
					}) : undefined
				});
			}

			// === Fungsi Style Saat Dipilih (highlight) ===
			function getSelectedStyle(feature, resolution) {
				const d_nop = feature.get('d_nop');
				const matchedItem = dataBPHTB.find(item => item.noptanpaFormat === d_nop);

				let strokeColor = '#EFBF04';
				let fillColor = 'rgba(0, 0, 0, 0)';

				if (matchedItem && matchedItem.status) {
					const status = String(matchedItem.status);
					switch (status) {
						case '4':
							strokeColor = '#FF0000';
							fillColor = 'rgba(255, 0, 0, 0.8)';
							break;
						case '5':
							strokeColor = '#00AA00';
							fillColor = 'rgba(57, 255, 20, 0.8)';
							break;
						case '6':
							strokeColor = '#0000AA';
							fillColor = 'rgba(0, 0, 255, 0.8)';
							break;
						case '7':
							strokeColor = '#AAAA00';
							fillColor = 'rgba(255, 255, 0, 0.8)';
							break;
						case '11':
							strokeColor = '#AA0077';
							fillColor = 'rgba(255, 20, 147, 0.8)';
							break;
						case '12':
							strokeColor = '#00AAAA';
							fillColor = 'rgba(0, 255, 255, 0.8)';
							break;
						default:
							strokeColor = '#FF00FF';
							fillColor = 'rgba(255, 0, 255, 0.8)';
							break;
					}
				}

				// Label tetap muncul saat dipilih (opsional)
				const geometry = feature.getGeometry();
				let areaM2 = 0;
				if (geometry.getType() === 'Polygon') {
					areaM2 = ol.sphere.getArea(geometry);
				} else if (geometry.getType() === 'MultiPolygon') {
					const polygons = geometry.getPolygons();
					for (const poly of polygons) {
						areaM2 += ol.sphere.getArea(poly);
					}
				}

				const pixelPerMeter = 1 / resolution;
				const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
				const MIN_AREA_PX2 = 10000;
				const showLabel = areaPx2 >= MIN_AREA_PX2;

				const nop = feature.get('nop') || '';
				const nib = feature.get('NIB') || '';
				const labelText = showLabel && nop ? (nib ? `${nop}\n${nib}` : nop) : '';

				return new ol.style.Style({
					stroke: new ol.style.Stroke({
						color: strokeColor,
						width: 3 // lebih tebal saat dipilih
					}),
					fill: new ol.style.Fill({
						color: fillColor
					}),
					text: labelText ? new ol.style.Text({
						text: labelText,
						font: '12px Arial, sans-serif',
						fill: new ol.style.Fill({
							color: '#FFFFFF'
						}),
						stroke: new ol.style.Stroke({
							color: '#000000',
							width: 0.1
						}),
						overflow: true,
						textAlign: 'center',
						textBaseline: 'middle',
						maxAngle: 0,
						offsetY: -10
					}) : undefined
				});
			}

			const vectorSource = new ol.source.Vector();
			const vectorLayer = new ol.layer.Vector({
				source: vectorSource,
				style: getDefaultStyle
			});

			// Google Satellite Layer
			const googleSatelliteLayer = new ol.layer.Tile({
				source: new ol.source.XYZ({
					url: 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
					attributions: 'Map data ©2025 Google',
					maxZoom: 19
				})
			});

			const map = new ol.Map({
				target: 'map',
				layers: [googleSatelliteLayer, vectorLayer],
				view: new ol.View({
					center: ol.proj.fromLonLat([109.6753, -6.8885]),
					zoom: 16
				})
			});

			// === Event Klik ===
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
					const datakirim = {
						'LUASTERTUL' : clickedFeature.get('LUASTERTUL'),
						'NIB' : clickedFeature.get('NIB'),
						'Nomor_Hak' : clickedFeature.get('Nomor_Hak'),
						'Pemilik_Ak' : clickedFeature.get('Pemilik_Ak'),
						'Surat_Ukur' : clickedFeature.get('Surat_Ukur'),
						'TIPEHAK' : clickedFeature.get('TIPEHAK'),
						'TIPEHAK' : clickedFeature.get('TIPEHAK'),
					};
					const matchedItem = dataBPHTB.find(item => item.noptanpaFormat === clickedFeature.get('d_nop'));
					selectedFeature.setStyle(function(feature, resolution) {
						return getSelectedStyle(feature, resolution);
					});
					$('a[href="#informasiTab"]').tab('show');
					$('#informasidata').html(`
						<div class="text-center py-3">
							<div class="spinner-border text-primary" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							<p class="mt-2">Memuat informasi...</p>
						</div>
					`);
					loadInformasiData(clickedFeature.get('d_nop'), datakirim, matchedItem?.id)
				}
			});

			async function loadInformasiData(nop, datakirim, bphtb) {
				$.ajax({
					url: '{{ route("beranda.data-informasi") }}',
					type: 'POST',
					data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						nop: nop,
						datakirim: datakirim,
						bphtb: bphtb
					},
					dataType:'HTML',
					success: function(htmlResponse) {
						$('#informasidata').html(htmlResponse);
					},
					error: function(xhr, status, error) {
						console.error('Error:', error);
						$('#informasidata').html('<p class="text-danger">Gagal memuat informasi.</p>');
					}
				});
			}

			// === Muat Data ===
			async function loadPetaData() {
				try {
					const response = await fetch('{{ route("beranda.data-peta") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					if (!response.ok) throw new Error('Gagal mengambil data peta');
					const geojsonData = await response.json();
					vectorSource.clear();
					const features = new ol.format.GeoJSON().readFeatures(geojsonData, {
						dataProjection: 'EPSG:4326',
						featureProjection: 'EPSG:3857'
					});
					vectorSource.addFeatures(features);
				} catch (err) {
					console.error(err);
					alert('Gagal memuat data peta.');
				}
			}

			async function loadDataBphtb() {
				try {
					const response = await fetch('{{ route("beranda.data-bphtb") }}', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json',
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					if (!response.ok) throw new Error('Gagal mengambil data BPHTB');
					dataBPHTB = await response.json();
					vectorLayer.changed();
				} catch (err) {
					console.error(err);
					alert('Gagal memuat data BPHTB.');
				}
			}

			loadPetaData();
			loadDataBphtb();
		});
	</script>
</body>

</html>