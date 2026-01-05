<x-app-layout :assets="['data-table']">
    <!-- Ensure CSRF Token Meta Tag -->
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    
    <div class="container-fluid px-2 px-md-3 py-3">
        <!-- Responsive Button Container -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4">
            <h4 class="text-success mb-3 mb-sm-0 fs-5 fs-md-4">Applicants Management</h4>
            <a href="{{ route('applicants.export') }}" class="btn btn-success btn-sm btn-md-normal">
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
            <table id="applicants-table" class="table table-bordered table-striped custom-table w-100">
                <thead class="table-success">
                    <tr>
                        <th class="text-nowrap">S/N</th>
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
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
        }
        
        .custom-table {
            border: 1px solid #28a745 !important;
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
            background-color: #28a745;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .custom-table thead th {
            border: 1px solid #28a745 !important;
            font-weight: 600;
            font-size: 13px;
            padding: 12px !important;
        }
        
        /* Actions column optimization */
        .custom-table th:last-child,
        .custom-table td:last-child {
            width: 55px !important;
            min-width: 55px !important;
            max-width: 55px !important;
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
        .custom-table th:nth-child(3) { width: 250px !important; } /* Full Name */
        .custom-table th:nth-child(4) { width: 200px !important; } /* Application Type */
        .custom-table th:nth-child(5) { width: 80px !important; } /* Status */
        .custom-table th:nth-child(6) { width: 55px !important; } /* Actions */
        
        .custom-table td:nth-child(1) { width: 40px !important; }
        .custom-table td:nth-child(2) { width: 80px !important; }
        .custom-table td:nth-child(3) { width: 250px !important; }
        .custom-table td:nth-child(4) { width: 200px !important; }
        .custom-table td:nth-child(5) { width: 80px !important; }
        .custom-table td:nth-child(6) { width: 55px !important; }
        
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
        
        .editable {
            display: inline-block;
            min-width: 80px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .editable:focus {
            outline: 2px solid #28a745;
            background: #f0fff4;
        }
        
        /* Action Buttons Styling */
        .btn-action {
            transition: all 0.2s ease;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        
        .btn-outline-primary:hover {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        
        .btn-outline-success:hover {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
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
            
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 0.75rem;
            }
            
            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                font-size: 14px;
                padding: 0.375rem 0.75rem;
            }
            
            .dataTables_wrapper .dataTables_paginate {
                margin-top: 1rem;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.25rem 0.5rem;
                margin: 0 1px;
                font-size: 12px;
            }
        }
        
        /* Tablet Optimizations */
        @media (min-width: 768px) and (max-width: 1023.98px) {
            .custom-table {
                font-size: 13px;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 9px 10px !important;
            }
        }
        
        /* Desktop Optimizations */
        @media (min-width: 1024px) {
            .custom-table th,
            .custom-table td {
                padding: 10px 12px !important;
            }
        }
    </style>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#applicants-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: '{{ route('applicants.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'application_id', name: 'application_id' },
                    { data: 'full_name', name: 'full_name' },
                    { data: 'application_type', name: 'application_type' },
                    { data: 'status', name: 'status', orderable: false },
                    { data: 'actions', name: 'actions', orderable: false }
                ],
                order: [[1, 'desc']]
            });

            // Handle update status button click
            $(document).on('click', '.update-status', function(e) {
                e.preventDefault();
                var applicantId = $(this).data('id');
                var btn = $(this);

                if (confirm('Are you sure you want to mark this applicant as Admitted?')) {
                    $.ajax({
                        url: '/applicants/' + applicantId + '/update-status',
                        type: 'POST',
                        data: {
                            status: 'Admitted',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            // Show success message
                            alert('Status updated to Admitted successfully!');
                            // Reload the datatable
                            $('#applicants-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            var errorMsg = 'Error updating status. Please try again.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            alert(errorMsg);
                            console.error(xhr);
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
