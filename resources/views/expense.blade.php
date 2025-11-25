@extends('layouts.master')

@section('title', 'Expense Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Expenses</h4>
                <div class="btn-div">
                    <button class="btn btn-dark filter-btn my-1" type="button"
                        onclick="$('.filter-div').toggle('fast', 'linear');">
                        <i class="ti ti-filter me-1"></i> Filter
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal"><i
                            class="ti ti-plus"></i> Add Expense</button>
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
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="expense_type">Expense Type</label>
                                <select name="expense_type" id="expense_type" class="form-select select-2" style="width: 100%;" autocomplete="off">
                                    <option value="">Select Expense Type</option>
                                    @foreach (EXPENSE_TYPES as $key => $expenseType)
                                        <option value="{{ $key }}">{{ $expenseType['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-3">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select name="payment_method" id="payment_method" class="form-select select-2" style="width: 100%;" autocomplete="off">
                                    <option value="">Select Payment Method</option>
                                    @foreach (PAYMENT_METHODS as $key => $method)
                                        <option value="{{ $key }}">{{ $method }}</option>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Payee</th>
                            <th>Amount</th>
                            <th>Expense Type</th>
                            <th>Payment Method</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Expense - Add Modal --}}
    <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addExpenseModalLabel">Add Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addExpenseForm" method="POST" action="{{ route('expense.store') }}" autocomplete="off">
                        @csrf
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control date-pickr" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="payee" class="form-label">Payee</label>
                            <input type="text" class="form-control" id="payee" name="payee" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="expense_type" class="form-label">Expense Type</label>
                            <select name="expense_type_id" id="expense_type" class="form-select modal-select"
                                style="width: 100%;" required>
                                <option value="">Select Expense Type</option>
                                @foreach (EXPENSE_TYPES as $key => $expenseType)
                                    <option value="{{ $key }}" data-parent="" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="{{ $expenseType['description'] }}">
                                        {{ $expenseType['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="payment_method" class="form-select modal-select"
                                style="width: 100%;" required>
                                <option value="">Select Payment Method</option>
                                @foreach (PAYMENT_METHODS as $key => $method)
                                    <option value="{{ $key }}">{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Expense</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Expense - Edit Modal --}}
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editExpenseForm" method="PUT" action="" autocomplete="off">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_date" class="form-label">Date</label>
                            <input type="date" class="form-control date-pickr" id="edit_date" name="date"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payee" class="form-label">Payee</label>
                            <input type="text" class="form-control" id="edit_payee" name="payee" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="edit_amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_expense_type" class="form-label">Expense Type</label>
                            <select name="expense_type_id" id="edit_expense_type" class="form-select modal-select"
                                style="width: 100%;" required>
                                <option value="">Select Expense Type</option>
                                @foreach (EXPENSE_TYPES as $key => $expenseType)
                                    <option value="{{ $key }}" data-parent="" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-title="{{ $expenseType['description'] }}">
                                        {{ $expenseType['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method_id" id="edit_payment_method" class="form-select modal-select"
                                style="width: 100%;" required>
                                <option value="">Select Payment Method</option>
                                @foreach (PAYMENT_METHODS as $key => $method)
                                    <option value="{{ $key }}">{{ $method }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Expense</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [0, 'desc'],
                    [7, 'desc']
                ],
                ajax: {
                    url :"{{ route('expense.index') }}",
                    type: 'GET',
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.expense_type = $('#expense_type').val();
                        d.payment_method = $('#payment_method').val();
                    }   
                },
                columns: [{
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'payee',
                        name: 'payee'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'expense_type',
                        name: 'expense_type'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
                    },
                ]
            });

            $(document).on('click', '#filter-btn', function() {
                table.ajax.reload();
            });

            $(document).on('click', '#reset-btn', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#expense_type').val('').trigger('change');
                $('#payment_method').val('').trigger('change');
                table.ajax.reload();
            });

            $('.date-pickr').flatpickr({
                maxDate: "today",
            });

            $(document).on('submit', '#addExpenseForm', function(event) {
                event.preventDefault();
                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#addExpenseForm button[type="submit"]').attr('disabled', true);
                    },
                    success: function(response) {
                        $('#addExpenseForm button[type="submit"]').attr('disabled', false);
                        $('#addExpenseModal').modal('hide');
                        successAlert(response.message);
                        table.ajax.reload();
                        $('#addExpenseForm').trigger('reset');
                    },
                    error: function(xhr) {
                        $('#addExpenseForm button[type="submit"]').attr('disabled', false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors || 'An error occurred');
                    }
                });
            });

            $(document).on('click', '.edit-expense', function() {
                const url = $(this).data('url');
                const button = $(this);
                $.ajax({
                    url: url,
                    method: 'GET',
                    beforeSend: function() {
                        button.prop('disabled', true);
                        $('#editExpenseForm').trigger('reset');
                    },
                    success: function(response) {
                        button.prop('disabled', false);
                        const expense = response.expense;
                        $('#editExpenseForm').attr('action', response.url);
                        $('#edit_date').val(expense.date.split('T')[0]);
                        $('#edit_payee').val(expense.payee);
                        $('#edit_amount').val(expense.amount);
                        $('#edit_expense_type').val(expense.expense_type_id).trigger('change');
                        $('#edit_payment_method').val(expense.payment_method_id).trigger(
                            'change');
                        $('#edit_description').val(expense.description);
                        $('#editExpenseModal').modal('show');
                    },
                    error: function(xhr) {
                        button.prop('disabled', false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors ||
                            'An error occurred while fetching the expense details.');
                    }
                });
            });

            $(document).on('submit', '#editExpenseForm', function(event) {
                event.preventDefault();
                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    beforeSend: function() {
                        $('#editExpenseForm button[type="submit"]').attr('disabled', true);
                    },
                    success: function(response) {
                        $('#editExpenseForm button[type="submit"]').attr('disabled', false);
                        $('#editExpenseModal').modal('hide');
                        successAlert(response.message);
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        $('#editExpenseForm button[type="submit"]').attr('disabled', false);
                        let errors = xhr.responseJSON.errors || xhr.responseJSON.message;
                        errorAlert(errors || 'An error occurred');
                    }
                });
            });

            $(document).on('click', '.delete-expense', function() {
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
                                        'An error occurred while deleting the expense.'
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
