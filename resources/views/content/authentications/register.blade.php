@extends('layouts/blankLayout')

@section('title', 'Daftar')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
            <!-- Register Card -->
            <div class="card px-sm-6 px-0">
                <div class="card-body">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mb-6">
                        <a href="{{ url('/') }}" class="app-brand-link gap-2">
                            <span class="app-brand-logo demo">@include('_partials.macros')</span>
                            <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-1">Petualangan dimulai di sini 🚀</h4>
                    <p class="mb-6">Buat pengelolaan aplikasi Anda jadi mudah dan menyenangkan!</p>

                    <form method="POST" action="{{ route('register') }}" class="mb-6">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="nid" class="form-label">NID</label>
                            <input type="nid" class="form-control" id="nid" name="nid" value="{{ old('nid') }}" placeholder="Masukkan nid Anda" required />
                            @error('nid')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama Anda" required autofocus />
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email Anda" required />
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password">Kata Sandi</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-6 form-password-toggle">
                            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                            </div>
                        </div>
                        <div class="my-7">
                            <div class="form-check mb-0">
                                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" required />
                                <label class="form-check-label" for="terms-conditions">
                                    Saya setuju dengan
                                    <a href="javascript:void(0);">kebijakan privasi & syarat ketentuan</a>
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-primary d-grid w-100">Daftar</button>
                    </form>

                    <p class="text-center">
                        <span>Sudah punya akun?</span>
                        <a href="{{ route('login') }}">
                            <span>Masuk saja</span>
                        </a>
                    </p>
                </div>
            </div>
            <!-- Register Card -->
        </div>
    </div>
</div>
@endsection