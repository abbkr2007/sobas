<x-app-layout :assets="$assets ?? []">
    <div class="container-fluid px-3 px-md-4">
        <div class="application-settings-page">
            <div class="settings-header">
                <div>
                    <p class="settings-eyebrow mb-1">Admin Control</p>
                    <h4 class="settings-title mb-1">Application Portal Settings</h4>
                    <p class="settings-subtitle mb-0">Open or close the application portal and update the closed page message.</p>
                </div>
                <span class="settings-state {{ $registrationOpen ? 'is-open' : 'is-closed' }}">
                    <i class="fas {{ $registrationOpen ? 'fa-check-circle' : 'fa-lock' }} me-2"></i>
                    {{ $registrationOpen ? 'Open' : 'Closed' }}
                </span>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-lg-7">
                    <div class="settings-panel status-panel">
                        <div class="panel-icon {{ $registrationOpen ? 'panel-icon-open' : 'panel-icon-closed' }}">
                            <i class="fas {{ $registrationOpen ? 'fa-door-open' : 'fa-lock' }}"></i>
                        </div>
                        <div class="panel-content">
                            <p class="panel-label mb-1">Current Status</p>
                            <h5 class="panel-title mb-2">
                                Application portal is {{ $registrationOpen ? 'open' : 'closed' }}
                            </h5>
                            <p class="panel-text mb-0">
                                @if($registrationOpen)
                                    New applicants can access the form and submit applications.
                                @else
                                    New applicants will see the closed message. Existing users can still log in.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="settings-panel action-panel">
                        <div>
                            <p class="panel-label mb-1">Quick Toggle</p>
                            <h5 class="panel-title mb-2">
                                {{ $registrationOpen ? 'Close applications' : 'Open applications' }}
                            </h5>
                            <p class="panel-text mb-3">
                                Change the portal status with one click.
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.registration.toggle') }}">
                            @csrf
                            <button type="submit" class="btn {{ $registrationOpen ? 'btn-danger' : 'btn-success' }} btn-lg w-100">
                                @if($registrationOpen)
                                    <i class="fas fa-lock me-2"></i>Close Portal
                                @else
                                    <i class="fas fa-unlock me-2"></i>Open Portal
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.registration.update') }}">
                @csrf

                <div class="settings-panel form-panel mb-4">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <p class="panel-label mb-1">Portal Access</p>
                            <h5 class="panel-title mb-2">Application Availability</h5>
                            <p class="panel-text mb-0">
                                Use this when admissions are ready to open or need to be paused.
                            </p>
                        </div>

                        <div class="col-lg-8">
                            <input type="hidden" name="registration_open" value="0">
                            <div class="access-switch mb-4">
                                <div>
                                    <label class="form-label fw-bold mb-1" for="registrationOpen">Allow New Applications</label>
                                    <p class="text-muted small mb-0">Turn this off to show the closed page.</p>
                                </div>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" name="registration_open"
                                           id="registrationOpen" value="1"
                                           {{ $registrationOpen ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label fw-bold" for="closedMessage">Closed Page Message</label>
                                <textarea class="form-control" name="registration_closed_message"
                                          id="closedMessage" rows="4" required>{{ $closedMessage }}</textarea>
                                @error('registration_closed_message')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-actions">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .application-settings-page {
            max-width: 1180px;
            margin: 0 auto;
            padding: 18px 0 28px;
        }

        .settings-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            padding: 22px 24px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #e8edf3;
            box-shadow: 0 8px 22px rgba(17, 24, 39, 0.06);
        }

        .settings-eyebrow {
            color: #198754;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .settings-title {
            color: #1f2937;
            font-weight: 700;
        }

        .settings-subtitle,
        .panel-text {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .settings-state {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 112px;
            padding: 10px 16px;
            border-radius: 999px;
            font-weight: 700;
            white-space: nowrap;
        }

        .settings-state.is-open {
            color: #0f5132;
            background: #d1e7dd;
        }

        .settings-state.is-closed {
            color: #842029;
            background: #f8d7da;
        }

        .settings-panel {
            height: 100%;
            padding: 24px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #e8edf3;
            box-shadow: 0 8px 22px rgba(17, 24, 39, 0.06);
        }

        .status-panel {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .action-panel {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .panel-icon {
            width: 64px;
            height: 64px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex: 0 0 auto;
        }

        .panel-icon-open {
            color: #198754;
            background: #eaf7ef;
        }

        .panel-icon-closed {
            color: #dc3545;
            background: #fff0f1;
        }

        .panel-label {
            color: #198754;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .panel-title {
            color: #1f2937;
            font-weight: 700;
        }

        .access-switch {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e8edf3;
        }

        .access-switch .form-check-input {
            width: 3.4rem;
            height: 1.8rem;
            cursor: pointer;
        }

        .form-panel textarea {
            border-color: #d8dee8;
            border-radius: 8px;
            resize: vertical;
        }

        .form-panel textarea:focus,
        .access-switch .form-check-input:focus {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
        }

        .settings-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        @media (max-width: 767.98px) {
            .settings-header,
            .status-panel,
            .access-switch {
                align-items: flex-start;
                flex-direction: column;
            }

            .settings-state {
                min-width: auto;
            }

            .settings-panel {
                padding: 20px;
            }
        }
    </style>
</x-app-layout>
