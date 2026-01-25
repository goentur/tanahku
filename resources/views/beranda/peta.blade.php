<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>BATIK TANAHAN - Kota Pekalongan</title>
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
			left: 50%;
			transform: translateX(-50%);
			width: auto;
			z-index: 1000;
		}
		.sidebar-information {
			position: absolute;
			top: 9px;
			left: 0;
			width: 20%;
			z-index: 1000;
		}
	</style>
</head>
<body>
	<div id="map"></div>
	<div class="sidebar">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);">
			<div class="card-body">
				<form class="row g-3 align-items-center" method="POST" id="form-cari-data" action="javascript:void(0)">
					<div class="col-auto"><b>NOP</b></div>
					<div class="col-auto">
						<input type="text" id="nop_cari" placeholder="Masukan NOP anda" class="form-control form-control-sm">
					</div>
					<div class="col-auto"><b>NIB</b></div>
					<div class="col-auto">
						<input type="text" id="nib_cari" placeholder="Masukan NIB anda" class="form-control form-control-sm">
					</div>
					<div class="col-auto">
						<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> CARI</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="sidebar-information" style="display: none" id="sidebarInformation">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);">
			<div class="card-header">
				<h3>INFORMASI DETAIL</h3>
			</div>
			<div class="card-body" id="sidebarInformationDetail">
			</div>
		</div>
	</div>

	<!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/ol@v9.0.0/dist/ol.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

	<script>
		// === GLOBAL SCOPE: semua variabel peta di sini ===
		let map;
		let vectorSource;
		let vectorLayer;
		let selectedFeature = null;

		// Fungsi untuk menampilkan bidang dari geometry
		function tampilkanBidangDariGeometry(geometry, properties = {}) {
			if (selectedFeature) {
				selectedFeature.setStyle(undefined); // kembali ke style default
			}

			vectorSource.clear();

			const geojsonFeature = {
				type: 'Feature',
				geometry: geometry,
				properties: properties
			};

			const format = new ol.format.GeoJSON();
			const feature = format.readFeature(geojsonFeature, {
				dataProjection: 'EPSG:4326',
				featureProjection: 'EPSG:3857'
			});

			vectorSource.addFeature(feature);

			const extent = feature.getGeometry().getExtent();
			map.getView().fit(extent, {
				padding: [50, 50, 50, 50],
				duration: 800,
				maxZoom: 20
			});

			selectedFeature = feature;
		}

		$(document).ready(function () {
			// Inisialisasi peta
			vectorSource = new ol.source.Vector();
			vectorLayer = new ol.layer.Vector({
				source: vectorSource,
				style: new ol.style.Style({
					stroke: new ol.style.Stroke({
						color: '#FF8C00', // Orange
						width: 3
					}),
					fill: new ol.style.Fill({
						color: 'rgba(255, 140, 0, 0.1)'
					})
				})
			});

			const googleSatelliteLayer = new ol.layer.Tile({
				source: new ol.source.XYZ({
					url: 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
					attributions: 'Map data ©2025 Google',
					maxZoom: 19
				})
			});

			map = new ol.Map({
				target: 'map',
				layers: [googleSatelliteLayer, vectorLayer],
				view: new ol.View({
					center: ol.proj.fromLonLat([109.6745035, -6.895942]),
					zoom: 16
				})
			});

			// Mask input
			$('#nop_cari').mask('00.00.000.000.000-0000.0');
			$('#nib_cari').mask('00000000.00000');

			// AJAX form submit
			$('#form-cari-data').on('submit', function (e) {
				e.preventDefault();

				const nop = $('#nop_cari').val().trim();
				const nib = $('#nib_cari').val().trim();

				if (!nop && !nib) {
					alert('Silakan masukkan NOP atau NIB.');
					return;
				}

				$.ajax({
					url: '{{ route("beranda.cari-data-dan-peta") }}',
					method: 'POST',
					data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						nop: nop,
						nib: nib
					},
					dataType: 'json',
					success: function (response) {
						if (response.success && response.geometry) {
							$('#sidebarInformationDetail').html('')
							$('#sidebarInformation').show()
							$('#sidebarInformationDetail').html(response.html)
							tampilkanBidangDariGeometry(response.geometry, response.properties || {});
							$('[data-fancybox]').fancybox({ buttons: ['zoom', 'close'], loop: true });
						} else {
							alert('Data tidak ditemukan di peta.');
						}
					},
					error: function (xhr, status, error) {
						console.error('AJAX Error:', error);
						alert('Terjadi kesalahan saat mencari data.');
					}
				});
			});
		});
	</script>
</body>
</html>