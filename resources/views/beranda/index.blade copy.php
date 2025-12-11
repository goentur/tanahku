<!DOCTYPE html>
<html lang="en">

<head>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">

	<title>Chain App Dev - App Landing Page HTML5 Template</title>

	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<!-- Bootstrap core CSS -->
	<link href="{{ asset('front-end/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

	<!-- Additional CSS Files -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ asset('front-end/assets/css/templatemo-chain-app-dev.css')}}">
	<link rel="stylesheet" href="{{ asset('front-end/assets/css/animated.css')}}">
	<link rel="stylesheet" href="{{ asset('front-end/assets/css/owl.css')}}">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v9.0.0/ol.css" />
	<style>
		#map {
			padding-top: 100px;
			width: 100%;
			height: 100vh;
			background-color: #f0f0f0;
		}
		
		.sidebar {
			position: absolute;
			top: 9px;
			padding-top: 100px;
			left: 50%;
			transform: translateX(-50%);
			width: auto;
			z-index: 1000;
		}
	</style>
</head>

<body>

	<!-- ***** Preloader Start ***** -->
	<div id="js-preloader" class="js-preloader">
		<div class="preloader-inner">
			<span class="dot"></span>
			<div class="dots">
				<span></span>
				<span></span>
				<span></span>
			</div>
		</div>
	</div>
	<!-- ***** Preloader End ***** -->

	<!-- ***** Header Area Start ***** -->
	<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<nav class="main-nav">
						<!-- ***** Logo Start ***** -->
						<a href="index.html" class="logo" style="width: 50px">
							<img src="{{ asset('assets/img/logo.png') }}" alt="Chain App Dev">
						</a>
						<!-- ***** Logo End ***** -->
						<!-- ***** Menu Start ***** -->
						<ul class="nav">
							<li class="scroll-to-section"><a href="#beranda" class="active">BERANDA</a></li>
							<li class="scroll-to-section"><a href="#layanan">LAYANAN</a></li>
							<li class="scroll-to-section"><a href="#tentang">TENTANG</a></li>
							<li class="scroll-to-section"><a href="{{ route('beranda.peta') }}">PETA</a></li>
							<li>
								<div class="gradient-button"><a href="{{ route('login') }}"><i class="fa fa-sign-in-alt"></i> Login</a></div>
							</li>
						</ul>
						<a class='menu-trigger'>
							<span>Menu</span>
						</a>
						<!-- ***** Menu End ***** -->
					</nav>
				</div>
			</div>
		</div>
	</header>
	<!-- ***** Header Area End ***** -->

	<div id="map"></div>

	<div class="sidebar">
		<div class="card" style="background-color: rgba(255, 255, 255, 0.7);">
			<div class="card-body">
				<form class="row g-3 align-items-center" method="POST" action="javascript:void(0)">
					<div class="col-auto" style="vertical-align: middle">
						<b>NOP</b>
					</div>
					<div class="col-auto">
						<input type="text" placeholder="Masukan NOP anda" class="form-control">
					</div>
					<div class="col-auto" style="vertical-align: middle">
						<b>NIB</b>
					</div>
					<div class="col-auto">
						<input type="text" placeholder="Masukan NIB anda" class="form-control">
					</div>
					<div class="col-auto">
						<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> CARI</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="layanan" class="services section">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 offset-lg-2">
					<div class="section-heading  wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.5s">
						<h4>Sederhanakan Proses <em>PBB & ATR/BPN</em> dengan Satu Platform Digital</h4>
						<img src="{{ asset('front-end/assets/images/heading-line-dec.png') }}" alt="">
						<p>Jika Anda lelah dengan duplikasi data, ketidakakuratan spasial, waktu pelayanan yang lama, dan potensi PAD yang hilang, GeoTaxConnect adalah solusi yang Anda butuhkan. Platform kami terintegrasi dengan sistem PBB, BPHTB, dan ATR/BPN untuk memberikan pelayanan yang cepat, akurat, dan transparan.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-4">
					<div class="service-item first-service">
						<div class="icon"></div>
						<h4>Sinkronisasi Data Real-Time</h4>
						<p>Integrasi mulus antara catatan pajak BPKAD dan data kadaster BPN tanpa delay. Satu sumber data yang akurat untuk semua informasi lahan dan pajak.</p>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="service-item second-service">
						<div class="icon"></div>
						<h4>Layanan E-SPPT Tanpa Klik</h4>
						<p>Pengiriman SPPT otomatis melalui WhatsApp atau Email setelah verifikasi, menghilangkan permintaan manual dan mengurangi waktu pemrosesan hingga 90%.</p>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="service-item third-service">
						<div class="icon"></div>
						<h4>Pemeriksaan Integritas Spasial</h4>
						<p>Validasi berbasis AI terhadap batas-batas bidang tanah dari berbagai sumber data geografis, memastikan akurasi dan mencegah sengketa.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="tentang" class="about-us section">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 align-self-center">
					<div class="section-heading">
						<h4>About <em>What We Do</em> &amp; Who We Are</h4>
						<img src="{{ asset('front-end/assets/images/heading-line-dec.png') }}" alt="">
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eismod tempor incididunt ut labore et dolore magna.</p>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Maintance Problems</a></h4>
								<p>Lorem Ipsum Text</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">24/7 Support &amp; Help</a></h4>
								<p>Lorem Ipsum Text</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Fixing Issues About</a></h4>
								<p>Lorem Ipsum Text</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Co. Development</a></h4>
								<p>Lorem Ipsum Text</p>
							</div>
						</div>
						<div class="col-lg-12">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eismod tempor idunte ut labore et dolore adipiscing magna.</p>
							<div class="gradient-button">
								<a href="javascript:void(0)">Start 14-Day Free Trial</a>
							</div>
							<span>*No Credit Card Required</span>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="right-image">
						<img src="{{ asset('front-end/assets/images/about-right-dec.png') }}" alt="">
					</div>
				</div>
			</div>
		</div>
	</div>

	<footer id="newsletter">
		<div class="container">
			<div class="mt-5">&nbsp;</div>
			<div class="row mt-5">
				<div class="col-lg-3">
					<div class="footer-widget">
						<h4>Kantor Pajak Daerah</h4>
						<p>Jl. Sriwijaya NO 44 Kota Pekalongan</p>
						<p><a href="javascript:void(0)">(0285) 429451</a></p>
						<p><a href="javascript:void(0)">bpkad.kotapkl@gmail.com</a></p>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="footer-widget">
						<h4>Kantor Pertanahan Daerah</h4>
						<p>Jl. Majapahit No.2 Kota Pekalongan</p>
						<p><a href="javascript:void(0)">0815-6910-009</a></p>
						<p><a href="javascript:void(0)">kot-pekalongan@atrbpn.go.id</a></p>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="footer-widget">
						<h4>Layanan Pajak Daerah</h4>
						<ul>
							<li><a href="javascript:void(0)">BPHTB Online</a></li>
							<li><a href="javascript:void(0)">ESPT</a></li>
							<li><a href="javascript:void(0)">PBB Online</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="footer-widget">
						<h4>Layanan Pertanahan Daerah</h4>
						<ul>
							<li><a href="javascript:void(0)">Bhumi ATR/BPN</a></li>
						</ul>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="copyright-text">
						<p>Copyright &copy; 2025 BPKAD & ATR/BPN Kota Pekalongan. All Rights Reserved.
						</p>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!-- Scripts -->
	<script src="{{ asset('front-end/vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('front-end/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('front-end/assets/js/owl-carousel.js') }}"></script>
	<script src="{{ asset('front-end/assets/js/animation.js') }}"></script>
	<script src="{{ asset('front-end/assets/js/imagesloaded.js') }}"></script>
	<script src="{{ asset('front-end/assets/js/popup.js') }}"></script>
	<script src="{{ asset('front-end/assets/js/custom.js') }}"></script>
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
					center: ol.proj.fromLonLat([109.6987027, -6.8871928]),
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