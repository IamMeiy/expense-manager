@extends('layouts.master')

@section('title', 'Savings')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="card-title">Bank Details</div>
                <a href="{{ route('bank-accounts.index') }}" class="btn btn-dark">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>BANK NAME</td>
                            <td>{{ $bankAccount->bank_name }}</td>
                        </tr>
                        <tr>
                            <td>Account Number</td>
                            <td>{{ $bankAccount->account_number }}</td>
                        </tr>
                        <tr>
                            <td>Account Type</td>
                            <td>{{ BANK_ACCOUNT_TYPES[$bankAccount->account_type] }}</td>
                        </tr>

                        @php
                            $added = $bankAccount->savings->sum('amount');
                            $transferred = $bankAccount->savings->sum('transfered_amount');
                            $available = $added - $transferred;
                        @endphp

                        <tr>
                            <td>Available Balance</td>
                            <td id="available_balance">₹ {{ number_format($available, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Added Amount</td>
                            <td id="added_amount">₹ {{ number_format($added, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Transferred Amount</td>
                            <td id="transferred_amount">₹ {{ number_format($transferred, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">{{ $bankAccount->bank_name }} Savings</h4>
                <div class="btn-div">
                    <button class="btn btn-dark filter-btn my-1" type="button"
                        onclick="$('.filter-div').toggle('fast', 'linear');">
                        <i class="ti ti-filter me-1"></i> Filter
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSavingsModal">
                        <iconify-icon icon="mdi:bank-plus" class="me-2"></iconify-icon>
                        Add Savings
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="filter-div mb-3" style="display: none;">
                <div class="filter-body">
                    <div class="row mx-2">
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="from-date">From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control date-pickr"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="to-date">To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control date-pickr"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter-footer mt-3">
                    <div class="row mx-2">
                        <div class="col d-flex justify-content-end">
                            <div class="btn-div">
                                <button type="button" id="filter-btn" class="btn btn-info me-2 my-1">
                                    <i class="ti ti-filter me-1"></i> Apply Filter
                                </button>
                                <button type="button" id="reset-btn" class="btn btn-warning my-1">
                                    <i class="ti ti-refresh me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="savingsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Savings: Add modal --}}
    <div class="modal fade" id="addSavingsModal" tabindex="-1" aria-labelledby="addSavingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSavingsModalLabel">Add Savings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSavingsForm" method="POST" action="{{ route('savings.store', $bankAccount->id) }}"
                        autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control date-pickr" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" min="0"
                                step="0.01" value="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Savings: Edit modal --}}
    <div class="modal fade" id="editSavingsModal" tabindex="-1" aria-labelledby="editSavingsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSavingsModalLabel">Edit Savings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editSavingsModalBody">
                    <form id="editSavingsForm" method="POST" action="" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" class="form-control date-pickr" id="edit_date" name="date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" min="0"
                                step="0.01" value="0.00" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.date-pickr').flatpickr({
                maxDate: "today",
            });

            // Initialize DataTable
            const table = $('#savingsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('savings.index', $bankAccount->id) }}',
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'saved_at',
                        name: 'saved_at'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'balance',
                        name: 'balance'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $(document).on('click', '#filter-btn', function() {
                table.ajax.reload();
            });

            $(document).on('click', '#reset-btn', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                table.ajax.reload();
            });

            $(document).on('submit', '#addSavingsForm', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#addSavingsModal button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#addSavingsModal button[type="submit"]').prop('disabled', false);
                        $('#addSavingsModal').modal('hide');
                        successAlert(response.message);
                        table.ajax.reload();
                        form.trigger('reset');
                        updateAmount(response.amount);
                    },
                    error: function(xhr) {
                        $('#addSavingsModal button[type="submit"]').prop('disabled', false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors || 'An error occurred');
                    }
                });
            });

            $(document).on('click', '.edit-savings', function() {
                const url = $(this).data('url');
                const button = $(this);
                $.ajax({
                    url: url,
                    type: 'GET',
                    beforeSend: function() {
                        button.prop('disabled', true);
                        $('#editSavingsForm').trigger('reset');
                    },
                    success: function(response) {
                        button.prop('disabled', false);
                        const data = response.data;
                        $('#editSavingsForm').attr('action', response.url);
                        $('#edit_date').val(data.saved_at.split('T')[0]);
                        $('#edit_amount').val(data.amount);
                        $('#edit_description').val(data.description);
                        $('#editSavingsModal').modal('show');
                    },
                    error: function(xhr) {
                        button.prop('disabled', false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors ||
                            'An error occurred while fetching Savings details.');
                    }
                });
            });

            $(document).on('submit', '#editSavingsForm', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    type: 'PUT',
                    url: form.attr('action'),
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#editSavingsModal button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#editSavingsModal button[type="submit"]').prop('disabled',
                            false);
                        $('#editSavingsModal').modal('hide');
                        successAlert(response.message);
                        table.ajax.reload();
                        updateAmount(response.amount);
                    },
                    error: function(xhr) {
                        $('#editSavingsModal button[type="submit"]').prop('disabled',
                            false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors ||
                            'An error occurred while updating the savings.');
                    }
                });
            });

            $(document).on('click', '.delete-savings', function() {
                const url = $(this).data('url');
                const button = $(this);
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            beforeSend: function() {
                                button.prop('disabled', true);
                            },
                            success: function(result) {
                                button.prop('disabled', false);
                                table.ajax.reload();
                                successAlert(result.message);
                                updateAmount(result.amount);
                            },
                            error: function(xhr, status, error) {
                                button.prop('disabled', false);
                                let errors = xhr.responseJSON.errors;
                                errorAlert(errors || {
                                    'error': [
                                        'An error occurred while deleting the savings.'
                                    ]
                                });
                            }
                        });
                    }
                });
            });

            function updateAmount(amount) {
                $('#available_balance').text('₹ ' + amount.available);
                $('#added_amount').text('₹ ' + amount.added);
                $('#transferred_amount').text('₹ ' + amount.transferred);
            }
        });
    </script>
@endpush
