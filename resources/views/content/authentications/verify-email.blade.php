@extends('layouts/blankLayout')

@section('title', 'Verifikasi Email')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Verifikasi Email -->
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{ route('login') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Verifikasi Email Anda 🔒</h4>
                    <p class="mb-6">
                        Sebelum melanjutkan, silakan cek kotak masuk email Anda dan klik tautan verifikasi yang telah kami kirim.
                        Jika tidak menerima email, Anda bisa mengirim ulang tautan verifikasi di bawah ini.
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            Tautan verifikasi baru telah dikirim ke alamat email yang Anda gunakan saat pendaftaran.
                        </div>
                    @endif

                    <form class="mb-6" action="{{ route('verification.send') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary d-grid w-100" type="submit">
                            Kirim Ulang Tautan Verifikasi
                        </button>
                    </form>

                    <div class="text-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-danger d-grid w-100" type="submit">
                                Kembali ke Halaman Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /Verifikasi Email -->
        </div>
    </div>
</div>
@endsection