<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Applications Data - SOBAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .page-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 2rem 0;
            box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table thead th {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.75rem;
        }
        
        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
        }
        
        .badge {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
            font-weight: 500;
        }
        
        .badge-undergraduate {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .badge-postgraduate {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .badge-default {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .stats-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem 0;
            }
            
            .table thead th,
            .table tbody td {
                padding: 0.75rem 0.5rem;
                font-size: 0.9rem;
            }
            
            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="mb-0">
                        <i class="fas fa-users me-3"></i>
                        All Applications Data
                    </h1>
                    <p class="mb-0 mt-2 opacity-75">Complete list of all student applications</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-light btn-lg" onclick="exportToCSV()">
                            <i class="fas fa-download me-2"></i>
                            Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="container my-4">
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number">{{ $applications->count() }}</div>
                    <div class="stats-label">Total Applications</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stats-number">{{ $applications->where('application_type', 'undergraduate')->count() }}</div>
                    <div class="stats-label">Undergraduate</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <div class="stats-number">{{ $applications->where('application_type', 'postgraduate')->count() }}</div>
                    <div class="stats-label">Postgraduate</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-number">{{ $applications->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</div>
                    <div class="stats-label">Today</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h5 class="mb-0 text-success">
                            <i class="fas fa-table me-2"></i>
                            Applications Data Table
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="applicationsTable" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Application ID</th>
                                        <th>Full Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>State</th>
                                        <th>LGA</th>
                                        <th>Application Type</th>
                                        <th>Date Applied</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                    <tr>
                                        <td><strong>{{ $application->id }}</strong></td>
                                        <td><span class="badge bg-primary">{{ $application->application_id }}</span></td>
                                        <td>
                                            <strong>
                                                {{ trim(($application->firstname ?? '') . ' ' . ($application->middlename ?? '') . ' ' . ($application->surname ?? '')) ?: 'N/A' }}
                                            </strong>
                                        </td>
                                        <td>{{ $application->phone ?? 'N/A' }}</td>
                                        <td>{{ $application->email ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $application->gender == 'Male' ? 'bg-info' : 'bg-warning' }}">
                                                {{ $application->gender ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $application->state ?? 'N/A' }}</td>
                                        <td>{{ $application->lga ?? 'N/A' }}</td>
                                        <td>
                                            @if($application->application_type)
                                                @if(str_contains(strtolower($application->application_type), 'undergraduate'))
                                                    <span class="badge badge-undergraduate">{{ ucwords(str_replace('_', ' ', $application->application_type)) }}</span>
                                                @elseif(str_contains(strtolower($application->application_type), 'postgraduate'))
                                                    <span class="badge badge-postgraduate">{{ ucwords(str_replace('_', ' ', $application->application_type)) }}</span>
                                                @else
                                                    <span class="badge badge-default">{{ ucwords(str_replace('_', ' ', $application->application_type)) }}</span>
                                                @endif
                                            @else
                                                <span class="badge badge-default">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $application->created_at ? $application->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="container mt-5 mb-3">
        <div class="text-center text-muted">
            <p>&copy; {{ date('Y') }} SOBAS - Student Online Application System</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#applicationsTable').DataTable({
                order: [[0, 'desc']], // Order by ID descending (newest first)
                pageLength: 25,
                responsive: true,
                language: {
                    search: "Search Applications:",
                    lengthMenu: "Show _MENU_ applications per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ applications",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { responsivePriority: 1, targets: [1, 2] }, // Application ID and Name always visible
                    { responsivePriority: 2, targets: [8, 9] }  // Type and Date important
                ]
            });
        });

        function exportToCSV() {
            // Get table data
            const table = $('#applicationsTable').DataTable();
            const data = table.data().toArray();
            
            if (data.length === 0) {
                alert('No data to export.');
                return;
            }

            // Create CSV content
            const headers = ['ID', 'Application ID', 'Full Name', 'Phone', 'Email', 'Gender', 'State', 'LGA', 'Application Type', 'Date Applied'];
            let csvContent = headers.join(',') + '\n';

            // Add data rows
            @foreach($applications as $application)
            csvContent += [
                '{{ $application->id }}',
                '"{{ $application->application_id }}"',
                '"{{ trim(($application->firstname ?? "") . " " . ($application->middlename ?? "") . " " . ($application->surname ?? "")) ?: "N/A" }}"',
                '"{{ $application->phone ?? "N/A" }}"',
                '"{{ $application->email ?? "N/A" }}"',
                '"{{ $application->gender ?? "N/A" }}"',
                '"{{ $application->state ?? "N/A" }}"',
                '"{{ $application->lga ?? "N/A" }}"',
                '"{{ $application->application_type ? ucwords(str_replace("_", " ", $application->application_type)) : "N/A" }}"',
                '"{{ $application->created_at ? $application->created_at->format("d/m/Y H:i") : "N/A" }}"'
            ].join(',') + '\n';
            @endforeach

            // Create and download file
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            
            if (link.download !== undefined) {
                const url = URL.createObjectURL(blob);
                link.setAttribute('href', url);
                link.setAttribute('download', `all_applications_data_${new Date().toISOString().split('T')[0]}.csv`);
                link.style.visibility = 'hidden';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Show success message
                const btn = event.target;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-2"></i>Exported!';
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-light');
                }, 2000);
            } else {
                alert('Export feature is not supported in this browser.');
            }
        }
    </script>
</body>
</html>