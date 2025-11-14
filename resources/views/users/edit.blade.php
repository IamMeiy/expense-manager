@extends('layouts.master')

@section('title', 'Edit User')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Edit User</h4>
                <a href="{{ route('users.index') }}" class="btn btn-dark">
                    <i class="ti ti-arrow-left"></i> Back to Users
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', encrypt($user->id)) }}" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name"
                        value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email"
                        value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password (leave blank to keep current password)</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
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
                    successAlert('User updated successfully!');
                    window.location.href = "{{ route('users.index') }}";
                },
                error: function(xhr) {
                    $('form button[type="submit"]').attr('disabled', false);
                    let errors = xhr.responseJSON.errors;
                    errorAlert(errors);
                }

            });
        });
    </script>
@endpush
