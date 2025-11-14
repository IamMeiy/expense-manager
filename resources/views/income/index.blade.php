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
            $('table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [[0, 'desc']],
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
        });
    </script>
@endpush
