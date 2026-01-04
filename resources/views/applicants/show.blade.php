<x-app-layout>
    <div class="container-fluid px-2 py-3">
        <!-- Navigation -->
        <nav class="mb-2">
            <a href="{{ route('applicants.index') }}" class="btn btn-sm btn-outline-secondary">← Back</a>
        </nav>

        <!-- Slip/Document Container -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="bg-white" style="border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); padding: 20px;">
                    
                    <!-- Document Header -->
                    <div style="text-align: center; border-bottom: 3px solid #333; padding-bottom: 12px; margin-bottom: 15px;">
                        <h5 class="mb-0" style="letter-spacing: 0.5px; font-weight: 800; font-size: 14px;">APPLICANT INFORMATION</h5>
                    </div>

                    <!-- Photo Section (if available) -->
                    @if($applicant->passport || $applicant->photo)
                    <div style="text-align: center; margin-bottom: 15px;">
                        <img src="{{ asset($applicant->passport ?? $applicant->photo) }}" 
                             alt="Applicant Photo" 
                             class="rounded" 
                             style="width:100px;height:120px;object-fit:cover;border: 1px solid #ddd;">
                    </div>
                    @endif

                    <!-- Main Information Section -->
                    <div style="background-color: #f8f9fa; padding: 10px 12px; border-left: 3px solid #333; margin-bottom: 12px;">
                        <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 8px; font-weight: 700;">FULL NAME:</p>
                        <h6 class="mb-0" style="font-size: 14px; display: inline-block; font-weight: 700; text-transform: capitalize;">{{ strtolower($fullName) }}</h6>
                    </div>

                    <!-- Key Information Row -->
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 5px; font-weight: 700;">MATRIC:</p>
                            <p class="fw-bold" style="font-size: 13px; margin: 0; display: inline-block; text-transform: uppercase;">{{ $applicant->matric_no ?? $applicant->application_id ?? '—' }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 5px; font-weight: 700;">STATUS:</p>
                            <p class="fw-bold" style="font-size: 13px; margin: 0; display: inline-block; text-transform: capitalize;">{{ strtolower($applicant->status ?? 'pending') }}</p>
                        </div>
                    </div>

                    <!-- Separator Line -->
                    <hr style="border: none; border-top: 1px solid #e9ecef; margin: 10px 0;">

                    <!-- Detailed Information -->
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 5px; font-weight: 700;">PHONE:</p>
                            <p style="font-size: 12px; margin: 0; font-weight: 500; display: inline-block;">{{ $applicant->phone ?? '—' }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 5px; font-weight: 700;">GENDER:</p>
                            <p style="font-size: 12px; margin: 0; font-weight: 500; display: inline-block; text-transform: capitalize;">{{ strtolower($applicant->gender ?? '—') }}</p>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 5px; font-weight: 700;">STATE:</p>
                            <p style="font-size: 12px; margin: 0; font-weight: 500; display: inline-block; text-transform: capitalize;">{{ strtolower($applicant->state ?? '—') }}</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 5px; font-weight: 700;">LGA:</p>
                            <p style="font-size: 12px; margin: 0; font-weight: 500; display: inline-block; text-transform: capitalize;">{{ strtolower($applicant->lga ?? '—') }}</p>
                        </div>
                    </div>

                    <div class="mb-2">
                        <p class="text-muted small" style="font-size: 11px; display: inline-block; margin-right: 10px; font-weight: 700;">PROGRAMME:</p>
                        <p style="font-size: 12px; margin: 0; font-weight: 600; display: inline-block; text-transform: capitalize;">{{ strtolower(str_replace('_', ' ', $applicant->application_type ?? '—')) }}</p>
                    </div>

                    <!-- Separator Line -->
                    <hr style="border: none; border-top: 1px solid #e9ecef; margin: 10px 0;">

                    <!-- Footer with Date -->
                    <div style="text-align: center; font-size: 0.75rem; color: #999; margin-bottom: 10px;">
                        <p class="mb-0">{{ now()->format('d M Y') }} | APP-{{ $applicant->id }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('applicants.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                        <button id="admit-btn" class="btn btn-sm btn-success" data-id="{{ $applicant->id }}">
                            Admit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .fw-500 {
            font-weight: 500;
            color: #333;
        }

        .btn {
            border-radius: 4px;
            font-weight: 500;
            font-size: 12px;
        }

        @media print {
            .btn, nav {
                display: none;
            }
            
            .bg-white {
                box-shadow: none;
                border: 1px solid #ccc;
            }
        }
    </style>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#admit-btn').click(function() {
                const id = $(this).data('id');
                if (!confirm('Are you sure you want to admit this applicant?')) return;
                
                $(this).prop('disabled', true);
                
                $.ajax({
                    url: '/applicants/' + id + '/update-status',
                    type: 'POST',
                    data: JSON.stringify({ status: 'Admitted' }),
                    contentType: 'application/json',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function() {
                        alert('Applicant admitted successfully!');
                        window.location.href = '{{ route('applicants.index') }}';
                    },
                    error: function() {
                        alert('Error admitting applicant');
                        $('#admit-btn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
