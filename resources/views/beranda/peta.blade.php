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
							<li class="list-group-item">
								<div class="row">
									<div class="col-6" style="background-color: #FF4500; height: 20px;"></div>
									<div class="col-6"> BELUM BAYAR</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-6" style="background-color: #39FF14; height: 20px;"></div>
									<div class="col-6"> WASDAL</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-6" style="background-color: #1E90FF; height: 20px;"></div>
									<div class="col-6"> PEMERIKSAAN</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-6" style="background-color: #FFFF00; height: 20px;"></div>
									<div class="col-6"> KURANG BAYAR</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-6" style="background-color: #FF1493; height: 20px;"></div>
									<div class="col-6"> PROSES ATR/BPN</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-6" style="background-color: #00FFFF; height: 20px;"></div>
									<div class="col-6"> SELESAI ATR/BPN</div>
								</div>
							</li>
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
		$(document).ready(function () {
    // === Variabel global untuk data BPHTB ===
    let dataBPHTB = [];

    // === Vector Layer ===
    const vectorSource = new ol.source.Vector();
    const vectorLayer = new ol.layer.Vector({
					source: vectorSource,
					style: function (feature) {
							const d_nop = feature.get('d_nop');
							const matchedItem = dataBPHTB.find(item => item.noptanpaFormat === d_nop);

							// Default style (jika tidak ditemukan atau status tidak dikenali)
							let strokeColor = 'white';
							let fillColor = 'rgba(255, 255, 255, 0.1)';

							if (matchedItem && matchedItem.status) {
									const status = String(matchedItem.status); // pastikan berupa string

									// Mapping status ke warna
									
									switch (status) {
    case '4':
        strokeColor = '#FF4500'; // Orange Red (lebih cerah dari oranye biasa)
        fillColor = 'rgba(255, 69, 0, 0.4)';
        break;
    case '5':
        strokeColor = '#39FF14'; // Neon Green (hijau neon pure)
        fillColor = 'rgba(57, 255, 20, 0.4)';
        break;
    case '6':
        strokeColor = '#1E90FF'; // Dodger Blue (biru cerah & bersih)
        fillColor = 'rgba(30, 144, 255, 0.4)';
        break;
    case '7':
        strokeColor = '#FFFF00'; // Pure Yellow (kuning terang penuh)
        fillColor = 'rgba(255, 255, 0, 0.4)';
        break;
    case '11':
        strokeColor = '#FF1493'; // Deep Pink (pink neon kuat)
        fillColor = 'rgba(255, 20, 147, 0.4)';
        break;
    case '12':
        strokeColor = '#00FFFF'; // Cyan (cyan penuh / aqua)
        fillColor = 'rgba(0, 255, 255, 0.4)';
        break;
    default:
        // Warna fallback: abu-abu terang jika tidak cocok
        strokeColor = '#CCCCCC';
        fillColor = 'rgba(204, 204, 204, 0.2)';
        break;
}
							}

							return new ol.style.Style({
									stroke: new ol.style.Stroke({
											color: strokeColor,
											width: 1
									}),
									fill: new ol.style.Fill({
											color: fillColor
									})
							});
					}
			});

    // === Google Satellite Layer ===
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

    // === Muat data peta (hanya sekali) ===
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
            console.log("Data peta dimuat.");
        } catch (err) {
            console.error(err);
            alert('Gagal memuat data peta.');
        }
    }

    // === Muat data BPHTB (tanpa reload peta) ===
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
            
            // === PENTING: Beri tahu OpenLayers untuk render ulang style ===
            vectorLayer.changed();

            console.log("Data BPHTB dimuat, style diperbarui.");
        } catch (err) {
            console.error(err);
            alert('Gagal memuat data BPHTB.');
        }
    }

    // === Jalankan sekali saat halaman dibuka ===
    loadPetaData();      // Muat geometri peta
    loadDataBphtb();     // Muat data BPHTB, lalu update style

    // === Fitur tambahan ===
    $('#searchBtn').on('click', function () {
        const query = $('#searchInput').val().trim();
        if (!query) return;
        alert("Pencarian untuk: " + query + "\n(Fungsi ini bisa dikembangkan dengan geocoding API)");
    });

    map.on('pointermove', function (evt) {
        const coordinate = evt.coordinate;
        const lonlat = ol.proj.toLonLat(coordinate);
        $('#coords').text(lonlat[1].toFixed(6) + ', ' + lonlat[0].toFixed(6));
    });

    console.log("Peta satelit Google + data GeoJSON dimuat via OpenLayers");
});
	</script>
</body>

</html>