<x-app-layout :assets="['data-table']">
    <br />
    <div class="container-fluid px-2 px-md-3">
        <!-- Responsive Button Container -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 mb-md-4">
            <h4 class="text-success mb-2 mb-sm-0 fs-5 fs-md-4">User Management</h4>
            <a href="{{ route('bulk-users.create') }}" class="btn btn-success btn-sm btn-md-normal">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-1 me-md-2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="m16 11 2 2 4-4"></path>
                </svg>
                <span class="d-none d-sm-inline">Generate Bulk Users</span>
                <span class="d-sm-none">Add Users</span>
            </a>
        </div>
        
        <!-- Responsive Table Container -->
        <div class="table-responsive">
            <table id="users-table" class="table table-bordered table-striped custom-table w-100">
                <thead class="table-success">
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Matric No</th>
                        <th class="text-nowrap d-none d-md-table-cell">First Name</th>
                        <th class="text-nowrap d-none d-md-table-cell">Last Name</th>
                        <th class="text-nowrap d-md-none">Name</th>
                        <th class="text-nowrap">Email</th>
                        <th class="text-nowrap d-none d-lg-table-cell">Phone Number</th>
                        <th class="text-nowrap d-none d-lg-table-cell">Plain Password</th>
                    </tr>
                </thead>
            </table>
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
    </style>

    @push('scripts')
    <script>
        $(function () {
            let table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                scrollCollapse: true,
                ajax: '{{ route('users.index') }}',
                language: {
                    processing: '<div class="d-flex align-items-center"><div class="spinner-border text-success me-2" role="status"></div>Loading...</div>',
                    lengthMenu: '_MENU_',
                    search: '',
                    searchPlaceholder: 'Search users...',
                    paginate: {
                        first: '<i class="fas fa-angle-double-left"></i>',
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>',
                        last: '<i class="fas fa-angle-double-right"></i>'
                    }
                },
                columnDefs: [
                    { 
                        targets: [0], 
                        className: 'text-center',
                        width: '60px' 
                    },
                    { 
                        targets: [1], 
                        width: '120px' 
                    },
                    { 
                        targets: [2, 3], 
                        responsivePriority: 1,
                        className: 'd-none d-md-table-cell'
                    },
                    { 
                        targets: [4], 
                        responsivePriority: 2 
                    },
                    { 
                        targets: [5, 6], 
                        className: 'd-none d-lg-table-cell',
                        responsivePriority: 3
                    }
                ],
                columns: [
                    { 
                        data: 'id', 
                        name: 'id',
                        title: 'ID'
                    },
                    { 
                        data: 'mat_id', 
                        name: 'mat_id',
                        title: 'Matric No'
                    },
                    {
                        data: 'first_name', 
                        name: 'first_name',
                        title: 'First Name',
                        render: function (data, type, row) {
                            if (window.innerWidth < 768) return null;
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="first_name">${data ?? ''}</span>`;
                        }
                    },
                    {
                        data: 'last_name', 
                        name: 'last_name',
                        title: 'Last Name',
                        render: function (data, type, row) {
                            if (window.innerWidth < 768) return null;
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="last_name">${data ?? ''}</span>`;
                        }
                    },
                    {
                        data: null, 
                        name: 'full_name',
                        title: 'Name',
                        className: 'd-md-none',
                        render: function (data, type, row) {
                            if (window.innerWidth >= 768) return null;
                            return `<div class="mobile-name-cell">
                                        <div><strong>${row.first_name || ''} ${row.last_name || ''}</strong></div>
                                        <small class="text-muted">${row.mat_id || ''}</small>
                                    </div>`;
                        }
                    },
                    {
                        data: 'email', name: 'email',
                        render: function (data, type, row) {
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="email">${data ?? ''}</span>`;
                        }
                    },
                    {
                        data: 'phone_number', name: 'phone_number',
                        render: function (data, type, row) {
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="phone_number">${data ?? ''}</span>`;
                        }
                    },
                    {
                        data: 'plain_password', name: 'plain_password',
                        render: function (data, type, row) {
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="plain_password">${data ?? ''}</span>`;
                        }
                    },
                ]
            });

            $(document).on('blur', '.editable', function () {
                let id = $(this).data('id');
                let column = $(this).data('column');
                let value = $(this).text().trim();

                $.ajax({
                    url: '{{ route("users.inline-update") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        column: column,
                        value: value
                    },
                    success: function (response) {
                        if (response.success) {
                            showSuccessToast('Updated successfully!');
                        } else {
                            alert('Update failed: ' + (response.message ?? 'Unknown error'));
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('Error updating! Status: ' + xhr.status);
                    }
                });
            });

            function showSuccessToast(message) {
                $('.success-toast').remove();
                let toast = $('<div class="success-toast">')
                    .text(message)
                    .css({
                        position: 'fixed',
                        top: '20px',
                        right: '20px',
                        background: '#ffffffff',
                        color: '#1c8100ff',
                        padding: '10px 20px',
                        borderRadius: '5px',
                        zIndex: 9999,
                        boxShadow: '0 2px 6px rgba(0,0,0,0.2)'
                    })
                    .hide()
                    .appendTo('body')
                    .fadeIn(300);

                setTimeout(() => {
                    toast.fadeOut(300, () => toast.remove());
                }, 1500);
            }
        });
    </script>
    @endpush
</x-app-layout>
