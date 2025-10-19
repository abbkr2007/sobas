<x-app-layout :assets="['data-table']">
    <!-- Ensure CSRF Token Meta Tag -->
    @push('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    
    <br />
    <div class="container-fluid px-2 px-md-3">
        <!-- Responsive Button Container -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 mb-md-4">
            <h4 class="text-success mb-2 mb-sm-0 fs-5 fs-md-4">Applicants Management</h4>
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

    <!-- Applicant Details Modal -->
        <!-- Custom Modal (non-Bootstrap) to avoid freezing -->
    <div id="customApplicantModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; max-width: 800px; width: 90%; max-height: 80%; overflow-y: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                <h5 style="margin: 0; color: #28a745;">
                    <i class="fas fa-user-circle me-2"></i>Applicant Details
                </h5>
                <div>
                    <button id="closeCustomModal" style="background: #dc3545; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
            <div id="customModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Original Bootstrap Modal (kept as backup) -->
    <div class="modal fade" id="applicantModal" tabindex="-1" aria-labelledby="applicantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="applicantModalLabel">
                        <i class="fas fa-user me-2"></i>Applicant Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Responsive Table Styles */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.1);
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
            border: 1px solid #28a745 !important;
            vertical-align: middle;
            padding: 8px 12px;
            white-space: nowrap;
        }
        
        .custom-table thead {
            background-color: #d4edda;
            color: #155724;
            position: sticky;
            top: 0;
            z-index: 10;
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
            padding: 0.2rem 0.35rem !important;
            font-size: 0.75rem !important;
            line-height: 1.2 !important;
        }
        
        /* Reasonable table cell padding */
        .custom-table th,
        .custom-table td {
            padding: 6px 8px !important;
            font-size: 0.9rem !important;
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
        .custom-table th:nth-child(1) { width: 35px !important; white-space: nowrap !important; } /* ID */
        .custom-table th:nth-child(2) { width: 75px !important; white-space: nowrap !important; } /* Application ID */
        .custom-table th:nth-child(3) { width: 250px !important; white-space: nowrap !important; } /* Full Name */
        .custom-table th:nth-child(4) { width: 210px !important; white-space: nowrap !important; } /* Application Type */
        .custom-table th:nth-child(5) { width: 65px !important; white-space: nowrap !important; } /* Status */
        .custom-table th:nth-child(6) { width: 55px !important; white-space: nowrap !important; } /* Actions */
        
        .custom-table td:nth-child(1) { width: 35px !important; white-space: nowrap !important; }
        .custom-table td:nth-child(2) { width: 75px !important; white-space: nowrap !important; }
        .custom-table td:nth-child(3) { width: 250px !important; white-space: nowrap !important; }
        .custom-table td:nth-child(4) { width: 210px !important; white-space: nowrap !important; }
        .custom-table td:nth-child(5) { width: 65px !important; white-space: nowrap !important; }
        .custom-table td:nth-child(6) { width: 55px !important; white-space: nowrap !important; }
        
        /* Text formatting - Capitalize first letter except Application ID */
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
            font-size: 0.7rem !important;
            padding: 0.25rem 0.5rem !important;
        }
        
        .custom-table tbody tr:hover {
            background-color: #f6fff6;
        }
        
        .editable {
            display: inline-block;
            min-width: 80px;
            padding: 4px 8px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        /* Mobile Optimizations */
        @media (max-width: 767.98px) {
            .custom-table {
                font-size: 12px;
            }
            
            .custom-table th,
            .custom-table td {
                padding: 6px 8px;
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
                padding: 7px 10px;
            }
        }
        
        /* Desktop Optimizations */
        @media (min-width: 1024px) {
            .custom-table th,
            .custom-table td {
            border-radius: 4px;
        }
        .editable:focus {
            outline: 2px solid #28a745;
            background: #f0fff4;
        }
        
        /* Custom modal loading spinner animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .btn-outline-primary:hover {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        
        .btn-outline-success:hover {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        
        /* Modal Styling */
        .modal-header {
            border-bottom: 2px solid #28a745;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        
        .modal-body .table {
            margin-bottom: 0;
        }
        
        .modal-body .table td {
            border: none;
            padding: 8px 12px;
        }
        
        .modal-body .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .modal-body h6 {
            border-bottom: 2px solid #28a745;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
    </style>

    @push('scripts')
    <script>
        $(function () {
            console.log('Starting DataTable initialization...');
            console.log('AJAX URL:', '{{ route('applicants.index') }}');
            
            // Add a timeout to prevent infinite freezing
            let initTimeout = setTimeout(function() {
                console.error('DataTable initialization timed out after 30 seconds');
                alert('Table loading is taking too long. Please refresh the page.');
            }, 30000);
            
            // Test the AJAX endpoint first
            $.ajax({
                url: '{{ route('applicants.index') }}',
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                timeout: 15000, // 15 second timeout
                success: function(data) {
                    console.log('Direct AJAX test success:', data);
                    console.log('Data type:', typeof data);
                    console.log('Data keys:', Object.keys(data));
                    if (data.data) {
                        console.log('First item:', data.data[0]);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Direct AJAX test error:', {xhr, status, error});
                    console.error('Response text:', xhr.responseText);
                    clearTimeout(initTimeout);
                    alert('Failed to load data from server');
                    return;
                }
            });
            
            let simpleTable; // Declare outside try block for broader scope
            
            try {
                // Switch back to server-side processing to handle large datasets better
                simpleTable = $('#applicants-table').DataTable({
                    processing: true,
                    serverSide: true,
                    pageLength: 25, // Show 25 records per page to improve performance
                    ajax: {
                        url: '{{ route('applicants.index') }}',
                        type: 'GET',
                        data: function(d) {
                            d.cache_bust = new Date().getTime(); // Add cache busting parameter
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        error: function(xhr, error, thrown) {
                            console.error('DataTable AJAX Error:', error, thrown);
                            alert('Error loading data: ' + error);
                        }
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'application_id', name: 'application_id' },
                        { data: 'full_name', name: 'full_name' },
                        { data: 'application_type', name: 'application_type' },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false }
                    ],
                    order: [[0, 'desc']], // Order by ID descending (newest first)
                    drawCallback: function(settings) {
                        console.log('DataTable draw callback - rows:', settings.fnRecordsDisplay());
                        // Re-attach event handlers after each draw
                        console.log('Draw callback completed successfully');
                    },
                    initComplete: function(settings, json) {
                        console.log('DataTable init complete - data:', json);
                        console.log('Page is now interactive');
                    }
                });
                
                console.log('DataTable initialized:', simpleTable);
                clearTimeout(initTimeout); // Clear the timeout since initialization succeeded
            } catch (error) {
                console.error('Error initializing DataTable:', error);
                clearTimeout(initTimeout);
                alert('Failed to initialize table: ' + error.message);
            }

            // Handle view button click - Using custom modal to avoid Bootstrap conflicts
            $(document).on('click', '.view-applicant', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                console.log('View button clicked');
                
                let applicantId = $(this).data('id');
                console.log('Applicant ID:', applicantId);
                
                if (!applicantId) {
                    alert('Applicant ID not found');
                    return false;
                }
                
                // Show custom modal with loading
                $('#customModalContent').html(`
                    <div style="text-align: center; padding: 40px;">
                        <div style="border: 4px solid #f3f3f3; border-top: 4px solid #28a745; border-radius: 50%; width: 40px; height: 40px; animation: spin 2s linear infinite; margin: 0 auto;"></div>
                        <p style="margin-top: 20px;">Loading applicant details...</p>
                    </div>
                `);
                $('#customApplicantModal').show();
                
                // Store the applicant ID for download functionality
                $('#downloadAdmissionLetter').data('current-applicant', applicantId);
                
                // Fetch data
                $.ajax({
                    url: `/applicant/${applicantId}/details`,
                    method: 'GET',
                    dataType: 'json',
                    timeout: 5000,
                    cache: false,
                    success: function(response) {
                        console.log('AJAX Success:', response);
                        if(response.success && response.data) {
                            displayApplicantDetailsCustom(response.data);
                        } else {
                            $('#customModalContent').html('<div style="color: red; padding: 20px;">Error: Invalid response format</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        $('#customModalContent').html('<div style="color: red; padding: 20px;">Error loading data: ' + error + '</div>');
                    }
                });
                
                return false;
            });
            
            // Close custom modal and handle download
            $(document).on('click', '#closeCustomModal, #customApplicantModal', function(e) {
                if (e.target === this) {
                    $('#customApplicantModal').hide();
                }
            });
            
            // Handle download admission letter button
            $(document).on('click', '#downloadAdmissionLetter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get the current applicant ID
                let currentApplicantId = $(this).data('current-applicant');
                
                if (currentApplicantId) {
                    // Open admission letter download in new tab
                    window.open(`/applicant/${currentApplicantId}/admission-letter`, '_blank');
                } else {
                    alert('Unable to download admission letter. Please try again.');
                }
            });
            
            // Handle Application Type dropdown change
            $(document).on('change', '#applicationTypeSelect', function() {
                let applicantId = $(this).data('applicant-id');
                let newValue = $(this).val();
                let displayValue = $(this).find('option:selected').text();
                
                console.log('Updating application type:', {applicantId, newValue, displayValue});
                
                // Show loading state
                $(this).prop('disabled', true);
                
                $.ajax({
                    url: `/applicant/${applicantId}/update-field`,
                    method: 'POST',
                    data: {
                        field: 'application_type',
                        value: newValue
                    },
                    success: function(response) {
                        console.log('Update response:', response);
                        if (response.success) {
                            // Show subtle success feedback with green flash
                            let originalBg = $('#applicationTypeSelect').css('background-color');
                            $('#applicationTypeSelect').css('background-color', '#d4edda');
                            setTimeout(function() {
                                $('#applicationTypeSelect').css('background-color', originalBg);
                            }, 1500);
                            
                            // Refresh the DataTable to show updated data
                            if (typeof simpleTable !== 'undefined' && simpleTable) {
                                simpleTable.ajax.reload(null, false);
                            }
                            
                            console.log('Application type updated successfully');
                        } else {
                            // Only show error indication if update actually failed
                            console.error('Update failed:', response);
                            $('#applicationTypeSelect').css('background-color', '#f8d7da');
                            setTimeout(function() {
                                $('#applicationTypeSelect').css('background-color', '');
                            }, 2000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error Details:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusCode: xhr.status,
                            headers: xhr.getAllResponseHeaders()
                        });
                        
                        // Show subtle error indication with red flash
                        $('#applicationTypeSelect').css('background-color', '#f8d7da');
                        setTimeout(function() {
                            $('#applicationTypeSelect').css('background-color', '');
                        }, 2000);
                        
                        console.error('Error updating application type:', error);
                    },
                    complete: function() {
                        $('#applicationTypeSelect').prop('disabled', false);
                    }
                });
            });
            
            // Handle Status dropdown change
            $(document).on('change', '#statusSelect', function() {
                let applicantId = $(this).data('applicant-id');
                let newValue = $(this).val();
                let displayValue = $(this).find('option:selected').text();
                
                console.log('Main delegated event: Updating status:', {applicantId, newValue, displayValue});
                
                // Check if any modal is visible
                const customModalVisible = $('#customApplicantModal').is(':visible');
                const bootstrapModalVisible = $('#applicantModal').is(':visible');
                console.log('Modal visibility check:', {customModalVisible, bootstrapModalVisible});
                
                // Handle admission letter section logic for modal
                if (customModalVisible || bootstrapModalVisible) {
                    console.log('Modal is visible, handling admission letter section');
                    
                    // Always remove existing admission letter section first
                    $('#admissionLetterSection').remove();
                    $('.admission-letter-section').remove();
                    console.log('Removed existing admission letter section');
                    
                    // Only add admission letter section if new status is exactly "Admitted"
                    if (newValue === 'Admitted') {
                        console.log('New status is Admitted, calling updateAdmissionLetterSectionCustom');
                        updateAdmissionLetterSectionCustom(newValue, applicantId);
                    } else {
                        console.log('New status is not Admitted, not adding admission letter section');
                    }
                } else {
                    console.log('No modal is visible, skipping admission letter section logic');
                }
                
                // Show loading state
                $(this).prop('disabled', true);
                
                $.ajax({
                    url: `/applicant/${applicantId}/update-field`,
                    method: 'POST',
                    data: {
                        field: 'status',
                        value: newValue
                    },
                    success: function(response) {
                        console.log('Status update response:', response);
                        if (response.success) {
                            // Show subtle success feedback with green flash
                            let originalBg = $('#statusSelect').css('background-color');
                            $('#statusSelect').css('background-color', '#d4edda');
                            setTimeout(function() {
                                $('#statusSelect').css('background-color', originalBg);
                            }, 1500);
                            
                            // Refresh the DataTable to show updated data
                            if (typeof simpleTable !== 'undefined' && simpleTable) {
                                simpleTable.ajax.reload(null, false);
                            }
                            
                            console.log('Status updated successfully');
                        } else {
                            // Only show error indication if update actually failed
                            console.error('Status update failed:', response);
                            $('#statusSelect').css('background-color', '#f8d7da');
                            setTimeout(function() {
                                $('#statusSelect').css('background-color', '');
                            }, 2000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Status AJAX Error Details:', {
                            status: status,
                            error: error,
                            responseText: xhr.responseText,
                            statusCode: xhr.status,
                            headers: xhr.getAllResponseHeaders()
                        });
                        
                        // Show subtle error indication with red flash
                        $('#statusSelect').css('background-color', '#f8d7da');
                        setTimeout(function() {
                            $('#statusSelect').css('background-color', '');
                        }, 2000);
                        
                        console.error('Error updating status:', error);
                    },
                    complete: function() {
                        $('#statusSelect').prop('disabled', false);
                    }
                });
            });
            
            // Simple modal close handler
            $('#applicantModal').on('hidden.bs.modal', function () {
                console.log('Modal closed');
                $(this).find('.modal-body').empty();
            });
            
            function displayApplicantDetails(applicant) {
                console.log('Displaying applicant details:', applicant);
                
                // Helper function to get status badge with appropriate color
                function getStatusBadge(status) {
                    if (!status) return '<span class="badge bg-secondary">N/A</span>';
                    
                    let badgeClass = 'bg-secondary';
                    switch(status.toLowerCase()) {
                        case 'admitted':
                            badgeClass = 'bg-success';
                            break;
                        case 'pending':
                            badgeClass = 'bg-warning';
                            break;
                        case 'rejected':
                            badgeClass = 'bg-danger';
                            break;
                        case 'approved':
                            badgeClass = 'bg-info';
                            break;
                    }
                    return `<span class="badge ${badgeClass}">${status}</span>`;
                }
                
                try {
                    let modalContent = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">Personal Information</h6>
                                <table class="table table-sm">
                                    <tr><td><strong>Full Name:</strong></td><td>${applicant.full_name || 'N/A'}</td></tr>
                                    <tr><td><strong>Email:</strong></td><td>${applicant.email || 'N/A'}</td></tr>
                                    <tr><td><strong>Phone:</strong></td><td>${applicant.phone || 'N/A'}</td></tr>
                                    <tr><td><strong>Application ID:</strong></td><td>${applicant.application_id || 'N/A'}</td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-success mb-3">Application Details</h6>
                                <table class="table table-sm">
                                    <tr><td><strong>Application Type:</strong></td><td>${applicant.application_type || 'N/A'}</td></tr>
                                    <tr><td><strong>Status:</strong></td><td>
                                        <select class="form-select form-select-sm" id="statusSelect" data-applicant-id="${applicant.id}">
                                            <option value="Pending" ${applicant.status === 'Pending' ? 'selected' : ''}>Pending</option>
                                            <option value="Admitted" ${applicant.status === 'Admitted' ? 'selected' : ''}>Admitted</option>
                                        </select>
                                    </td></tr>
                                    <tr><td><strong>Submitted:</strong></td><td>${applicant.created_at || 'N/A'}</td></tr>
                                </table>
                            </div>
                        </div>
                    `;
                    
                    // Add O'Level results if available
                    if (applicant.o_level_results && applicant.o_level_results.length > 0) {
                        modalContent += `
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6 class="text-success mb-3">O'Level Results</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Grade</th>
                                                <th>Year</th>
                                            </tr>
                                        </thead>
                                        <tbody>`;
                        
                        applicant.o_level_results.forEach(function(result) {
                            modalContent += `
                                            <tr>
                                                <td>${result.subject || 'N/A'}</td>
                                                <td>${result.grade || 'N/A'}</td>
                                                <td>${result.year || 'N/A'}</td>
                                            </tr>`;
                        });
                        
                        modalContent += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>`;
                    } else {
                        modalContent += '<div class="mt-3"><p class="text-muted">No O\'Level results available.</p></div>';
                    }
                    
                    // Do NOT add admission letter section initially - it will be added dynamically
                    
                    $('#applicantModal .modal-body').html(modalContent);
                    console.log('Modal content updated successfully');
                    
                    // Event listener is handled by main delegated event at document level
                    // No need for additional event listeners here as they conflict with the main handler
                    
                    // Check initial status and add admission letter section if status is "Admitted"
                    // Remove any existing admission letter sections first
                    $('#admissionLetterSection').remove();
                    $('.admission-letter-section').remove();
                    
                    // Add admission letter section if status is exactly "Admitted"
                    if (applicant.status && applicant.status.trim() === 'Admitted') {
                        updateAdmissionLetterSection('Admitted', applicant.id);
                    }
                    
                } catch (error) {
                    console.error('Error in displayApplicantDetails:', error);
                    $('#applicantModal .modal-body').html('<div class="alert alert-danger">Error displaying applicant details.</div>');
                }
            }
            
            // Function to update admission letter section based on status
            function updateAdmissionLetterSection(status, applicantId) {
                // Remove existing admission letter section
                $('.admission-letter-section').remove();
                
                // Add admission letter section if status is "Admitted"
                if (status === 'Admitted') {
                    const admissionSection = `
                        <div class="row mt-4 admission-letter-section">
                            <div class="col-12">
                                <div class="alert alert-success">
                                    <h6 class="alert-heading"><i class="fas fa-graduation-cap"></i> Congratulations!</h6>
                                    <p class="mb-3">Your application has been approved. You can now download your admission letter.</p>
                                    <a href="/applicant/${applicantId}/download-admission-letter" 
                                       class="btn btn-success" 
                                       target="_blank">
                                        <i class="fas fa-download"></i> Download Admission Letter
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    $('#applicantModal .modal-body').append(admissionSection);
                }
            }
            
            // Function to update applicant status in database
            function updateApplicantStatus(applicantId, status) {
                console.log('updateApplicantStatus called with applicantId:', applicantId, 'status:', status);
                
                $.ajax({
                    url: '/applicants/' + applicantId + '/update-status',
                    method: 'POST',
                    data: {
                        status: status,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('AJAX success response:', response);
                        if (response.success) {
                            console.log('Status updated successfully in database');
                            // Refresh the DataTable to reflect changes
                            if ($.fn.DataTable.isDataTable('#applicants-table')) {
                                $('#applicants-table').DataTable().ajax.reload(null, false);
                                console.log('DataTable reloaded after status update');
                            }
                        } else {
                            console.error('Status update failed:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating status:', error);
                    }
                });
            }
            
            // Custom modal display function (no Bootstrap dependencies)
            function displayApplicantDetailsCustom(applicant) {
                console.log('Displaying applicant details in custom modal:', applicant);
                
                let content = `
                    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                        <div style="flex: 1; min-width: 300px;">
                            <h6 style="color: #28a745; margin-bottom: 15px; border-bottom: 2px solid #28a745; padding-bottom: 5px;">Personal Information</h6>
                            <table style="width: 100%; border-collapse: collapse;">`;
                
                // Only show fields that have values
                if (applicant.full_name) {
                    content += `<tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Full Name:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">${applicant.full_name}</td></tr>`;
                }
                if (applicant.email) {
                    content += `<tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Email:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">${applicant.email}</td></tr>`;
                }
                if (applicant.phone) {
                    content += `<tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Phone:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">${applicant.phone}</td></tr>`;
                }
                if (applicant.application_id) {
                    content += `<tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Application ID:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">${applicant.application_id}</td></tr>`;
                }
                
                content += `
                            </table>
                        </div>
                        <div style="flex: 1; min-width: 300px;">
                            <h6 style="color: #28a745; margin-bottom: 15px; border-bottom: 2px solid #28a745; padding-bottom: 5px;">Application Details</h6>
                            <table style="width: 100%; border-collapse: collapse;">`;
                
                // Application Type dropdown
                if (applicant.application_type) {
                    content += `
                        <tr>
                            <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Application Type:</strong></td>
                            <td style="padding: 8px; border-bottom: 1px solid #eee;">
                                <select id="applicationTypeSelect" data-applicant-id="${applicant.id}" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                    <option value="">-- Select Application Type --</option>
                                    <option value="Matric Arts" ${applicant.application_type === 'Matric Arts' ? 'selected' : ''}>Matric Arts</option>
                                    <option value="Matric Arabic & Islamic Studies" ${applicant.application_type === 'Matric Arabic & Islamic Studies' ? 'selected' : ''}>Matric Arabic & Islamic Studies</option>
                                    <option value="Matric Management Sciences" ${applicant.application_type === 'Matric Management Sciences' ? 'selected' : ''}>Matric Management Sciences</option>
                                    <option value="Matric Science" ${applicant.application_type === 'Matric Science' ? 'selected' : ''}>Matric Science</option>
                                    <option value="Matric Social Sciences" ${applicant.application_type === 'Matric Social Sciences' ? 'selected' : ''}>Matric Social Sciences</option>
                                    <option value="Matric Law" ${applicant.application_type === 'Matric Law' ? 'selected' : ''}>Matric Law</option>
                                    <option value="Remedial Arts" ${applicant.application_type === 'Remedial Arts' ? 'selected' : ''}>Remedial Arts</option>
                                    <option value="Remedial Arabic & Islamic Studies" ${applicant.application_type === 'Remedial Arabic & Islamic Studies' ? 'selected' : ''}>Remedial Arabic & Islamic Studies</option>
                                    <option value="Remedial Management Sciences" ${applicant.application_type === 'Remedial Management Sciences' ? 'selected' : ''}>Remedial Management Sciences</option>
                                    <option value="Remedial Science" ${applicant.application_type === 'Remedial Science' ? 'selected' : ''}>Remedial Science</option>
                                    <option value="Remedial Social Sciences" ${applicant.application_type === 'Remedial Social Sciences' ? 'selected' : ''}>Remedial Social Sciences</option>
                                    <option value="Remedial Law" ${applicant.application_type === 'Remedial Law' ? 'selected' : ''}>Remedial Law</option>
                                    <option value="JAMB Training Science" ${applicant.application_type === 'JAMB Training Science' ? 'selected' : ''}>JAMB Training Science</option>
                                    <option value="JAMB Training Accounting" ${applicant.application_type === 'JAMB Training Accounting' ? 'selected' : ''}>JAMB Training Accounting</option>
                                    <option value="JAMB Training Economics" ${applicant.application_type === 'JAMB Training Economics' ? 'selected' : ''}>JAMB Training Economics</option>
                                    <option value="JAMB Training Political Science" ${applicant.application_type === 'JAMB Training Political Science' ? 'selected' : ''}>JAMB Training Political Science</option>
                                    <option value="JAMB Training Sociology" ${applicant.application_type === 'JAMB Training Sociology' ? 'selected' : ''}>JAMB Training Sociology</option>
                                    <option value="JAMB Training Business Admin" ${applicant.application_type === 'JAMB Training Business Admin' ? 'selected' : ''}>JAMB Training Business Admin</option>
                                    <option value="JAMB Training Public Admin" ${applicant.application_type === 'JAMB Training Public Admin' ? 'selected' : ''}>JAMB Training Public Admin</option>
                                </select>
                            </td>
                        </tr>`;
                }
                
                // Status dropdown - only Pending and Admitted
                content += `
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Status:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #eee;">
                            <select id="statusSelect" data-applicant-id="${applicant.id}" style="width: 100%; padding: 5px; border: 1px solid #ddd; border-radius: 4px;">
                                <option value="Pending" ${applicant.status === 'Pending' ? 'selected' : ''}>Pending</option>
                                <option value="Admitted" ${applicant.status === 'Admitted' ? 'selected' : ''}>Admitted</option>
                            </select>
                        </td>
                    </tr>`;
                
                if (applicant.created_at) {
                    content += `<tr><td style="padding: 8px; border-bottom: 1px solid #eee;"><strong>Submitted:</strong></td><td style="padding: 8px; border-bottom: 1px solid #eee;">${applicant.created_at}</td></tr>`;
                }
                
                content += `
                            </table>
                        </div>
                    </div>
                `;
                
                // Add O'Level results if available - optimized for 9 subjects
                if (applicant.o_level_results && applicant.o_level_results.length > 0) {
                    content += `
                    <div style="margin-top: 30px;">
                        <h6 style="color: #28a745; margin-bottom: 15px; border-bottom: 2px solid #28a745; padding-bottom: 5px;">O'Level Results (${applicant.o_level_results.length} subjects)</h6>
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                                <thead style="background: #28a745; color: white;">
                                    <tr>
                                        <th style="padding: 12px; border: 1px solid #ddd; text-align: left;">Subject</th>
                                        <th style="padding: 12px; border: 1px solid #ddd; text-align: center;">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    
                    applicant.o_level_results.forEach(function(result, index) {
                        // Alternate row colors for better readability
                        let rowStyle = index % 2 === 0 ? 'background: #f8f9fa;' : 'background: white;';
                        
                        // Only show fields that have values
                        let subject = result.subject || '';
                        let grade = result.grade || '';
                        
                        if (subject || grade) { // Only show row if at least one field has data
                            content += `
                                        <tr style="${rowStyle}">
                                            <td style="padding: 10px; border: 1px solid #ddd; font-weight: 500;">${subject}</td>
                                            <td style="padding: 10px; border: 1px solid #ddd; text-align: center; font-weight: bold; color: #28a745;">${grade}</td>
                                        </tr>`;
                        }
                    });
                    
                    content += `
                                </tbody>
                            </table>
                        </div>
                    </div>`;
                } else {
                    content += '<div style="margin-top: 20px; color: #6c757d; font-style: italic; text-align: center; padding: 20px; background: #f8f9fa; border-radius: 8px;">No O\'Level results available.</div>';
                }
                
                // Do NOT add admission letter section initially - it will be added dynamically when status changes
                
                $('#customModalContent').html(content);
                
                // Event listener is handled by main delegated event at document level
                // No need for additional event listeners here as they conflict with the main handler
                
                // Check initial status and add admission letter section if status is "Admitted"
                // Remove any existing admission letter sections first
                $('#admissionLetterSection').remove();
                $('.admission-letter-section').remove();
                
                console.log('Checking applicant status:', applicant.status);
                console.log('Status trim check:', applicant.status ? applicant.status.trim() : 'null/undefined');
                console.log('Is admitted?', applicant.status && applicant.status.trim() === 'Admitted');
                
                // Add admission letter section if status is exactly "Admitted"
                if (applicant.status && applicant.status.trim() === 'Admitted') {
                    console.log('Adding admission letter section for admitted student');
                    updateAdmissionLetterSectionCustom('Admitted', applicant.id);
                } else {
                    console.log('NOT adding admission letter section');
                }
                
                $('#applicationTypeSelect').off('change').on('change', function() {
                    const newApplicationType = $(this).val();
                    const applicantId = $(this).data('applicant-id');
                    
                    // Update application type in database
                    updateApplicantField(applicantId, 'application_type', newApplicationType);
                });
            }
            
            // Function to update admission letter section for custom modal
            function updateAdmissionLetterSectionCustom(status, applicantId) {
                console.log('updateAdmissionLetterSectionCustom called with status:', status, 'applicantId:', applicantId);
                
                // Remove existing admission letter section
                $('#admissionLetterSection').remove();
                console.log('Removed existing admission letter sections');
                
                // Add admission letter section if status is "Admitted"
                if (status === 'Admitted') {
                    console.log('Status is Admitted, creating admission section');
                    const admissionSection = `
                        <div id="admissionLetterSection" style="margin-top: 30px;">
                            <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 20px;">
                                <h6 style="color: #155724; margin-bottom: 15px; display: flex; align-items: center;">
                                    <i class="fas fa-graduation-cap" style="margin-right: 10px;"></i> Congratulations!
                                </h6>
                                <p style="color: #155724; margin-bottom: 15px;">Your application has been approved. You can now download your admission letter.</p>
                                <a href="/applicant/${applicantId}/download-admission-letter" 
                                   style="display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;"
                                   target="_blank">
                                    <i class="fas fa-download" style="margin-right: 8px;"></i>Download Admission Letter
                                </a>
                            </div>
                        </div>`;
                    console.log('Appending admission section to #customModalContent');
                    $('#customModalContent').append(admissionSection);
                    console.log('Admission section appended successfully');
                    
                    // Verify the section was added
                    const sectionExists = $('#admissionLetterSection').length > 0;
                    console.log('Admission section exists in DOM:', sectionExists);
                    if (sectionExists) {
                        console.log('Admission section HTML preview:', $('#admissionLetterSection')[0].outerHTML.substring(0, 150) + '...');
                    }
                } else {
                    console.log('Status is not Admitted, not adding admission section');
                }
            }
            
            // Function to update applicant field in database
            function updateApplicantField(applicantId, field, value) {
                $.ajax({
                    url: '/applicant/' + applicantId + '/update-field',
                    method: 'POST',
                    data: {
                        field: field,
                        value: value,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Field updated successfully');
                            // Refresh the DataTable to reflect changes
                            if ($.fn.DataTable.isDataTable('#applicants-table')) {
                                $('#applicants-table').DataTable().ajax.reload(null, false);
                            }
                        } else {
                            console.error('Field update failed:', response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating field:', error);
                    }
                });
            }
            
            function getStatusColor(status) {
                switch(status?.toLowerCase()) {
                    case 'approved': return 'success';
                    case 'pending': return 'warning';
                    case 'rejected': return 'danger';
                    default: return 'secondary';
                }
            }
            
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Reinitialize tooltips on table redraw (only if table was initialized successfully)
            if (simpleTable && typeof simpleTable.on === 'function') {
                simpleTable.on('draw', function() {
                    $('[data-bs-toggle="tooltip"]').tooltip();
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
