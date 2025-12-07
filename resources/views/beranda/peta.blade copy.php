<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Peta Pekalongan - OpenLayers</title>

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
			top: 10px;
			left: 10px;
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

	<!-- Sidebar Overlay - Bootstrap Native -->
	<div class="sidebar">
		<div class="card shadow-sm">
			<!-- Header: Search -->
			<div class="card-header d-flex gap-2">
				<input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Cari Lokasi..." />
				<button id="searchBtn" class="btn btn-sm btn-primary">Cari</button>
			</div>

			<!-- Tab Navigation -->
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" data-bs-toggle="tab" href="#infoTab">Informasi data layer</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-bs-toggle="tab" href="#mejaTab">Meja Kerja</a>
				</li>
			</ul>

			<!-- Tab Content -->
			<div class="card-body p-3">
				<div class="tab-content">
					<div class="tab-pane fade show active" id="infoTab">
						<p class="mb-2">Daftar layer yang tersedia akan ditampilkan di sini.</p>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">Layer 1: Data Tanah</li>
							<li class="list-group-item">Layer 2: Zona Risiko</li>
							<li class="list-group-item">Layer 3: Infrastruktur</li>
						</ul>
					</div>
					<div class="tab-pane fade" id="mejaTab">
						<p>Proyek atau data kerja Anda akan muncul di sini.</p>
						<div class="alert alert-info mb-0">
							Belum ada proyek aktif.
						</div>
					</div>
				</div>
			</div>

			<!-- Footer: Koordinat -->
			<div class="coord-footer">
				<i class="bi bi-geo-alt"></i> Koordinat: <span id="coords">-</span>
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
			const googleSatelliteLayer = new ol.layer.Tile({
				source: new ol.source.XYZ({
					url: 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
					attributions: 'Map data ©2025 Google',
					maxZoom: 19,
				}),
			});

			// Layer WMS dengan style
			const wmsLayer = new ol.layer.Tile({
				source: new ol.source.TileWMS({
					url: 'http://192.168.75.15/geoserver/bpn/wms',
					params: {
						LAYERS: 'bpn:Join_gamer',
						STYLES: 'bidang_tanah', // ← STYLE DISINI!
						FORMAT: 'image/png',
						TRANSPARENT: true,
						VERSION: '1.1.1'
					},
					serverType: 'geoserver',
					projection: 'EPSG:3857'
				})
			});

			const map = new ol.Map({
				target: 'map',
				layers: [googleSatelliteLayer, wmsLayer],
				view: new ol.View({
					center: ol.proj.fromLonLat([109.6753, -6.8885]),
					zoom: 14,
				}),
			});

			$('#infoTab').prepend('<div id="feature-info" class="mt-2"></div>');

			map.on('singleclick', function(evt) {
				const viewResolution = map.getView().getResolution();
				if (viewResolution === undefined) return;

				const url = wmsLayer.getSource().getFeatureInfoUrl(
					evt.coordinate,
					viewResolution,
					'EPSG:3857', {
						INFO_FORMAT: 'text/html',
						QUERY_LAYERS: 'bpn:Join_gamer',
						FEATURE_COUNT: 50
					}
				);

				if (url) {
					const proxyUrl = `/proxy/geoserver?url=${encodeURIComponent(url)}`;
					fetch(proxyUrl)
						.then(response => response.text())
						.then(html => {
							const infoDiv = document.getElementById('feature-info');
							if (html.includes('<table class="featureInfo">')) {
								const style = html.match(/<style[^>]*>([\s\S]*?)<\/style>/i)?.[0] || '';
								const table = html.match(/<table class="featureInfo">[\s\S]*?<\/table>/i)?.[0] || '';
								infoDiv.innerHTML = `<div style="overflow:auto; max-height:250px;">${style}${table}</div>`;
							} else {
								infoDiv.innerHTML = '<div class="alert alert-warning">Tidak ada data di lokasi ini.</div>';
							}
						})
						.catch(err => {
							console.error('Error fetching WMS info:', err);
							document.getElementById('feature-info').innerHTML = '<div class="alert alert-danger">Gagal memuat data.</div>';
						});
				}
			});

			// === Pencarian Lokasi ===
			$('#searchBtn').on('click', function() {
				const query = $('#searchInput').val().trim();
				if (!query) return;
				alert("Pencarian untuk: " + query + "\n(Fungsi ini bisa dikembangkan dengan geocoding API)");
			});

			// === Tampilkan Koordinat saat hover ===
			map.on('pointermove', function(evt) {
				const coordinate = evt.coordinate;
				const lonlat = ol.proj.toLonLat(coordinate);
				$('#coords').text(lonlat[1].toFixed(6) + ', ' + lonlat[0].toFixed(6));
			});

			console.log("Peta satelit Google + WMS dengan style dimuat via OpenLayers");
		});
	</script>
</body>

</html>