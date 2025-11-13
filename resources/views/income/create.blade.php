@extends('layouts.master')

@section('title', 'Income List')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Create Income</h4>
                <a href="{{ route('income.index') }}" class="btn btn-dark">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('income.store') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label for="source" class="form-label">Income Source</label>
                        <input type="text" class="form-control" id="source" name="source" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0" step="0.01" value="0.00" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control date-pickr" id="date" name="date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Income</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.date-pickr').flatpickr({
            maxDate: "today",
        });
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
                    window.location.href = "{{ route('income.index') }}";
                },
                error: function(xhr) {
                    $('form button[type="submit"]').attr('disabled', false);
                    let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                    errorAlert(errors || 'An error occurred');
                }
            });
        });
    </script>
@endpush
