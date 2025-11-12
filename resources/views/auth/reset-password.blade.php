@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="resetEmail" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" id="resetEmail"
                value="{{ old('email', $request->email) }}" required autofocus>
            @error('email')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="resetPassword" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="resetPassword" required>
            @error('password')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="resetPasswordConfirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="resetPasswordConfirmation"
                required>
            @error('password_confirmation')
                <p class="text-danger mt-2">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4">Reset Password</button>
    </form>
@endsection
