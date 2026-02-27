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
			box-shadow: 0 2px 6px rgba(0,0,0,0.2);
			border-radius: 4px;
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
							<tr>
								<td style="width: 90%">PRINGREJO</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_010091',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">MEDONO</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_010092',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">SAPURO KEBULEN</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_010093',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">PODOSUGIH</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_010094',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">BENDAN KERGON</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_010095',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">TIRTO</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_010096',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
						</table>
						<table class="w-100 mt-3">
							<tr>
								<td style="width: 100%" colspan="2" class="fw-bold">PEKALONGAN TIMUR</td>
							</tr>
							<tr>
								<td style="width: 90%">NOYONTAANSARI</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020091',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">KALIBAROS</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020092',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">SETONO</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020093',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">KAUMAN</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020094',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">PONCOL</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020095',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">KLEGO</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020096',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">GAMER</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_020097',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
						</table>
						<table class="w-100 mt-3">
							<tr>
								<td style="width: 100%" colspan="2" class="fw-bold">PEKALONGAN SELATAN</td>
							</tr>
							<tr>
								<td style="width: 90%">BANYURIP</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_030091',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">SOKODUWET</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:pbt_030095',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
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
					<li class="d-flex align-items-center mb-1">
						<div style="width: 15px; height: 15px; background-color: #ff0000; margin-right: 8px;"></div>
						<span>Belum Bayar</span>
					</li>
					<li class="d-flex align-items-center mb-1">
						<div style="width: 15px; height: 15px; background-color: #39FF14; margin-right: 8px;"></div>
						<span>Wasdal</span>
					</li>
					<li class="d-flex align-items-center mb-1">
						<div style="width: 15px; height: 15px; background-color: #0000FF; margin-right: 8px;"></div>
						<span>Pemeriksaan</span>
					</li>
					<li class="d-flex align-items-center mb-1">
						<div style="width: 15px; height: 15px; background-color: #FFFF00; margin-right: 8px;"></div>
						<span>Kurang Bayar</span>
					</li>
					<li class="d-flex align-items-center mb-1">
						<div style="width: 15px; height: 15px; background-color: #FF1493; margin-right: 8px;"></div>
						<span>Proses ATR/BPN</span>
					</li>
					<li class="d-flex align-items-center">
						<div style="width: 15px; height: 15px; background-color: #00FFFF; margin-right: 8px;"></div>
						<span>Selesai ATR/BPN</span>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/ol@v9.0.0/dist/ol.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script>
		$(document).ready(function() {
			let dataBPHTB = [];
			let selectedFeature = null;
			const baseLayers = {};
			const mapLayers = [];

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
					}
				}
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
				const nop = feature.get('d_nop') || '';
				const last8 = nop.slice(-8);
				const nopPotong = last8.length === 8 ?
					`${last8.slice(0, 3)}-${last8.slice(3, 7)}.${last8.slice(7)}` :
					last8;
				const nib = feature.get('NIB') || '';
				const labelText = showLabel && nop ? (nib ? `${nopPotong}\n${nib}` : nopPotong) : '';
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
					}
				}
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
				const nop = feature.get('d_nop') || '';
				const last8 = nop.slice(-8);
				const nopPotong = last8.length === 8 ?
					`${last8.slice(0, 3)}-${last8.slice(3, 7)}.${last8.slice(7)}` :
					last8;
				const nib = feature.get('NIB') || '';
				const labelText = showLabel && nop ? (nib ? `${nopPotong}\n${nib}` : nopPotong) : '';
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
					const datakirim = {
						'LUASTERTUL': clickedFeature.get('LUASTERTUL'),
						'NIB': clickedFeature.get('NIB'),
						'Nomor_Hak': clickedFeature.get('Nomor_Hak'),
						'Pemilik_Ak': clickedFeature.get('Pemilik_Ak'),
						'Surat_Ukur': clickedFeature.get('Surat_Ukur'),
						'TIPEHAK': clickedFeature.get('TIPEHAK')
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
					loadInformasiData(clickedFeature.get('d_nop'), datakirim, matchedItem?.id);
				}
			});

			function loadInformasiData(nop, datakirim, bphtb) {
				$.ajax({
					url: '{{ route("beranda.data-informasi") }}',
					type: 'POST',
					data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						nop: nop,
						datakirim: datakirim,
						bphtb: bphtb
					},
					success: function(htmlResponse) {
						$('#informasidata').html(htmlResponse);
						$('[data-fancybox]').fancybox({
							buttons: ['zoom', 'close'],
							loop: true
						});
					},
					error: function(xhr, status, error) {
						console.error('Error:', error);
						$('#informasidata').html('<p class="text-danger">Gagal memuat informasi.</p>');
					}
				});
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

			window.toggleLayer = function(layerId, buttonElement) {
				if (!baseLayers[layerId]) {
					$.ajax({
						url: '{{ route("beranda.data-peta") }}',
						type: 'POST',
						data: {
							_token: $('meta[name="csrf-token"]').attr('content'),
							id: layerId
						},
						success: function(geojsonData) {
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
							$(buttonElement).removeClass('btn-primary').addClass('btn-danger').find('i').removeClass('fa-eye').addClass('fa-eye-slash');
						},
						error: function(xhr, status, error) {
							alert('Gagal memuat data peta.');
						}
					});
				} else {
					const entry = baseLayers[layerId];
					const isVisible = entry.layer.getVisible();
					const newVisible = !isVisible;
					entry.layer.setVisible(newVisible);
					entry.visible = newVisible;
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
			window.loadPetaData = function(layerId) {
				console.warn("Gunakan toggleLayer() langsung di HTML.");
			};
		});
	</script>
</body>

</html>