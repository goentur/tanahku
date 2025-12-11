@extends('layouts/blankLayout')

@section('title', 'Lupa Kata Sandi')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Forgot Password -->
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{ route('login') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo"><img src="{{ asset('assets/img/logo.png') }}" alt="" class="img-fluid" height="50" width="50" srcset=""></span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Lupa Kata Sandi? 🔒</h4>
                    <p class="mb-6">Masukkan email Anda, dan kami akan mengirimkan petunjuk untuk mengatur ulang kata sandi Anda</p>

                    {{-- Tampilkan pesan sukses jika ada --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            Tauan sudah dikirm ke email Anda, silahkan cek di kontak masuk atau spam
                        </div>
                    @endif

                    <form class="mb-6" action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="text"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email Anda"
                                autofocus
                                required
                            />
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button class="btn btn-primary d-grid w-100">Kirim Tautan Reset</button>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="d-flex justify-content-center">
                            <i class="icon-base bx bx-chevron-left me-1"></i>
                            Kembali ke halaman login
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
</div>
@endsection