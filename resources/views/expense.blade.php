@extends('layouts.master')

@section('title', 'Expense Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Expenses</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal"><i
                        class="ti ti-plus"></i> Add Expense</button>
            </div>
        </div>

        <div class="card-body">
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
                ajax: "{{ route('expense.index') }}",
                columns: [
                    {
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
