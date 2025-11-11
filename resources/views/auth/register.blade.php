@extends('layouts.guest')

@section('title', 'Registration Page')

@section('content')
    <p class="text-center">New Registration</p>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="registerName" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" id="registerName" aria-describedby="nameHelp">
            @error('name')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="registerEmail" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="registerEmail" aria-describedby="emailHelp">
            @error('email')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="registerPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="registerPassword1">
            @error('password')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-3">
            <label for="registerPassword2" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="registerPassword2">
            @error('password_confirmation')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Register</button>
        <div class="d-flex align-items-center justify-content-center">
            <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
            <a class="text-primary fw-bold ms-2" href="{{ route('login') }}">Sign In</a>
        </div>
    </form>
@endsection
