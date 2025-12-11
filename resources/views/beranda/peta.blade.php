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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
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
	</style>
</head>

<body>
	<div id="map"></div>
	<div class="sidebar">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);">
			<div class="card-body">
				<form class="row g-3 align-items-center" method="POST" action="javascript:void(0)">
					<div class="col-auto" style="vertical-align: middle">
						<b>NOP</b>
					</div>
					<div class="col-auto">
						<input type="text" placeholder="Masukan NOP anda" class="form-control form-control-sm">
					</div>
					<div class="col-auto" style="vertical-align: middle">
						<b>NIB</b>
					</div>
					<div class="col-auto">
						<input type="text" placeholder="Masukan NIB anda" class="form-control form-control-sm">
					</div>
					<div class="col-auto">
						<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> CARI</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- OpenLayers -->
	<script src="https://cdn.jsdelivr.net/npm/ol@v9.0.0/dist/ol.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js"></script>
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
				});
			}

			// === Fungsi Style Saat Dipilih (highlight) ===
			function getSelectedStyle(feature, resolution) {
				const d_nop = feature.get('d_nop');
				const matchedItem = dataBPHTB.find(item => item.noptanpaFormat === d_nop);

				let strokeColor = '#EFBF04';
				let fillColor = 'rgba(0, 0, 0, 0)';

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
				return new ol.style.Style({
					stroke: new ol.style.Stroke({
						color: strokeColor,
						width: 3 // lebih tebal saat dipilih
					}),
					fill: new ol.style.Fill({
						color: fillColor
					}),
				});
			}

			const vectorSource = new ol.source.Vector();
			const vectorLayer = new ol.layer.Vector({
				source: vectorSource,
				style: getDefaultStyle
			});

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
			
			loadPetaData();
		});
	</script>
</body>

</html>