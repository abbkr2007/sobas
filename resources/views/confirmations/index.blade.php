<x-app-layout :assets="['data-table']">
    <!-- Ensure CSRF Token Meta Tag -->
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    
    <div class="container-fluid px-2 px-md-3 py-3">
        <!-- Responsive Button Container -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
            <h4 class="text-info mb-3 mb-sm-0 fs-5 fs-md-4">Confirmation List</h4>
            <a href="{{ route('confirmations.export') }}" class="btn btn-info btn-sm btn-md-normal">
                <i class="fas fa-download me-1 me-md-2"></i>
                <span class="d-none d-sm-inline">Export CSV</span>
                <span class="d-sm-none">Export</span>
            </a>
        </div>
        
        <!-- Debug CSRF Token -->
        <script>
            $(document).ready(function() {
                // Ensure CSRF token is available
                if (!$('meta[name="csrf-token"]').length) {
                    $('head').append('<meta name="csrf-token" content="{{ csrf_token() }}">');
                }
                
                // Set up AJAX defaults to include CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log('CSRF Token:', csrfToken);
            });
        </script>
        
        <!-- Responsive Table Container -->
        <div class="table-responsive">
            <table id="confirmations-table" class="table table-bordered table-striped custom-table w-100">
                <thead class="table-info">
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Application ID</th>
                        <th class="text-nowrap">Full Name</th>
                        <th class="text-nowrap d-none d-lg-table-cell">Application Type</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <style>
        /* Modal Fixes */
        .modal {
            z-index: 1055 !important;
        }
        
        .modal-backdrop {
            z-index: 1050 !important;
        }
        
        /* Responsive Table Styles */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.1);
        }
        
        .custom-table {
            border: 1px solid #17a2b8 !important;
            border-radius: 6px;
            overflow: hidden;
            font-size: 14px;
            margin-bottom: 0;
        }
        
        .custom-table th,
        .custom-table td {
            border: 1px solid #e0e0e0 !important;
            vertical-align: middle;
            padding: 10px 12px !important;
            white-space: nowrap;
        }
        
        .custom-table thead {
            background-color: #17a2b8;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .custom-table thead th {
            border: 1px solid #17a2b8 !important;
            font-weight: 600;
            font-size: 13px;
            padding: 12px !important;
        }
        
        /* Actions column optimization */
        .custom-table th:last-child,
        .custom-table td:last-child {
            width: 130px !important;
            min-width: 130px !important;
            max-width: 130px !important;
            text-align: center !important;
            white-space: nowrap !important;
        }
        
        /* Make action buttons reasonably compact */
        .custom-table .btn-sm {
            padding: 0.25rem 0.4rem !important;
            font-size: 0.8rem !important;
            line-height: 1.2 !important;
        }
        
        /* Prevent table from expanding beyond container */
        .table-responsive {
            overflow-x: auto;
        }
        
        .custom-table {
            table-layout: fixed !important;
            width: 100% !important;
        }
        
        /* Prevent text wrapping in all cells */
        .custom-table th,
        .custom-table td {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        
        /* Column width distribution - Balanced spacing */
        .custom-table th:nth-child(1) { width: 40px !important; } /* ID */
        .custom-table th:nth-child(2) { width: 80px !important; } /* Application ID */
        .custom-table th:nth-child(3) { width: 220px !important; } /* Full Name */
        .custom-table th:nth-child(4) { width: 180px !important; } /* Application Type */
        .custom-table th:nth-child(5) { width: 80px !important; } /* Status */
        .custom-table th:nth-child(6) { width: 130px !important; } /* Actions */
        
        .custom-table td:nth-child(1) { width: 40px !important; }
        .custom-table td:nth-child(2) { width: 80px !important; }
        .custom-table td:nth-child(3) { width: 220px !important; }
        .custom-table td:nth-child(4) { width: 180px !important; }
        .custom-table td:nth-child(5) { width: 80px !important; }
        .custom-table td:nth-child(6) { width: 130px !important; }
        
        /* Text formatting */
        .custom-table td:nth-child(3), /* Full Name */
        .custom-table td:nth-child(4), /* Application Type */
        .custom-table td:nth-child(5) { /* Status */
            text-transform: capitalize !important;
        }
        
        /* Keep Application ID as uppercase */
        .custom-table td:nth-child(2) {
            text-transform: uppercase !important;
        }
        
        /* Make status badges reasonably sized */
        .custom-table .badge {
            font-size: 0.75rem !important;
            padding: 0.3rem 0.6rem !important;
        }
        
        .custom-table tbody tr {
            background-color: #fff;
            transition: background-color 0.2s ease;
        }
        
        .custom-table tbody tr:hover {
            background-color: #f9f9f9;
        }
        
        /* Mobile Optimizations */
        @media (max-width: 767.98px) {
            .custom-table {
                font-size: 12px;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 8px 6px !important;
            }
        }
    </style>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#confirmations-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: '{{ route('confirmations.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'application_id', name: 'application_id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'application_type', name: 'application_type' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'actions', name: 'actions', orderable: false }
                ],
                order: [[0, 'desc']]
            });
        });
    </script>
    @endpush
</x-app-layout>
