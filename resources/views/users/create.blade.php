@extends('layouts.master')

@section('title', 'Create User')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Create New User</h4>
                <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="ti ti-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Create User</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('form').on('submit', function(event) {
            event.preventDefault();
            const form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: form.serialize(),
                beforeSend: function() {
                    $('form button[type="submit"]').attr('disabled', true);
                },
                success: function(response) {
                    $('form button[type="submit"]').attr('disabled', false);
                    successAlert(response.message);
                    window.location.href = "{{ route('users.index') }}";
                },
                error: function(xhr) {
                    $('form button[type="submit"]').attr('disabled', false);
                    let errors = xhr.responseJSON.errors;
                    errorAlert(errors || 'An error occurred');
                }
            });
        });
    </script>
@endpush
