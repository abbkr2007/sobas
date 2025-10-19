    @push('scripts')
    <script>
        $(function () {
            console.log('Page loaded successfully');
            
            // Initialize DataTable
            let table = $('#applicants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('applicants.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'name', name: 'name'},
                    {data: 'mat_id', name: 'mat_id'},
                    {data: 'application_type', name: 'application_type'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[0, 'desc']],
                pageLength: 25
            });
        });

        // Simple download function
        function downloadSlip(id) {
            window.open(`/applications/${id}/acknowledgment`, '_blank');
        }
    </script>
    @endpush
</x-app-layout>