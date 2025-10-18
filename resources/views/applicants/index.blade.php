<x-app-layout :assets="['data-table']">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-success">Applicants List</h1>
            <div>
                <span class="badge bg-info me-2">Total: {{ \App\Models\Application::count() }} records</span>
                <a href="{{ route('applicants.export') }}" class="btn btn-success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14,2 14,8 20,8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10,9 9,9 8,9"></polyline>
                    </svg>
                    Export to Excel
                </a>
            </div>
        </div>
        <table id="applicants-table" class="table table-bordered table-striped custom-table">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Application ID</th>
                    <th>Surname</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Application Type</th>
                </tr>
            </thead>
        </table>
    </div>

    <style>
        .custom-table {
            border: 1px solid #28a745 !important;
            border-radius: 6px;
            overflow: hidden;
            font-size: 14px;
        }
        .custom-table th,
        .custom-table td {
            border: 1px solid #28a745 !important;
            vertical-align: middle;
            padding: 8px 12px;
        }
        .custom-table thead {
            background-color: #d4edda;
            color: #155724;
        }
        .custom-table tbody tr:hover {
            background-color: #f6fff6;
        }
        .badge-application-type {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .badge-undergraduate {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-postgraduate {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-default {
            background-color: #f8f9fa;
            color: #495057;
        }
    </style>

    @push('scripts')
    <script>
        $(function () {
            let table = $('#applicants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('applicants.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'application_id', name: 'application_id' },
                    { data: 'surname', name: 'surname' },
                    { data: 'firstname', name: 'firstname' },
                    { data: 'middlename', name: 'middlename', orderable: false },
                    { 
                        data: 'application_type', 
                        name: 'application_type',
                        render: function (data, type, row) {
                            if (!data) return '<span class="badge badge-default">N/A</span>';
                            
                            let formattedType = data.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let badgeClass = 'badge-default';
                            
                            if (data.toLowerCase().includes('undergraduate')) {
                                badgeClass = 'badge-undergraduate';
                            } else if (data.toLowerCase().includes('postgraduate')) {
                                badgeClass = 'badge-postgraduate';
                            }
                            
                            return `<span class="badge badge-application-type ${badgeClass}">${formattedType}</span>`;
                        }
                    }
                ],
                order: [[0, 'desc']], // Order by ID descending (latest first)
                pageLength: 25,
                responsive: true,
                language: {
                    processing: '<div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div>',
                    emptyTable: 'No applicants found',
                    zeroRecords: 'No matching applicants found'
                }
            });
        });
    </script>
    @endpush
</x-app-layout>