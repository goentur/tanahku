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

	<!-- Bootstrap core CSS -->
	<link href="{{ asset('front-end/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

	<!--

TemplateMo 570 Chain App Dev

https://templatemo.com/tm-570-chain-app-dev

-->

	<!-- Additional CSS Files -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<link rel="stylesheet" href="{{ asset('front-end/assets/css/templatemo-chain-app-dev.css')}}">
	<link rel="stylesheet" href="{{ asset('front-end/assets/css/animated.css')}}">
	<link rel="stylesheet" href="{{ asset('front-end/assets/css/owl.css')}}">

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

	<div class="main-banner wow fadeIn" id="beranda" data-wow-duration="1s" data-wow-delay="0.5s">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="row">
						<div class="col-lg-6 align-self-center">
							<div class="left-content show-up header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
								<div class="row">
									<div class="col-lg-12">
										<h2>Get The Latest App From App Stores</h2>
										<p>Chain App Dev is an app landing page HTML5 template based on Bootstrap v5.1.3 CSS layout provided by TemplateMo, a great website to download free CSS templates.</p>
									</div>
									<div class="col-lg-12">
										<div class="white-button first-button scroll-to-section">
											<a href="#contact">Free Quote <i class="fab fa-apple"></i></a>
										</div>
										<div class="white-button scroll-to-section">
											<a href="#contact">Free Quote <i class="fab fa-google-play"></i></a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
								<img src="{{ asset('front-end/assets/images/coba-1.svg') }}" alt="">
							</div>
						</div>
					</div>
				</div>
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
						<p>Jika Anda lelah dengan duplikasi data, ketidakakuratan spasial, waktu pelayanan yang lama, dan potensi PAD yang hilang — PSL-TS adalah solusi yang Anda butuhkan. Platform ini terintegrasi langsung dengan sistem BPKAD dan BPN untuk memberikan layanan yang cepat, akurat, dan transparan.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="service-item first-service">
						<div class="icon"></div>
						<h4>Sinkronisasi Data Real-Time</h4>
						<p>Integrasi mulus antara catatan pajak BPKAD dan data kadaster BPN tanpa delay. Satu sumber data yang akurat untuk semua informasi lahan dan pajak.</p>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="service-item fourth-service">
						<div class="icon"></div>
						<h4>Layanan E-SPPT Tanpa Klik</h4>
						<p>Pengiriman SPPT otomatis melalui WhatsApp atau Email setelah verifikasi, menghilangkan permintaan manual dan mengurangi waktu pemrosesan hingga 90%.</p>
					</div>
				</div>
				<div class="col-lg-6 mt-3">
					<div class="service-item third-service">
						<div class="icon"></div>
						<h4>Pemeriksaan Integritas Spasial</h4>
						<p>Validasi berbasis AI terhadap batas-batas bidang tanah dari berbagai sumber data geografis, memastikan akurasi dan mencegah sengketa.</p>
					</div>
				</div>
				<div class="col-lg-6 mt-3">
					<div class="service-item second-service">
						<div class="icon"></div>
						<h4>Optimalkan Potensi PAD Daerah</h4>
						<p>Sistem deteksi objek pajak yang tidak sesuai dengan data legal. Data real-time menunjukkan pertumbuhan PAD, tingkat verifikasi NIB, dan jumlah SPPT terbit — membantu pemerintah daerah memaksimalkan pendapatan asli daerah.</p>
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
						<h4>Tentang Apa yang Kami <em>Lakukan & Siapa</em> Kami</h4>
						<img src="{{ asset('front-end/assets/images/heading-line-dec.png') }}" alt="">
						<p>PSL-TS adalah sistem integrasi digital antara BPKAD dan BPN Kota Pekalongan, dirancang untuk menciptakan satu sumber data terpercaya bagi perpajakan tanah dan pendaftaran tanah. Dengan teknologi mutakhir dan sentuhan budaya lokal, kami mempercepat, menyederhanakan, dan membuat proses lebih transparan.</p>
					</div>
					<div class="row">
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Integrasi Data Real-Time</a></h4>
								<p>Sinkronisasi instan antara data pajak BPKAD dan data kadaster BPN, tanpa duplikasi dan delay.</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Verifikasi Spasial Otomatis</a></h4>
								<p>Validasi batas bidang tanah secara AI, mencegah sengketa dan memastikan akurasi data geografis.</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Pelayanan E-SPPT Tanpa Klik</a></h4>
								<p>SPPT otomatis dikirim via WhatsApp/Email setelah verifikasi, hemat waktu hingga 90%.</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="box-item">
								<h4><a href="javascript:void(0)">Transparansi PAD & Akuntabilitas</a></h4>
								<p>Dashboard real-time menunjukkan pertumbuhan PAD, jumlah SPPT terbit, dan tingkat verifikasi NIB.</p>
							</div>
						</div>
						<div class="col-lg-12">
							<p>Dengan PSL-TS, kami tidak hanya membangun sistem, tapi juga membangun kepercayaan — antara warga, pemerintah, dan data. Setiap titik di peta, setiap angka di laporan, adalah komitmen kami terhadap efisiensi, keadilan, dan kemajuan Kota Pekalongan.</p>
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
</body>

</html>