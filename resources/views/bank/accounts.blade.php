@extends('layouts.master')

@section('title', 'Bank Accounts')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Bank Accounts</h4>
                <div class="btn-div">
                    <button class="btn btn-dark filter-btn my-1" type="button"
                        onclick="$('.filter-div').toggle('fast', 'linear');">
                        <i class="ti ti-filter me-1"></i> Filter
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBankAccountModal">
                        <iconify-icon icon="mdi:bank-plus" class="me-2"></iconify-icon>
                        Add Account
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
                                <label for="account_type">Account Type</label>
                                <select name="account_type" id="account_type" class="form-select select-2"
                                    style="width: 100%;" autocomplete="off">
                                    <option value="">Select Account Type</option>
                                    @foreach (BANK_ACCOUNT_TYPES as $key => $type)
                                        <option value="{{ $key }}">{{ $type }}</option>
                                    @endforeach
                                </select>
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
                <table class="table table-bordered" id="bankAccountsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th>Account Type</th>
                            <th>Account Number</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bank Account: Add modal --}}
    <div class="modal fade" id="addBankAccountModal" tabindex="-1" aria-labelledby="addBankAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBankAccountModalLabel">Add Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBankAccountForm" method="POST" action="{{ route('bank-accounts.store') }}"
                        autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label for="account_type" class="form-label">Account Type</label>
                            <select name="account_type" id="account_type" class="form-select modal-select"
                                style="width: 100%;" required>
                                <option value="">Select Bank Type</option>
                                @foreach (BANK_ACCOUNT_TYPES as $key => $bankAccountType)
                                    <option value="{{ $key }}" data-parent="">
                                        {{ $bankAccountType }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Bank Account: Edit modal --}}
    <div class="modal fade" id="editBankAccountModal" tabindex="-1" aria-labelledby="editBankAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBankAccountModalLabel">Edit Bank Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editBankAccountModalBody">
                    <form id="editBankAccountForm" method="POST" action="" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_account_type" class="form-label">Account Type</label>
                            <select name="account_type" id="edit_account_type" class="form-select modal-select"
                                style="width: 100%;" required>
                                <option value="">Select Bank Type</option>
                                @foreach (BANK_ACCOUNT_TYPES as $key => $bankAccountType)
                                    <option value="{{ $key }}" data-parent="">
                                        {{ $bankAccountType }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bank_name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="edit_bank_name" name="bank_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_account_number" class="form-label">Account Number</label>
                            <input type="text" class="form-control" id="edit_account_number" name="account_number"
                                required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#bankAccountsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: { 
                    url: '{{ route('bank-accounts.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.account_type = $('#account_type').val();
                    }  
                },
                order: [
                    [6, 'asc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bank_name',
                        name: 'bank_name'
                    },
                    {
                        data: 'account_type',
                        name: 'account_type'
                    },
                    {
                        data: 'account_number',
                        name: 'account_number'
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
                    },
                    {
                        data: 'id',
                        name: 'id',
                        visible: false
                    }
                ]
            });

            $(document).on('click', '#filter-btn', function() {
                table.ajax.reload();
            });
            
            $(document).on('click', '#reset-btn', function() {
                $('#account_type').val('').trigger('change');
                table.ajax.reload();
            });

            $(document).on('submit', '#addBankAccountForm', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    type: 'POST',
                    url: form.attr('action'),
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#addBankAccountModal button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#addBankAccountModal button[type="submit"]').prop('disabled', false);
                        $('#addBankAccountModal').modal('hide');
                        successAlert(response.message);
                        table.ajax.reload();
                        form.trigger('reset');
                    },
                    error: function(xhr) {
                        $('#addBankAccountModal button[type="submit"]').prop('disabled', false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors || 'An error occurred');
                    }
                });
            });

            $(document).on('click', '.edit-bank-account', function() {
                const url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        const data = response.data;
                        $('#editBankAccountForm').attr('action', response.url);
                        $('#edit_account_type').val(data.account_type);
                        $('#edit_bank_name').val(data.bank_name);
                        $('#edit_account_number').val(data.account_number);
                        $('#editBankAccountModal').modal('show');
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors ||
                            'An error occurred while fetching bank account details.');
                    }
                });
            });

            $(document).on('submit', '#editBankAccountForm', function(e) {
                e.preventDefault();
                const form = $(this);
                $.ajax({
                    type: 'PUT',
                    url: form.attr('action'),
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#editBankAccountModal button[type="submit"]').prop('disabled', true);
                    },
                    success: function(response) {
                        $('#editBankAccountModal button[type="submit"]').prop('disabled',
                            false);
                        $('#editBankAccountModal').modal('hide');
                        successAlert(response.message);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        $('#editBankAccountModal button[type="submit"]').prop('disabled',
                            false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors ||
                            'An error occurred while updating the bank account.');
                    }
                });
            });

            $(document).on('click', '.delete-bank-account', function() {
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
                            },
                            error: function(xhr, status, error) {
                                button.prop('disabled', false);
                                let errors = xhr.responseJSON.errors;
                                errorAlert(errors || {
                                    'error': [
                                        'An error occurred while deleting the bank account.'
                                    ]
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
