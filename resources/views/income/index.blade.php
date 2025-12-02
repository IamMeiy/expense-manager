@extends('layouts.master')

@section('title', 'Income List')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Income List</h4>
                <div class="btn-div">
                    <button class="btn btn-dark filter-btn my-1" type="button"
                        onclick="$('.filter-div').toggle('fast', 'linear');">
                        <i class="ti ti-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('income.create') }}" class="btn btn-primary my-1">
                        <i class="ti ti-plus me-1"></i> Add Income
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="filter-div mb-3" style="display: none;">
                    <div class="filter-body">
                        <div class="row mx-2">
                            <div class="col-sm-12 col-md-4 col-lg-3">
                                <div class="form-group">
                                    <label for="source">Income Source</label>
                                    <select name="source" id="source" class="form-select select-2" style="width: 100%;" required>
                                        <option value="">Select Income Source</option>
                                        @foreach (INCOME_SOURCES as $key => $source)
                                            <option value="{{ $key }}">{{ $source }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Source</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Actions</th>
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
            $('.date-pickr').flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true,
                maxDate: "today"
            });

            const table = $('table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [
                    [0, 'desc'],
                    [4, 'desc']
                ],
                ajax: {
                    url: '{{ route('income.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.source = $('#source').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                },
                columns: [{
                        data: 'received_at',
                        name: 'received_at',
                        className: 'text-center'
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
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
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
                $('#source').val('').trigger('change');
                $('#from_date').val('');
                $('#to_date').val('');
                table.ajax.reload();
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
