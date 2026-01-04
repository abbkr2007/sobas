<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applicants Management - SOBAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
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

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-4">
                        <img id="qv-photo" src="" alt="Photo" class="rounded me-3" style="width:100px;height:100px;object-fit:cover;display:none;">
                        <div>
                            <div class="small text-muted">Matric Number</div>
                            <div id="qv-mat" class="fw-semibold fs-5">N/A</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold">Full Name:</span>
                        <div id="qv-name">N/A</div>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold">LGA:</span>
                        <div id="qv-lga">N/A</div>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold">State:</span>
                        <div id="qv-state">N/A</div>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold">Phone Number:</span>
                        <div id="qv-phone">N/A</div>
                    </div>
                    <div class="mb-3">
                        <span class="fw-semibold">Gender:</span>
                        <div id="qv-gender">N/A</div>
                    </div>
                    <div class="mb-4">
                        <span class="fw-semibold">Programme Type:</span>
                        <div id="qv-program">N/A</div>
                    </div>
                    <button id="qv-admit-btn" type="button" class="btn btn-primary w-100">
                        <span class="btn-label">Confirm Admission</span>
                        <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true" style="display:none;"></span>
                    </button>
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
            window.applicantsDT = $('#applicants-table').DataTable({
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
                            return names.length > 0 ? names.join(' ') : (data || 'N/A');
                        }
                    },
                    { 
                        data: 'application_type', 
                        name: 'application_type',
                        className: 'text-center',
                        width: '150px',
                        render: function (data) {
                            if (!data) return '<span class="badge bg-secondary">N/A</span>';
                            let formattedType = String(data).replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            let badgeClass = 'bg-secondary';
                            const lower = String(data).toLowerCase();
                            if (lower.includes('undergraduate')) badgeClass = 'bg-info';
                            else if (lower.includes('postgraduate')) badgeClass = 'bg-success';
                            return `<span class="badge ${badgeClass}">${formattedType}</span>`;
                        }
                    },
                    { 
                        data: 'status', 
                        name: 'status',
                        className: 'text-center',
                        width: '120px',
                        orderable: false,
                        render: function (data) {
                            const val = (data || '').toString().toLowerCase();
                            if (val === 'admitted') return '<span class="badge bg-success">Admitted</span>';
                            return '<span class="badge bg-warning text-dark">Pending</span>';
                        }
                    },
                    { 
                        data: 'id', 
                        name: 'actions',
                        className: 'text-center',
                        width: '220px',
                        orderable: false,
                        searchable: false,
                        render: function (data) {
                            return `
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewApplicant(${data})" title="View Applicant">
                                        <i class="bi bi-eye"></i> <span class="ms-1">View</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Quick View" onclick="openQuickViewById(${data})">
                                        <i class="bi bi-search"></i> <span class="ms-1">Quick View</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadSlip(${data})" title="Download Slip">
                                        <i class="bi bi-download"></i> <span class="ms-1">Slip</span>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [[0, 'desc']],
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

        let currentApplicantId = null;

        function openQuickViewById(id) {
            currentApplicantId = id;
            // Reset UI
            document.getElementById('qv-photo').style.display = 'none';
            document.getElementById('qv-photo').src = '';
            document.getElementById('qv-mat').textContent = 'N/A';
            document.getElementById('qv-name').textContent = 'N/A';
            document.getElementById('qv-lga').textContent = 'N/A';
            document.getElementById('qv-state').textContent = 'N/A';
            document.getElementById('qv-phone').textContent = 'N/A';
            document.getElementById('qv-gender').textContent = 'N/A';
            document.getElementById('qv-program').textContent = 'N/A';

            const modalEl = document.getElementById('quickViewModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

            // Fetch details
            fetch(`/applicant/${id}/details`, { headers: { 'Accept': 'application/json' } })
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const d = data.data || data; // support both wrappers

                    // Photo
                    const photo = d.photo || d.passport || d.profile_photo || d.picture || null;
                    if (photo) {
                        const img = document.getElementById('qv-photo');
                        img.src = photo;
                        img.style.display = '';
                    }

                    // Matric number
                    const mat = d.matric_no || d.mat_number || d.matric_number || d.matric || d.application_id || 'N/A';
                    document.getElementById('qv-mat').textContent = String(mat);

                    // Full name
                    const names = [];
                    if (d.firstname) names.push(d.firstname);
                    if (d.middlename) names.push(d.middlename);
                    if (d.surname) names.push(d.surname);
                    const fullName = names.length ? names.join(' ') : (d.full_name || 'N/A');
                    document.getElementById('qv-name').textContent = fullName;

                    // LGA / State
                    document.getElementById('qv-lga').textContent = d.lga || d.local_government || d.local_govt || 'N/A';
                    document.getElementById('qv-state').textContent = d.state || d.state_of_origin || 'N/A';

                    // Phone / Gender
                    document.getElementById('qv-phone').textContent = d.phone || d.phonenumber || d.phone_number || 'N/A';
                    document.getElementById('qv-gender').textContent = d.gender || 'N/A';

                    // Programme type
                    const appTypeRaw = d.application_type || d.programme_type || d.program_type || '';
                    const formattedType = appTypeRaw
                        ? String(appTypeRaw).replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
                        : 'N/A';
                    document.getElementById('qv-program').textContent = formattedType;
                })
                .catch(() => {
                    // leave defaults; optionally show a toast/alert
                });
        }

        document.getElementById('qv-admit-btn').addEventListener('click', function() {
            if (!currentApplicantId) return;
            const btn = this;
            const spinner = btn.querySelector('.spinner-border');
            btn.disabled = true;
            spinner.style.display = '';

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(`/applicants/${currentApplicantId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ status: 'admitted' })
            })
            .then(res => res.ok ? res.json() : Promise.reject(res))
            .then(() => {
                // Refresh table to reflect status
                if (window.applicantsDT) {
                    window.applicantsDT.ajax.reload(null, false);
                }
                // Close modal
                const modalEl = document.getElementById('quickViewModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal && modal.hide();
            })
            .catch(() => {
                // Optionally, surface error to user
            })
            .finally(() => {
                btn.disabled = false;
                spinner.style.display = 'none';
            });
        });
    </script>
</body>
</html>
