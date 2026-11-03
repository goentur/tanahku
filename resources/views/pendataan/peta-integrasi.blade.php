<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PETA PENDATAAN</title>
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
			z-index: 1000;
		}
	</style>
</head>

<body>
	<div id="map"></div>
	<div class="sidebar col-11 col-md-4 col-lg-2">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);max-height: calc(100vh - 10px); overflow-y: auto;">
			<div class="accordion accordion-flush" id="accordionFlushPeta">
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-peta" aria-expanded="false" aria-controls="flush-peta">
							LIHAT PETA BANDENGAN
						</button>
					</h2>
					<div id="flush-peta" class="accordion-collapse collapse" data-bs-parent="#accordionFlushPeta">
						<table class="mx-1 my-1">
							<tr>
								<td style="width: 90%">BPN</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('bpn:11050109',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
							<tr>
								<td style="width: 90%">PBB</td>
								<td class="w-1"><button class="btn btn-primary btn-sm" onclick="toggleLayer('3375040004',this)"><i class="fa fa-eye"></i></button></td>
							</tr>
						</table>
						<div class="accordion accordion-flush" id="accordionFlushPencarian">
							<div class="accordion-item">
								<h2 class="accordion-header">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-pencarian" aria-expanded="false" aria-controls="flush-pencarian">
										<i class="fa fa-search"></i> CARI?
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
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal Detail Persil -->
	<div class="modal fade" id="modalDetailPersil" tabindex="-1" aria-labelledby="modalDetailPersilLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalDetailPersilLabel">
						<i class="fa fa-info-circle me-1"></i> Informasi Detail Bidang Tanah
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" id="contentModalDetail">
					<!-- Konten AJAX akan dimuat di sini -->
				</div>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/ol@v9.0.0/dist/ol.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.9.0/proj4.js"></script>
	<script>
		$(document).ready(function() {
			let datSudahVerifikasi = {};
			let selectedFeature = null;
			const baseLayers = {};
			let dataPersilTerpilih = null;
			const mapLayers = [];
			let dataBPHTB = {};

			$('#modal_nop').mask('00.00.000.000.000-0000.0');

			proj4.defs("EPSG:32749", "+proj=utm +zone=49 +south +datum=WGS84 +units=m +no_defs");
			ol.proj.proj4.register(proj4);
			// Helper function untuk menentukan labelText agar tidak mengulang kode di 3 fungsi style
			function getFeatureLabelText(feature, showLabel) {
				if (!showLabel) return null;

				const layerId = feature.get('layerId') || '';
				const isBpn = String(layerId).startsWith('bpn:');

				if (isBpn) {
					// Untuk Layer BPN: gunakan NIB
					const nib = feature.get('NIB');
					return nib ? String(nib) : null;
				} else {
					// Untuk Layer PBB: gunakan NOP (8 digit terakhir)
					const nop = feature.get('d_nop');
					if (!nop) return null;
					
					const nopStr = String(nop);
					const last8 = nopStr.slice(-8);
					return last8.length === 8 
						? `${last8.slice(0, 3)}-${last8.slice(3, 7)}.${last8.slice(7)}` 
						: last8;
				}
			}

			function getDefaultStyle(feature, resolution) {
				const areaM2 = feature.get('_areaM2') || 0;
				const pixelPerMeter = 1 / resolution;
				const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
				const MIN_AREA_PX2 = 10000;
				const showLabel = areaPx2 >= MIN_AREA_PX2;

				const labelText = getFeatureLabelText(feature, showLabel);

				return new ol.style.Style({
					stroke: new ol.style.Stroke({
						color: 'white',
						width: 3
					}),
					fill: new ol.style.Fill({
						color: 'rgba(255, 255, 255, 0.1)'
					}),
					text: labelText ? new ol.style.Text({
						text: labelText,
						font: 'bold 13px Arial, sans-serif',
						fill: new ol.style.Fill({ color: '#FFFFFF' }),
						stroke: new ol.style.Stroke({ color: '#000000', width: 2 }),
						overflow: true,
						textAlign: 'center',
						textBaseline: 'middle',
						maxAngle: 0,
						offsetY: -10
					}) : undefined
				});
			}

			function getSelectedStyle(feature, resolution) {
				let strokeColor = '#FFD700';
				let fillColor = 'rgba(255, 215, 0, 0.2)';

				// Ambil cache luas jika ada, jika belum ada hitung dari geometri
				let areaM2 = feature.get('_areaM2');
				if (areaM2 === undefined) {
					const geometry = feature.getGeometry();
					areaM2 = 0;
					if (geometry) {
						if (geometry.getType() === 'Polygon') {
							areaM2 = ol.sphere.getArea(geometry);
						} else if (geometry.getType() === 'MultiPolygon') {
							for (const poly of geometry.getPolygons()) {
								areaM2 += ol.sphere.getArea(poly);
							}
						}
					}
				}

				const pixelPerMeter = 1 / resolution;
				const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
				const MIN_AREA_PX2 = 10000;
				const showLabel = areaPx2 >= MIN_AREA_PX2;

				const labelText = getFeatureLabelText(feature, showLabel);

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
						fill: new ol.style.Fill({ color: '#FFFFFF' }),
						stroke: new ol.style.Stroke({ color: '#000000', width: 2 }),
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
				let strokeColor = '#FFD700';
				let fillColor = 'rgba(255, 215, 0, 0.5)';

				let areaM2 = feature.get('_areaM2');
				if (areaM2 === undefined) {
					const geometry = feature.getGeometry();
					areaM2 = 0;
					if (geometry) {
						if (geometry.getType() === 'Polygon') {
							areaM2 = ol.sphere.getArea(geometry);
						} else if (geometry.getType() === 'MultiPolygon') {
							for (const poly of geometry.getPolygons()) {
								areaM2 += ol.sphere.getArea(poly);
							}
						}
					}
				}

				const pixelPerMeter = 1 / resolution;
				const areaPx2 = areaM2 * (pixelPerMeter * pixelPerMeter);
				const MIN_AREA_PX2 = 10000;
				const showLabel = areaPx2 >= MIN_AREA_PX2;

				const labelText = getFeatureLabelText(feature, showLabel);

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
						fill: new ol.style.Fill({ color: '#FFFFFF' }),
						stroke: new ol.style.Stroke({ color: '#000000', width: 3 }),
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
					center: ol.proj.fromLonLat([109.67630, -6.89072]),
					zoom: 16
				})
			});

			map.on('click', function(evt) {
				let clickedFeature = null;
				
				// Ambil feature teratas yang diklik
				map.forEachFeatureAtPixel(evt.pixel, function(feature) {
					clickedFeature = feature;
					return true; // Stop di feature pertama/teratas
				});

				// Reset highlight feature sebelumnya
				if (selectedFeature) {
					selectedFeature.setStyle(null);
					selectedFeature = null;
				}

				if (clickedFeature) {
					selectedFeature = clickedFeature;
					
					// Beri style highlight (kuning/gold)
					selectedFeature.setStyle(function(feature, resolution) {
						return getSelectedStyle(feature, resolution);
					});

					// Cek layerId yang tersimpan di feature
					const layerId = clickedFeature.get('layerId') || '';
					const isBpn = String(layerId).startsWith('bpn:');

					// Susun parameter pengiriman berdasarkan tipe layer
					let datakirim = {
						tipe_layer: isBpn ? 'bpn' : 'pbb',
						layer_id: layerId
					};

					if (isBpn) {
						datakirim.nib = clickedFeature.get('NIB') || '';
						datakirim.nib_el = clickedFeature.get('NIB_EL') || '';
						datakirim.luas = clickedFeature.get('LUASTERTUL') || '';
						datakirim.tipe_hak = clickedFeature.get('TIPEHAK') || '';
						datakirim.nomor_hak = clickedFeature.get('Nomor_Hak') || '';
						datakirim.pemilik_pertama = clickedFeature.get('Pemilik_Pe') || '';
						datakirim.pemilik_akhir = clickedFeature.get('Pemilik_Ak') || '';
						datakirim.nop = clickedFeature.get('d_nop') || '';
					} else {
						// Parameter khusus PBB (hanya butuh d_nop)
						datakirim.nop = clickedFeature.get('d_nop') || '';
					}

					dataPersilTerpilih = datakirim;

					// Tampilkan modal detail & set loading spinner
					$('#modalDetailPersil').modal('show');
					$('#contentModalDetail').html(`
						<div class="text-center py-4">
							<div class="spinner-border text-primary" role="status">
								<span class="visually-hidden">Loading...</span>
							</div>
							<p class="mt-2 mb-0">Memuat data detail ${isBpn ? 'BPN' : 'PBB'}...</p>
						</div>
					`);

					// Panggil fungsi AJAX
					loadInformasiData(datakirim);
				}
			});
			function loadInformasiData(datakirim) {
				$.ajax({
					url: '{{ route("pendataan.detail-persil") }}', // Route Controller kamu
					type: 'POST',
					data: {
						_token: $('meta[name="csrf-token"]').attr('content'),
						...datakirim
					},
					dataType: 'html', // Menandakan bahwa ekspektasi response dari server berupa HTML
					success: function(response) {
						// Langsung rendernya ke div kontainer modal
						$('#contentModalDetail').html(response);
					},
					error: function(xhr, status, error) {
						console.error("Gagal memuat detail persil:", error);
						
						// Menggunakan Swal.fire untuk pesan error
						Swal.fire({
							icon: 'error',
							title: 'Gagal Memuat Data',
							text: 'Terjadi kesalahan saat mengambil informasi detail persil.'
						});

						$('#contentModalDetail').html(`
							<div class="alert alert-danger mb-0 text-center">
								<i class="fa fa-exclamation-triangle me-1"></i> Gagal memuat data detail persil.
							</div>
						`);
					}
				});
			}

			window.toggleLayer = async function(layerId, buttonElement) {
				if (!baseLayers[layerId]) {
					const $btn = $(buttonElement);
					$btn.prop('disabled', true);

					try {
						const geojsonData = await $.ajax({
							url: '{{ route("verifikasi-peta-integrasi.data-peta") }}',
							type: 'POST',
							data: {
								_token: $('meta[name="csrf-token"]').attr('content'),
								id: layerId
							}
						});

						const source = new ol.source.Vector();
						const format = new ol.format.GeoJSON();

						// 1. Deteksi CRS
						const crsName = geojsonData?.crs?.properties?.name || '';
						let isUtm = crsName.includes('32749') || !layerId.startsWith('bpn:');
						
						// 2. Baca Features sesuai Proyeksi
						const features = format.readFeatures(geojsonData, {
							dataProjection: isUtm ? 'EPSG:32749' : 'EPSG:4326',
							featureProjection: 'EPSG:3857'
						});

						// 3. Pre-calculate area sekali saja (cegah lag/blink saat zoom)
						features.forEach(f => {
							f.set('layerId', layerId);

							const geom = f.getGeometry();
							let areaM2 = 0;
							if (geom) {
								if (geom.getType() === 'Polygon') {
									areaM2 = ol.sphere.getArea(geom);
								} else if (geom.getType() === 'MultiPolygon') {
									for (const poly of geom.getPolygons()) {
										areaM2 += ol.sphere.getArea(poly);
									}
								}
							}
							f.set('_areaM2', areaM2);
						});

						source.addFeatures(features);

						// 4. Tambahkan Vector Layer dengan renderMode 'image' (agar basemap tidak hilang saat zoom)
						const layer = new ol.layer.Vector({
							source: source,
							renderMode: 'image', // Mencegah blink basemap saat zoom in/out
							style: function(feature, resolution) {
								return getDefaultStyle(feature, resolution);
							}
						});

						layer.set('layerId', layerId);
						map.addLayer(layer);

						baseLayers[layerId] = {
							layer,
							button: buttonElement,
							visible: true
						};

						// 5. Fit View secara aman (tanpa memicu layar putih)
						const extent = source.getExtent();
						if (extent && isFinite(extent[0]) && isFinite(extent[1])) {
							map.getView().fit(extent, {
								padding: [50, 50, 50, 50],
								duration: 500,
								maxZoom: 18
							});
						}

						$btn.removeClass('btn-primary').addClass('btn-danger')
							.find('i').removeClass('fa-eye').addClass('fa-eye-slash');

					} catch (error) {
						console.error("Gagal memproses layer:", error);
						Swal.fire({
							icon: 'error',
							title: 'Gagal Memuat Layer',
							text: 'Terjadi kesalahan sistem saat memuat data dan Peta.'
						});
					} finally {
						$btn.prop('disabled', false);
					}

				} else {
					// Toggle On/Off
					const entry = baseLayers[layerId];
					const newVisible = !entry.layer.getVisible();

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

			function executeFieldSearch() {
				const field = $('#searchField').val();
				const keyword = $('#searchKeyword').val().trim();
				const infoEl = $('#searchInfo');
				const navEl = $('#searchNav');

				// === VALIDASI 1: Input field & keyword ===
				if (!field) {
					Swal.fire({
						icon: 'error',
						title: 'Pencarian Gagal',
						text: 'Silakan pilih jenis pencarian (NOP / NIB / Nomor Hak).'
					});
					return;
				}
				if (!keyword) {
					Swal.fire({
						icon: 'error',
						title: 'Pencarian Gagal',
						text: 'Silakan masukkan kata kunci pencarian.'
					});
					return;
				}

				// === RESET STATE SEBELUMNYA ===
				clearSearchHighlights();
				searchResults = [];
				currentResultIndex = 0;
				infoEl.addClass('d-none').text('').removeClass('text-danger');
				navEl.addClass('d-none');

				// === MAPPING BILA FIELD NIB / NOMOR HAK (HANYA BPN) ATAU NOP (BPN & PBB) ===
				const activeTargetLayers = [];

				for (const [layerId, entry] of Object.entries(baseLayers)) {
					if (entry.layer.getVisible()) {
						const isBpn = layerId.startsWith('bpn:');
						
						if (field === 'NIB' || field === 'Nomor_Hak') {
							// NIB / Nomor Hak HANYA dicari di layer BPN yang aktif
							if (isBpn) {
								activeTargetLayers.push({ id: layerId, entry: entry });
							}
						} else if (field === 'd_nop') {
							// NOP bisa dicari di layer BPN maupun PBB yang aktif
							activeTargetLayers.push({ id: layerId, entry: entry });
						}
					}
				}

				// === VALIDASI 2: Jika layer target tidak ada yang aktif ===
				if (activeTargetLayers.length === 0) {
					if (field === 'NIB' || field === 'Nomor_Hak') {
						Swal.fire({
							icon: 'warning',
							title: 'Layer Tidak Aktif',
							text: 'Layer BPN belum diaktifkan. Silakan aktifkan layer BPN terlebih dahulu.'
						});
					} else {
						Swal.fire({
							icon: 'warning',
							title: 'Layer Tidak Aktif',
							text: 'Tidak ada layer aktif yang cocok untuk pencarian NOP. Silakan aktifkan layer BPN atau PBB.'
						});
					}
					return;
				}

				// === PROSES PENCARIAN DI LAYER AKTIF ===
				activeTargetLayers.forEach(layerObj => {
					const source = layerObj.entry.layer.getSource();
					if (!source) return;

					const features = source.getFeatures();

					features.forEach(feature => {
						const props = feature.getProperties();
						const fieldValue = props[field];

						if (!fieldValue) return;

						const normalizedField = normalizeSearchString(fieldValue);
						const normalizedKeyword = normalizeSearchString(keyword);

						if (normalizedField.includes(normalizedKeyword)) {
							searchResults.push({
								feature: feature,
								layerId: layerObj.id
							});
						}
					});
				});

				// === TAMPILKAN HASIL ===
				if (searchResults.length > 0) {
					infoEl.removeClass('d-none').html(`
						✓ Ditemukan <strong>${searchResults.length}</strong> hasil 
						di <strong>${activeTargetLayers.length}</strong> layer aktif
					`);

					if (searchResults.length > 1) {
						navEl.removeClass('d-none');
					}

					navigateToResult(0);

				} else {
					infoEl.removeClass('d-none').addClass('text-danger').html(`
						✗ Tidak ada hasil ditemukan di layer yang aktif
					`);

					setTimeout(() => {
						Swal.fire({
							icon: 'warning',
							title: 'Data Tidak Ditemukan',
							text: `Tidak ditemukan data dengan ${getFieldLabel(field)}: "${keyword}" pada layer yang sedang aktif.`
						});
						infoEl.addClass('d-none').removeClass('text-danger');
					}, 100);
				}
			}

			// Helper: konversi field value ke label yang ramah user
			function getFieldLabel(field) {
				const labels = {
					'd_nop': 'NOP',
					'NIB': 'NIB',
					'Nomor_Hak': 'Nomor Hak'
				};
				return labels[field] || field;
			}

			// Navigasi ke hasil pencarian + zoom + gold border
			function navigateToResult(index) {
				if (searchResults.length === 0) return;

				if (index < 0) index = searchResults.length - 1;
				if (index >= searchResults.length) index = 0;

				currentResultIndex = index;
				const result = searchResults[currentResultIndex];
				const feature = result.feature;
				const layerId = result.layerId;

				if (searchHighlightedFeature && searchHighlightedFeature !== feature) {
					searchHighlightedFeature.setStyle(null);
				}

				searchHighlightedFeature = feature;
				feature.setStyle(function(feature, resolution) {
					return getSearchHighlightStyle(feature, resolution);
				});

				const geom = feature.getGeometry();
				const extent = geom.getExtent();

				map.getView().fit(extent, {
					padding: [100, 100, 100, 100],
					duration: 1200,
					maxZoom: 19
				});

				updateSearchInfo(layerId);
				showSearchTooltip(feature, currentResultIndex + 1, searchResults.length);
			}

			function updateSearchInfo(layerId) {
				const field = $('#searchField').val();
				const fieldName = getFieldLabel(field);
				const result = searchResults[currentResultIndex];
				const value = result.feature.get(field) || '-';

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

			function normalizeSearchString(str) {
				if (!str) return '';
				return String(str)
					.toLowerCase()
					.replace(/[\s.\-,/\\]+/g, '');
			}

			function clearSearchHighlights() {
				if (searchHighlightedFeature) {
					searchHighlightedFeature.setStyle(null);
					searchHighlightedFeature = null;
				}

				const tooltip = document.querySelector('.search-tooltip');
				if (tooltip && tooltip.parentNode) {
					tooltip.parentNode.removeChild(tooltip);
				}
			}

			$('#nextBtn').on('click', function() {
				navigateToResult(currentResultIndex + 1);
			});

			$('#prevBtn').on('click', function() {
				navigateToResult(currentResultIndex - 1);
			});

			$('#searchField, #searchKeyword').on('change input', function() {
				clearSearchHighlights();
				$('#searchInfo').addClass('d-none');
				$('#searchNav').addClass('d-none');
				searchResults = [];
			});
		});
	</script>
</body>

</html>