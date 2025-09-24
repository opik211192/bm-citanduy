@extends('layouts.login-layout')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-10">
            <div class="p-4 p-md-5 bg-white rounded-4 shadow-lg">
                <div class="row align-items-center">

                    {{-- Kolom Kiri: Logo --}}
                    <div class="col-md-5 text-center mb-4 mb-md-0">
                        <img src="{{ asset('img/citanduy.png') }}" alt="Logo Citanduy" class="img-fluid"
                            style="max-width: 280px;">
                    </div>

                    {{-- Kolom Kanan: Form Login --}}
                    <div class="col-md-7 border-start ps-md-5">
                        <h3 class="fw-bold text-primary mb-4 text-center text-md-start">
                            <i class="fa-solid fa-lock"></i> Login
                        </h3>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            {{-- Username --}}
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" required autofocus>
                                @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Remember Me --}}
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{
                                    old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>

                            {{-- Tombol --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ url('/') }}" class="btn btn-outline-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary px-4">Login</button>
                            </div>
                        </form>

                        {{-- Forgot Password --}}
                        @if (Route::has('password.request'))
                        <div class="mt-3 text-center text-md-start">
                            <a href="{{ route('password.request') }}">Forgot Your Password?</a>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection