<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants Management - SOBAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Applicants Management</h2>
                
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Applications List</h5>
                    </div>
                    <div class="card-body">
                        <table id="applicants-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Application ID</th>
                                    <th>Full Name</th>
                                    <th>Application Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applicants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('applicants.index') }}',
                columns: [
                    { 
                        data: 'id', 
                        name: 'id',
                        className: 'text-center',
                        width: '60px'
                    },
                    { 
                        data: 'application_id', 
                        name: 'application_id',
                        className: 'text-center',
                        width: '120px'
                    },
                    { 
                        data: 'full_name', 
                        name: 'full_name',
                        orderable: false,
                        render: function (data, type, row) {
                            let names = [];
                            if (row.firstname) names.push(row.firstname);
                            if (row.middlename) names.push(row.middlename);
                            if (row.surname) names.push(row.surname);
                            return names.length > 0 ? names.join(' ') : 'N/A';
                        }
                    },
                    { 
                        data: 'application_type', 
                        name: 'application_type',
                        className: 'text-center',
                        width: '150px',
                        render: function (data, type, row) {
                            if (!data) return '<span class="badge bg-secondary">N/A</span>';
                            
                            let formattedType = data.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let badgeClass = 'bg-secondary';
                            
                            if (data.toLowerCase().includes('undergraduate')) {
                                badgeClass = 'bg-info';
                            } else if (data.toLowerCase().includes('postgraduate')) {
                                badgeClass = 'bg-success';
                            }
                            
                            return `<span class="badge ${badgeClass}">${formattedType}</span>`;
                        }
                    },
                    { 
                        data: 'status', 
                        name: 'status',
                        className: 'text-center',
                        width: '100px',
                        orderable: false,
                        render: function (data, type, row) {
                            return '<span class="badge bg-warning text-dark">Pending</span>';
                        }
                    },
                    { 
                        data: 'id', 
                        name: 'actions',
                        className: 'text-center',
                        width: '120px',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewApplicant(${data})" title="View Details">
                                        View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadSlip(${data})" title="Download Slip">
                                        Download
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[0, 'desc']], // Order by ID descending (latest first)
                pageLength: 25,
                responsive: true,
                language: {
                    processing: 'Loading applications...',
                    emptyTable: 'No applicants found',
                    zeroRecords: 'No matching applicants found',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    search: 'Search:',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    }
                }
            });
        });

        function viewApplicant(id) {
            window.open(`/application-preview/${id}`, '_blank');
        }
        
        function downloadSlip(id) {
            window.open(`/applications/${id}/acknowledgment`, '_blank');
        }
    </script>
</body>
</html>