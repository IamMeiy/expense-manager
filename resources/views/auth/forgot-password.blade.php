@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
    <p class="text-center">
        Forgot your password? No problem. Just let us know your email address and we will email you a
        password reset link that will allow you to choose a new one.
    </p>
    @if (session('status'))
        <div class="alert alert-success mb-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="forgotEmail" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="forgotEmail" aria-describedby="emailHelp">
            @error('email')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Email Password Reset Link</button>
    </form>
@endsection
