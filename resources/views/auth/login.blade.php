@extends('layouts.login-layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 mt-5">
            <div class="card shadow-lg">
                <div class="card-header rounded-top bg-primary text-white">
                    <div class="h4 text-center"><i class="fa-solid fa-lock"></i> {{ __('Login') }}</div>
                </div>

                <div class="card-body rounded-bottom bg-primary text-white">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Username --}}
                        <div class="row mb-3">
                            <label for="username" class="col-md-4 col-form-label text-md-end">Username</label>
                            <div class="col-md-6">
                                <input id="username" type="text"
                                    class="form-control @error('username') is-invalid @enderror" name="username"
                                    value="{{ old('username') }}" required autofocus>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password')
                                }}</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{
                                        old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-light shadow">
                                    {{ __('Login') }}
                                </button>
                                <a href="{{ url('/') }}" class="btn btn-danger shadow">Kembali</a>
                            </div>
                        </div>
                    </form>

                    {{-- Forgot Password --}}
                    @if (Route::has('password.request'))
                    <div class="mt-2">
                        <a class="btn btn-link text-white" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection