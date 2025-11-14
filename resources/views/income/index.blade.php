@extends('layouts.master')

@section('title', 'Income List')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Income List</h4>
                <a href="{{ route('income.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-1"></i> Add Income
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">Source</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const table = $('table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [0, 'desc']
                ],
                ajax: '{{ route('income.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'source',
                        name: 'source'
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        className: 'text-start'
                    },
                    {
                        data: 'received_at',
                        name: 'received_at',
                        className: 'text-center'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });

            $(document).on('click', '.delete-income', function() {
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
                                        'An error occurred while deleting the income.'
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
