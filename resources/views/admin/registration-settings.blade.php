<x-app-layout :assets="$assets ?? []">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-success text-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0">
                                <i class="fas fa-cog me-2"></i>Registration Settings
                            </h4>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Current Status Card -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="alert alert-info border-0 rounded-3">
                                    <h5 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Current Registration Status
                                    </h5>
                                    <div class="mt-3">
                                        @if($registrationOpen)
                                            <h3>
                                                <span class="badge bg-success rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i>OPEN
                                                </span>
                                            </h3>
                                            <p class="text-muted mb-0">
                                                Registration is currently <strong>OPEN</strong>. Users can register new accounts.
                                            </p>
                                        @else
                                            <h3>
                                                <span class="badge bg-danger rounded-pill">
                                                    <i class="fas fa-lock me-1"></i>CLOSED
                                                </span>
                                            </h3>
                                            <p class="text-muted mb-0">
                                                Registration is currently <strong>CLOSED</strong>. Only login is allowed.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-2 border-warning rounded-3">
                                    <div class="card-body text-center">
                                        <h6 class="text-warning mb-3">
                                            <i class="fas fa-toggle-off me-2"></i>Quick Toggle
                                        </h6>
                                        <form method="POST" action="{{ route('admin.registration.toggle') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-lg">
                                                @if($registrationOpen)
                                                    <i class="fas fa-lock me-2"></i>Close Registration
                                                @else
                                                    <i class="fas fa-unlock me-2"></i>Open Registration
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Settings Form -->
                        <form method="POST" action="{{ route('admin.registration.update') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-door-open me-2"></i>Registration Status
                                        </label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="registration_open" 
                                                   id="registrationOpen" value="1" 
                                                   {{ $registrationOpen ? 'checked' : '' }}>
                                            <label class="form-check-label" for="registrationOpen">
                                                Allow new user registration
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-lightbulb me-1"></i>
                                            When unchecked, the registration form will not be accessible. Only login will be available.
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="alert alert-info rounded-3 mb-0">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <strong>Note:</strong> Users can still access the login page regardless of this setting.
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label fw-bold" for="closedMessage">
                                            <i class="fas fa-message me-2"></i>Closed Portal Message
                                        </label>
                                        <textarea class="form-control form-control-lg" name="registration_closed_message" 
                                                  id="closedMessage" rows="4" required
                                                  placeholder="Enter the message to display when registration is closed">{{ $closedMessage }}</textarea>
                                        <small class="text-muted d-block mt-2">
                                            This message will be shown to users when they try to access registration and the portal is closed.
                                        </small>
                                        @error('registration_closed_message')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save me-2"></i>Save Settings
                                    </button>
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg ms-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                </div>
                            </div>
                        </form>

                        <hr class="my-4">

                        <!-- Information Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">
                                            <i class="fas fa-circle-info me-2 text-info"></i>How This Works
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <ul class="list-unstyled">
                                                    <li class="mb-2">
                                                        <i class="fas fa-check text-success me-2"></i>
                                                        <strong>Registration OPEN:</strong> Users can register and complete the full registration process.
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fas fa-lock text-danger me-2"></i>
                                                        <strong>Registration CLOSED:</strong> Users see the closed message and cannot register.
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="list-unstyled">
                                                    <li class="mb-2">
                                                        <i class="fas fa-sign-in-alt text-primary me-2"></i>
                                                        <strong>Login Available:</strong> Login remains available regardless of registration status.
                                                    </li>
                                                    <li class="mb-2">
                                                        <i class="fas fa-toggle-on text-warning me-2"></i>
                                                        <strong>Quick Toggle:</strong> Use the toggle button above to quickly switch status.
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
