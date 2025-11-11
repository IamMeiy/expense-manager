@extends('layouts.guest')

@section('title', 'Login Page')

@section('content')
    <p class="text-center">Sign in to your account</p>
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="loginEmail" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="loginEmail" aria-describedby="emailHelp">
            @error('email')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="loginPassword">
            @error('password')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember Me</label>
            </div>
            <a class="text-primary fw-bold" href="{{ route('password.request') }}">Forgot Password ?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Login</button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="fs-4 mb-0 fw-bold">Don't have an Account?</p>
            <a class="text-primary fw-bold ms-2" href="{{ route('register') }}">Sign Up</a>
        </div>
    </form>
@endsection
