<x-app-layout :assets="['data-table']">
    <div class="container-fluid px-2 px-md-3">
        <header class="users-page-header">
            <div class="users-page-title">
                <p class="users-eyebrow">Directory</p>
                <h1>Users</h1>
            </div>
            <input type="hidden" id="sessionFilter" value="{{ optional($activeSession)->id }}">
            <div class="users-session-tools">
                <div class="users-session-context">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    <div>
                        <span>Viewing session</span>
                        <strong id="viewingSessionLabel">{{ $activeSession ? $activeSession->label : 'None selected' }}</strong>
                    </div>
                </div>
                <a href="{{ route('admin.registration.index') }}" class="btn btn-light users-change-session">
                    <i class="fas fa-sliders-h me-2" aria-hidden="true"></i>Change session
                </a>
                <div class="users-page-actions">
                    <button type="button" id="deleteSessionUsers" class="btn btn-outline-danger btn-sm" title="Delete all regular users in the selected session">
                        <i class="fas fa-trash-alt me-1" aria-hidden="true"></i>Delete Session Users
                    </button>
                    <a href="{{ route('users.export') }}" id="exportUsers" class="btn btn-outline-success btn-sm" title="Export users in the selected session">
                        <i class="fas fa-download me-1" aria-hidden="true"></i>Export
                    </a>
                    <a href="{{ route('bulk-users.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-user-plus me-1" aria-hidden="true"></i>Generate Bulk Users
                    </a>
                </div>
            </div>
        </header>
        
        <!-- Responsive Table Container -->
        <div class="table-responsive list-table-scroll">
            <table id="users-table" class="table custom-table w-100">
                <thead>
                    <tr>
                        <th class="text-nowrap">S/N</th>
                        <th class="text-nowrap">Matric No</th>
                        <th class="text-nowrap d-none d-md-table-cell">First Name</th>
                        <th class="text-nowrap d-none d-md-table-cell">Last Name</th>
                        <th class="text-nowrap d-md-none">Name</th>
                        <th class="text-nowrap">Email</th>
                        <th class="text-nowrap d-none d-lg-table-cell">Plain Password</th>
                        <th class="text-nowrap">Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <style>
        .users-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin: 18px 0 22px;
            padding: 18px 20px;
            border: 1px solid #dce5e3;
            border-radius: 8px;
            background: #fff;
        }

        .users-page-title { flex: 0 0 auto; }
        .users-eyebrow { margin: 0 0 3px; color: #287668; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .users-page-title h1 { margin: 0; color: #173b35; font-size: 24px; font-weight: 700; }
        .users-session-tools { min-width: 0; display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 10px; }
        .users-session-context { display: flex; align-items: center; gap: 10px; min-width: 150px; padding: 8px 12px; border: 1px solid #e4ece8; border-radius: 6px; background: #f5f8f6; color: #176c59; }
        .users-session-context > div { display: grid; gap: 1px; }
        .users-session-context span { color: #71817d; font-size: 10px; line-height: 1.2; }
        .users-session-context strong { overflow-wrap: anywhere; color: #173b35; font-size: 13px; }
        .users-change-session { border: 1px solid #d8e1df; color: #38554d; white-space: nowrap; }
        .users-page-actions { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
        .users-page-actions .btn { white-space: normal; }

        /* Compact directory table, scoped to the users list. */
        .list-table-scroll {
            border: 1px solid #dce5e3;
            border-radius: 8px;
            overflow-x: auto;
            max-width: 100%;
            background: #fff;
            box-shadow: 0 2px 8px rgba(23, 59, 53, 0.04);
        }

        .list-table-scroll .dataTables_wrapper { padding: 12px; }
        .list-table-scroll .custom-table {
            font-size: 13px;
            margin-bottom: 0;
            min-width: 100%;
            table-layout: auto;
            border-collapse: collapse !important;
        }

        .list-table-scroll .custom-table th,
        .list-table-scroll .custom-table td {
            border: 0;
            border-bottom: 1px solid #e9eeec;
            vertical-align: middle;
            padding: 5px 10px;
            line-height: 1.4;
            white-space: nowrap;
        }

        .list-table-scroll .custom-table thead th {
            padding-top: 9px;
            padding-bottom: 9px;
            background: #f2f6f4;
            color: #38554d;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-bottom-color: #dce5e3;
        }

        .list-table-scroll .custom-table tbody td { color: #354740; }
        .list-table-scroll .custom-table tbody tr:nth-child(even) { background: #fafcfb; }
        .list-table-scroll .custom-table tbody tr:hover { background: #f0f6f3; }
        .list-table-scroll .custom-table tbody tr:last-child td { border-bottom: 0; }
        .list-table-scroll .editable {
            display: inline-block;
            min-width: 70px;
            padding: 2px 4px;
            border-radius: 3px;
            transition: background-color 0.2s ease;
        }
        .list-table-scroll .editable:focus {
            outline: 2px solid #287668;
            background: #f0fff4;
        }
        .list-table-scroll .delete-user {
            width: 28px;
            height: 28px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
        }

        /* Mobile Optimizations */
        @media (max-width: 767.98px) {
            .users-page-header { align-items: stretch; flex-direction: column; gap: 14px; padding: 16px; }
            .users-session-tools { justify-content: flex-start; }
            .users-page-actions { width: 100%; }
            .users-page-actions .btn { flex: 1 1 auto; }
            .list-table-scroll .custom-table {
                font-size: 12px;
            }
            
            .list-table-scroll .custom-table th,
            .list-table-scroll .custom-table td {
                padding: 4px 8px;
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
            .list-table-scroll .custom-table {
                font-size: 13px;
            }
            
            .list-table-scroll .custom-table th,
            .list-table-scroll .custom-table td {
                padding: 5px 10px;
            }
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
                autoWidth: false,
                ajax: {
                    url: '{{ route('users.index') }}',
                    data: function (data) {
                        data.academic_session_id = $('#sessionFilter').val();
                    }
                },
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
                        targets: [6],
                        className: 'd-none d-lg-table-cell',
                        responsivePriority: 3
                    }
                ],
                columns: [
                    { 
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        title: 'S/N'
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
                        data: 'plain_password', name: 'plain_password',
                        render: function (data, type, row) {
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="plain_password">${data ?? ''}</span>`;
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function (data, type, row) {
                            return `<button type="button" class="btn btn-outline-danger btn-sm delete-user" data-id="${row.id}" title="Delete user" aria-label="Delete user">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>`;
                        }
                    },
                ]
            });

            function updateExportLink() {
                const sessionId = $('#sessionFilter').val();
                $('#exportUsers').attr(
                    'href',
                    '{{ route('users.export') }}?academic_session_id=' + encodeURIComponent(sessionId || '')
                );
            }

            updateExportLink();


            $('#exportUsers').on('click', function (event) {
                if (!$('#sessionFilter').val()) {
                    event.preventDefault();
                    alert('Select an academic session before exporting users.');
                }
            });

            $('#deleteSessionUsers').on('click', function () {
                const sessionId = $('#sessionFilter').val();
                const sessionLabel = $('#viewingSessionLabel').text().trim();

                if (!sessionId) {
                    alert('Select an academic session first.');
                    return;
                }

                if (!window.confirm('Delete all regular users for ' + sessionLabel + '? This cannot be undone.')) {
                    return;
                }

                const button = $(this);
                button.prop('disabled', true);
                $.ajax({
                    url: '{{ route('users.destroy-by-session') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE',
                        academic_session_id: sessionId
                    },
                    success: function (response) {
                        showSuccessToast(response.message);
                        table.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        alert(xhr.responseJSON?.message || 'Unable to delete session users.');
                    },
                    complete: function () {
                        button.prop('disabled', false);
                    }
                });
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

            $(document).on('click', '.delete-user', function () {
                const button = $(this);
                const userId = button.data('id');

                if (!window.confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                    return;
                }

                button.prop('disabled', true);
                $.ajax({
                    url: '{{ url('/users') }}/' + userId,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function (response) {
                        showSuccessToast(response.message || 'User deleted successfully.');
                        table.ajax.reload(null, false);
                    },
                    error: function (xhr) {
                        button.prop('disabled', false);
                        alert(xhr.responseJSON?.message || 'Unable to delete user.');
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
