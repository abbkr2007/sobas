<x-app-layout :assets="['data-table']">
    <div class="container">
        <h1 class="mb-4 text-success">Users List</h1>
        <a href="{{ route('bulk-users.create') }}" class="btn btn-success mb-3">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="white" class="me-2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="m16 11 2 2 4-4"></path>
            </svg>
            Generate Bulk Users
        </a>
        <table id="users-table" class="table table-bordered table-striped custom-table">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Matric No</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Plain Password</th>
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
            padding: 6px 8px;
        }
        .custom-table thead {
            background-color: #d4edda;
            color: #155724;
        }
        .custom-table tbody tr:hover {
            background-color: #f6fff6;
        }
        .editable {
            display: inline-block;
            min-width: 80px;
            padding: 2px 4px;
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
                ajax: '{{ route('users.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'mat_id', name: 'mat_id' },
                    {
                        data: 'first_name', name: 'first_name',
                        render: function (data, type, row) {
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="first_name">${data ?? ''}</span>`;
                        }
                    },
                    {
                        data: 'last_name', name: 'last_name',
                        render: function (data, type, row) {
                            return `<span class="editable" contenteditable="true" data-id="${row.id}" data-column="last_name">${data ?? ''}</span>`;
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
