<x-app-layout :assets="$assets ?? []">
    <!-- Professional Dashboard Wrapper -->
    <section class="professional-dashboard-wrapper">
        <!-- Main Content Section -->
        <section class="main-content-section">
            <div class="container-fluid px-3 px-md-4">
                <div class="professional-form-container">
                 @if(session('success'))
                    <!-- Success State with Enhanced Design -->
                    <div class="success-state-container">
                        <div class="success-card">
                            <div class="success-icon">
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            </div>
                            <h3 class="success-title">Application Submitted Successfully!</h3>
                            <p class="success-message">Your application has been received and is being processed.</p>
                            <div class="success-actions">
                                <a href="{{ route('applications.show', session('application_id')) }}" target="_blank" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-print me-2"></i>Download Bio-Data Form
                                </a>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Auto-redirect with notification
                        setTimeout(() => {
                            window.open("{{ route('applications.show', session('application_id')) }}", "_blank");
                        }, 2000);
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 6000);
                    </script>

                @elseif($hasSubmitted)
                    <!-- Already Submitted State -->
                    <div class="submitted-state-container text-center">
                        <div class="submitted-card mx-auto">
                            <div class="submitted-icon">
                                <i class="fas fa-clipboard-check fa-4x text-success"></i>
                            </div>
                            <h3 class="submitted-title">Application Already Submitted</h3>
                            <p class="submitted-message">You have already submitted your application. You can download your Biodata Slip below.</p>
                            <div class="submitted-actions">
                                <a href="{{ route('applications.show', $application->id) }}" target="_blank" 
                                   class="btn btn-success btn-lg">
                                    <i class="fas fa-file-alt me-2"></i>
                                    </i>Download Biodata Slip
                                </a>
                            </div>
                        </div>
                    </div>
                
                @elseif(auth()->user()->user_type == 'admin')
                    <!-- Admin Welcome State -->
                    <div class="admin-welcome-container text-center">
                        <div class="admin-welcome-card mx-auto">
                            <div class="admin-welcome-icon">
                                <i class="fas fa-user-shield fa-4x text-primary"></i>
                            </div>
                            <h3 class="admin-welcome-title">Welcome back, Admin!</h3>
                            <p class="admin-welcome-message">You have administrative access to the SOBAS system. Use the navigation menu to manage applications and system settings.</p>
                            <div class="admin-stats-summary">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stat-box">
                                            <h4>{{ \App\Models\Application::count() }}</h4>
                                            <p>Total Applications</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-box">
                                            <h4>{{ \App\Models\Application::whereDate('created_at', today())->count() }}</h4>
                                            <p>Today's Applications</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-box">
                                            <h4>{{ \App\Models\User::count() }}</h4>
                                            <p>Total Users</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

              @else
                    <!-- Professional Application Form -->
                    <div class="application-form-wrapper">
                        <!-- Form Header -->
                        <div class="form-header">
                            <div class="form-header-content">
                                <div class="form-icon">
                                    <i class="fas fa-edit fa-2x text-success"></i>
                                </div>
                                <div class="form-title-section">
                                    <h2 class="form-title">Student Application Form</h2>
                                    <p class="form-subtitle">Please complete all sections accurately. Your information will be reviewed for admission.</p>
                                </div>
                            </div>
                            
                            <!-- Enhanced Progress Indicator -->
                            <div class="progress-container">
                                <div class="progress-header">
                                    <span class="progress-label">Application Progress</span>
                                    <span class="progress-percentage" id="progressPercentage">25%</span>
                                </div>
                                <div class="progress-bar-wrapper">
                                    <div class="progress-bar-bg">
                                        <div id="progressBar" class="progress-bar-fill" style="width: 25%;"></div>
                                    </div>
                                </div>
                                <div class="progress-steps">
                                    <div class="step-indicator active">
                                        <div class="step-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span>Personal</span>
                                    </div>
                                    <div class="step-indicator">
                                        <div class="step-icon">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <span>Academic</span>
                                    </div>
                                    <div class="step-indicator">
                                        <div class="step-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <span>Review</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- Form -->
    
                    <form method="POST" action="{{ route('application.submit') }}" enctype="multipart/form-data" id="applicationForm">
                        @csrf

                        <!-- Step 1: Personal Information -->
                        <div class="step-content active" id="step-1">
                            <div class="step-card">
                                <div class="step-body">
                                    <!-- Enhanced Photo Upload Section -->
                                    <div class="photo-upload-section">
                                        <div class="photo-upload-container">
                                            <div class="photo-preview-wrapper">
                                                <div class="photo-preview-circle">
                                                    <img id="photoPreview" src="{{ asset('images/person.png') }}" 
                                                         alt="Photo Preview" class="photo-preview-img">
                                                    <div class="photo-overlay">
                                                        <i class="fas fa-camera fa-2x"></i>
                                                        <span>Upload Photo</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="photo-upload-controls">
                                                <label for="photoInput" class="photo-upload-btn">
                                                    <i class="fas fa-upload me-2"></i>Choose Photo
                                                </label>
                                                <input type="file" id="photoInput" name="photo" accept="image/*"
                                                       class="photo-input-hidden" onchange="previewPhoto(event)" required>
                                                <div class="photo-requirements">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        JPG, PNG max 2MB. Passport size recommended.
                                                    </small>
                                                </div>
                                                @error('photo')<div class="error-message">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Professional Form Fields -->
                                    <div class="form-sections">
                                        <!-- Basic Information Section -->
                                        <div class="form-section">
                                            <div class="section-header">
                                                <h4><i class="fas fa-id-card me-2"></i>Basic Information</h4>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Matriculation Number</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-hashtag input-icon"></i>
                                                            <input type="text" name="application_id" 
                                                                   class="form-control" 
                                                                   value="{{ auth()->user()->mat_id }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Surname <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-user input-icon"></i>
                                                            <input type="text" name="surname" class="form-control" 
                                                                   value="{{ auth()->user()->last_name }}" required>
                                                        </div>
                                                        @error('surname')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">First Name <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-user input-icon"></i>
                                                            <input type="text" name="firstname" class="form-control" 
                                                                   value="{{ auth()->user()->first_name }}" required>
                                                        </div>
                                                        @error('firstname')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row g-3 mt-2">
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Middle Name</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-user input-icon"></i>
                                                            <input type="text" name="middlename" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Date of Birth <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-calendar input-icon"></i>
                                                            <input type="date" name="dob" class="form-control" required>
                                                        </div>
                                                        @error('dob')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <div class="form-group">
                                                        <label class="form-label">Gender <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-venus-mars input-icon"></i>
                                                            <select name="gender" class="form-control" required>
                                                                <option value="">-- Select Gender --</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                        @error('gender')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Information Section -->
                                        <div class="form-section">
                                            <div class="section-header">
                                                <h4><i class="fas fa-phone me-2"></i>Contact Information</h4>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Phone Number <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-phone input-icon"></i>
                                                            <input type="text" name="phone" class="form-control" required>
                                                        </div>
                                                        @error('phone')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Email Address <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-envelope input-icon"></i>
                                                            <input type="email" name="email" class="form-control" 
                                                                   value="{{ auth()->user()->email }}" required>
                                                        </div>
                                                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Place of Birth</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-map-marker-alt input-icon"></i>
                                                            <input type="text" name="place_of_birth" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Location Information Section -->
                                        <div class="form-section">
                                            <div class="section-header">
                                                <h4><i class="fas fa-map me-2"></i>Location Information</h4>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">State <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-flag input-icon"></i>
                                                            <select name="state" id="state" class="form-control" required onchange="populateLGA()">
                                                                <option value="">-- Select State --</option>
                                                                <option value="Abia">Abia</option>
                                                                <option value="Adamawa">Adamawa</option>
                                                                <option value="Akwa Ibom">Akwa Ibom</option>
                                                                <option value="Anambra">Anambra</option>
                                                                <option value="Bauchi">Bauchi</option>
                                                                <option value="Bayelsa">Bayelsa</option>
                                                                <option value="Benue">Benue</option>
                                                                <option value="Borno">Borno</option>
                                                                <option value="Cross River">Cross River</option>
                                                                <option value="Delta">Delta</option>
                                                                <option value="Ebonyi">Ebonyi</option>
                                                                <option value="Edo">Edo</option>
                                                                <option value="Ekiti">Ekiti</option>
                                                                <option value="Enugu">Enugu</option>
                                                                <option value="FCT">FCT</option>
                                                                <option value="Gombe">Gombe</option>
                                                                <option value="Imo">Imo</option>
                                                                <option value="Jigawa">Jigawa</option>
                                                                <option value="Kaduna">Kaduna</option>
                                                                <option value="Kano">Kano</option>
                                                                <option value="Katsina">Katsina</option>
                                                                <option value="Kebbi">Kebbi</option>
                                                                <option value="Kogi">Kogi</option>
                                                                <option value="Kwara">Kwara</option>
                                                                <option value="Lagos">Lagos</option>
                                                                <option value="Nasarawa">Nasarawa</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Ogun">Ogun</option>
                                                                <option value="Ondo">Ondo</option>
                                                                <option value="Osun">Osun</option>
                                                                <option value="Oyo">Oyo</option>
                                                                <option value="Plateau">Plateau</option>
                                                                <option value="Rivers">Rivers</option>
                                                                <option value="Sokoto">Sokoto</option>
                                                                <option value="Taraba">Taraba</option>
                                                                <option value="Yobe">Yobe</option>
                                                                <option value="Zamfara">Zamfara</option>
                                                            </select>
                                                        </div>
                                                        @error('state')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">LGA <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-map-pin input-icon"></i>
                                                            <select name="lga" id="lga" class="form-control" required>
                                                                <option value="">-- Select State First --</option>
                                                            </select>
                                                        </div>
                                                        @error('lga')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Town</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-city input-icon"></i>
                                                            <input type="text" name="town" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Country <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-globe input-icon"></i>
                                                            <input type="text" name="country" class="form-control" value="Nigeria" required>
                                                        </div>
                                                        @error('country')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Home Address</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-home input-icon"></i>
                                                            <input type="text" name="home_address" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Guardian Information Section -->
                                        <div class="form-section">
                                            <div class="section-header">
                                                <h4><i class="fas fa-users me-2"></i>Guardian Information</h4>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Guardian Name</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-user-shield input-icon"></i>
                                                            <input type="text" name="guardian" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Guardian Phone</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-phone input-icon"></i>
                                                            <input type="text" name="guardian_phone" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Guardian Address</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-map-marker-alt input-icon"></i>
                                                            <input type="text" name="guardian_address" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Application Type Section -->
                                        <div class="form-section">
                                            <div class="section-header">
                                                <h4><i class="fas fa-graduation-cap me-2"></i>Application Information</h4>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Application Type <span class="required">*</span></label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-scroll input-icon"></i>
                                                            <select name="application_type" class="form-control" required>
                                                                <option value="">-- Select Application Type --</option>
                                                                <option value="Matric Arts">Matric Arts</option>
                                                                <option value="Matric Arabic & Islamic Studies">Matric Arabic & Islamic Studies</option>
                                                                <option value="Matric Management Sciences">Matric Management Sciences</option>
                                                                <option value="Matric Science">Matric Science</option>
                                                                <option value="Matric Social Sciences">Matric Social Sciences</option>
                                                                <option value="Matric Law">Matric Law</option>
                                                                <option value="Remedial Arts">Remedial Arts</option>
                                                                <option value="Remedial Arabic & Islamic Studies">Remedial Arabic & Islamic Studies</option>
                                                                <option value="Remedial Management Sciences">Remedial Management Sciences</option>
                                                                <option value="Remedial Science">Remedial Science</option>
                                                                <option value="Remedial Social Sciences">Remedial Social Sciences</option>
                                                                <option value="Remedial Law">Remedial Law</option>
                                                                <option value="JAMB Training Science">JAMB Training Science</option>
                                                                <option value="JAMB Training Accounting">JAMB Training Accounting</option>
                                                                <option value="JAMB Training Economics">JAMB Training Economics</option>
                                                                <option value="JAMB Training Political Science">JAMB Training Political Science</option>
                                                                <option value="JAMB Training Sociology">JAMB Training Sociology</option>
                                                                <option value="JAMB Training Business Admin">JAMB Training Business Admin</option>
                                                                <option value="JAMB Training Public Admin">JAMB Training Public Admin</option>
                                                            </select>
                                                        </div>
                                                        @error('application_type')<div class="error-message">{{ $message }}</div>@enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    

                        <!-- Step 2: Academic Records & Documents -->
                        <div class="step-content" id="step-2">
                            <div class="step-card">
                                <div class="step-body">
                                    <!-- Schools Attended Section -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <h4><i class="fas fa-school me-2"></i>Schools Attended</h4>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">School Name <span class="required">*</span></label>
                                                    <div class="input-wrapper">
                                                        <i class="fas fa-building input-icon"></i>
                                                        <input type="text" name="school_name[]" 
                                                               class="form-control" 
                                                               placeholder="Enter school name" required>
                                                    </div>
                                                    @error('school_name.*')<div class="error-message">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">From Year <span class="required">*</span></label>
                                                    <div class="input-wrapper">
                                                        <i class="fas fa-calendar input-icon"></i>
                                                        <select name="school_from[]" class="form-control year-select" required>
                                                            <option value="">-- Select Year --</option>
                                                            @for($year = date('Y'); $year >= 1960; $year--)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    @error('school_from.*')<div class="error-message">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">To Year <span class="required">*</span></label>
                                                    <div class="input-wrapper">
                                                        <i class="fas fa-calendar input-icon"></i>
                                                        <select name="school_to[]" class="form-control year-select" required>
                                                            <option value="">-- Select Year --</option>
                                                            @for($year = date('Y'); $year >= 1960; $year--)
                                                                <option value="{{ $year }}">{{ $year }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    @error('school_to.*')<div class="error-message">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- O'Level Results Section -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <h4><i class="fas fa-certificate me-2"></i>O'Level Results</h4>
                                        </div>
                                        
                                        <div class="olevel-container">
                                            <div class="row g-2">
                                                <!-- First Sitting -->
                                                <div class="col-12 col-xl-6">
                                                    <div class="exam-sitting-card">
                                                        <div class="exam-sitting-header">
                                                            <h5><i class="fas fa-file-alt me-2"></i>First Sitting</h5>
                                                        </div>
                                                        
                                                        <div class="exam-details">
                                                            <div class="row g-2">
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Exam Type <span class="required">*</span></label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-clipboard input-icon"></i>
                                                                            <select name="first_exam_type" class="form-control" required>
                                                                                <option value="">-- Select Exam Type --</option>
                                                                                <option value="WAEC">WAEC</option>
                                                                                <option value="NECO">NECO</option>
                                                                                <option value="GCE">GCE</option>
                                                                            </select>
                                                                        </div>
                                                                        @error('first_exam_type')<div class="error-message">{{ $message }}</div>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Exam Year <span class="required">*</span></label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-calendar input-icon"></i>
                                                                            <select name="first_exam_year" class="form-control year-select" required>
                                                                                <option value="">-- Select Year --</option>
                                                                                @for($year = date('Y'); $year >= 1980; $year--)
                                                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                        @error('first_exam_year')<div class="error-message">{{ $message }}</div>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Exam Number <span class="required">*</span></label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-hashtag input-icon"></i>
                                                                            <input type="text" name="first_exam_number" 
                                                                                   class="form-control" 
                                                                                   placeholder="e.g. 12345678" required>
                                                                        </div>
                                                                        @error('first_exam_number')<div class="error-message">{{ $message }}</div>@enderror
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Center Number <span class="required">*</span></label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-map-marker-alt input-icon"></i>
                                                                            <input type="text" name="first_center_number" 
                                                                                   class="form-control" 
                                                                                   placeholder="e.g. 12345" required>
                                                                        </div>
                                                                        @error('first_center_number')<div class="error-message">{{ $message }}</div>@enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Subjects Table -->
                                                        <div class="subjects-table-container">
                                                            <div class="table-responsive">
                                                                <table class="table subjects-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="70%">Subject</th>
                                                                            <th width="30%">Grade</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @for ($i = 1; $i <= 9; $i++)
                                                                        <tr>
                                                                            <td>
                                                                                <select name="first_subject[]" class="form-control" required>
                                                                                    <option value="">-- Select Subject --</option>
                                                                                    <option value="Arabic">Arabic</option>
                                                                                    <option value="Animal Husbandry">Animal Husbandry</option>
                                                                                    <option value="English Language">English Language</option>
                                                                                    <option value="Mathematics">Mathematics</option>
                                                                                    <option value="Biology">Biology</option>
                                                                                    <option value="Physics">Physics</option>
                                                                                    <option value="Chemistry">Chemistry</option>
                                                                                    <option value="Economics">Economics</option>
                                                                                    <option value="Geography">Geography</option>
                                                                                    <option value="Government">Government</option>
                                                                                    <option value="Literature in English">Literature in English</option>
                                                                                    <option value="Commerce">Commerce</option>
                                                                                    <option value="Accounting">Accounting</option>
                                                                                    <option value="Agricultural Science">Agricultural Science</option>
                                                                                    <option value="Civic Education">Civic Education</option>
                                                                                    <option value="Further Mathematics">Further Mathematics</option>
                                                                                    <option value="Christian Religious Studies">Christian Religious Studies</option>
                                                                                    <option value="Islamic Religious Studies">Islamic Religious Studies</option>
                                                                                    <option value="Hausa">Hausa</option>
                                                                                    <option value="Yoruba">Yoruba</option>
                                                                                    <option value="Igbo">Igbo</option>
                                                                                    <option value="Marketing">Marketing</option>
                                                                                    <option value="Data Processing">Data Processing</option>
                                                                                    <option value="Computer Studies">Computer Studies</option>
                                                                                    <option value="History">History</option>
                                                                                    <option value="French">French</option>
                                                                                    <option value="Physical Education">Physical Education</option>
                                                                                    <option value="Technical Drawing">Technical Drawing</option>
                                                                                    <option value="Fine Arts">Fine Arts</option>
                                                                                    <option value="Music">Music</option>
                                                                                    <option value="Home Economics">Home Economics</option>
                                                                                    <option value="Business Studies">Business Studies</option>
                                                                                    <option value="Food and Nutrition">Food and Nutrition</option>
                                                                                    <option value="Fishery">Fishery</option>
                                                                                    <option value="Catering and Craft Practice">Catering and Craft Practice</option>
                                                                                    <option value="Auto Mechanics">Auto Mechanics</option>
                                                                                    <option value="Welding and Fabrication">Welding and Fabrication</option>
                                                                                    <option value="Electrical Installation">Electrical Installation</option>
                                                                                    <option value="Building Construction">Building Construction</option>
                                                                                    <option value="Block Laying and Concreting">Block Laying and Concreting</option>
                                                                                    <option value="Photography">Photography</option>
                                                                                    <option value="Tourism">Tourism</option>
                                                                                    <option value="Garment Making">Garment Making</option>
                                                                                    <option value="Leather Goods Manufacturing">Leather Goods Manufacturing</option>
                                                                                    <option value="Office Practice">Office Practice</option>

                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="first_grade[]" class="form-control" required>
                                                                                    <option value="">-- Grade --</option>
                                                                                    <option value="A1">A1</option>
                                                                                    <option value="B2">B2</option>
                                                                                    <option value="B3">B3</option>
                                                                                    <option value="C4">C4</option>
                                                                                    <option value="C5">C5</option>
                                                                                    <option value="C6">C6</option>
                                                                                    <option value="D7">D7</option>
                                                                                    <option value="E8">E8</option>
                                                                                    <option value="F9">F9</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        @endfor
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            @error('first_subject.*')<div class="error-message">{{ $message }}</div>@enderror
                                                            @error('first_grade.*')<div class="error-message">{{ $message }}</div>@enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Second Sitting -->
                                                <div class="col-12 col-xl-6">
                                                    <div class="exam-sitting-card">
                                                        <div class="exam-sitting-header">
                                                            <h5><i class="fas fa-file-alt me-2"></i>Second Sitting</h5>
                                                            <small class="text-muted">(Optional)</small>
                                                        </div>
                                                        
                                                        <div class="exam-details">
                                                            <div class="row g-2">
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Exam Type</label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-clipboard input-icon"></i>
                                                                            <select name="second_exam_type" class="form-control">
                                                                                <option value="">-- Select Exam Type --</option>
                                                                                <option value="WAEC">WAEC</option>
                                                                                <option value="NECO">NECO</option>
                                                                                <option value="GCE">GCE</option>
                                                                                <option value="NABTEB">NABTEB</option>
                                                                                <option value="NBAIS">NBAIS</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Exam Year</label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-calendar input-icon"></i>
                                                                            <select name="second_exam_year" class="form-control year-select">
                                                                                <option value="">-- Select Year --</option>
                                                                                @for($year = date('Y'); $year >= 1980; $year--)
                                                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                                                @endfor
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Exam Number</label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-hashtag input-icon"></i>
                                                                            <input type="text" name="second_exam_number" 
                                                                                   class="form-control" 
                                                                                   placeholder="e.g. 12345678">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-sm-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Center Number</label>
                                                                        <div class="input-wrapper">
                                                                            <i class="fas fa-map-marker-alt input-icon"></i>
                                                                            <input type="text" name="second_center_number" 
                                                                                   class="form-control" 
                                                                                   placeholder="e.g. 12345">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Subjects Table -->
                                                        <div class="subjects-table-container">
                                                            <div class="table-responsive">
                                                                <table class="table subjects-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th width="70%">Subject</th>
                                                                            <th width="30%">Grade</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @for ($i = 1; $i <= 9; $i++)
                                                                        <tr>
                                                                            <td>
                                                                                <select name="second_subject[]" class="form-control">
                                                                                    <option value="">-- Select Subject --</option>
                                                                                   <option value="Arabic">Arabic</option>
                                                                                    <option value="Animal Husbandry">Animal Husbandry</option>
                                                                                    <option value="English Language">English Language</option>
                                                                                    <option value="Mathematics">Mathematics</option>
                                                                                    <option value="Biology">Biology</option>
                                                                                    <option value="Physics">Physics</option>
                                                                                    <option value="Chemistry">Chemistry</option>
                                                                                    <option value="Economics">Economics</option>
                                                                                    <option value="Geography">Geography</option>
                                                                                    <option value="Government">Government</option>
                                                                                    <option value="Literature in English">Literature in English</option>
                                                                                    <option value="Commerce">Commerce</option>
                                                                                    <option value="Accounting">Accounting</option>
                                                                                    <option value="Agricultural Science">Agricultural Science</option>
                                                                                    <option value="Civic Education">Civic Education</option>
                                                                                    <option value="Further Mathematics">Further Mathematics</option>
                                                                                    <option value="Christian Religious Studies">Christian Religious Studies</option>
                                                                                    <option value="Islamic Religious Studies">Islamic Religious Studies</option>
                                                                                    <option value="Hausa">Hausa</option>
                                                                                    <option value="Yoruba">Yoruba</option>
                                                                                    <option value="Igbo">Igbo</option>
                                                                                    <option value="Marketing">Marketing</option>
                                                                                    <option value="Data Processing">Data Processing</option>
                                                                                    <option value="Computer Studies">Computer Studies</option>
                                                                                    <option value="History">History</option>
                                                                                    <option value="French">French</option>
                                                                                    <option value="Physical Education">Physical Education</option>
                                                                                    <option value="Technical Drawing">Technical Drawing</option>
                                                                                    <option value="Fine Arts">Fine Arts</option>
                                                                                    <option value="Music">Music</option>
                                                                                    <option value="Home Economics">Home Economics</option>
                                                                                    <option value="Business Studies">Business Studies</option>
                                                                                    <option value="Food and Nutrition">Food and Nutrition</option>
                                                                                    <option value="Fishery">Fishery</option>
                                                                                    <option value="Catering and Craft Practice">Catering and Craft Practice</option>
                                                                                    <option value="Auto Mechanics">Auto Mechanics</option>
                                                                                    <option value="Welding and Fabrication">Welding and Fabrication</option>
                                                                                    <option value="Electrical Installation">Electrical Installation</option>
                                                                                    <option value="Building Construction">Building Construction</option>
                                                                                    <option value="Block Laying and Concreting">Block Laying and Concreting</option>
                                                                                    <option value="Photography">Photography</option>
                                                                                    <option value="Tourism">Tourism</option>
                                                                                    <option value="Garment Making">Garment Making</option>
                                                                                    <option value="Leather Goods Manufacturing">Leather Goods Manufacturing</option>
                                                                                    <option value="Office Practice">Office Practice</option>

                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="second_grade[]" class="form-control">
                                                                                    <option value="">-- Grade --</option>
                                                                                    <option value="A1">A1</option>
                                                                                    <option value="B2">B2</option>
                                                                                    <option value="C4">C4</option>
                                                                                    <option value="C5">C5</option>
                                                                                    <option value="C6">C6</option>
                                                                                    <option value="D7">D7</option>
                                                                                    <option value="E8">E8</option>
                                                                                    <option value="F9">F9</option>
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        @endfor
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- JAMB Details Section -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <h4><i class="fas fa-bookmark me-2"></i>JAMB Details</h4>
                                        </div>
                                        <div class="jamb-details-card">
                                            <div class="row g-2">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">JAMB Registration Number</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-hashtag input-icon"></i>
                                                            <input type="text" name="jamb_no" class="form-control" 
                                                                   placeholder="Enter JAMB registration number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">JAMB Score</label>
                                                        <div class="input-wrapper">
                                                            <i class="fas fa-chart-bar input-icon"></i>
                                                            <input type="number" name="jamb_score" class="form-control" 
                                                                   placeholder="Enter your JAMB score" min="0" max="400">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Step 3: Review & Submit -->
                        <div class="step-content" id="step-3">
                            <div class="step-card">
                                <div class="step-header">
                                    <div class="step-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="step-title">
                                        <h3>Review & Submit</h3>
                                        <p>Please review your information before final submission</p>
                                    </div>
                                </div>

                                <div class="step-body">
                                    <div class="review-section">
                                        <div class="review-notice">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Please Review:</strong> Kindly review all your details carefully before final submission. 
                                                Once submitted, you may not be able to edit your application.
                                            </div>
                                        </div>

                                        <div class="form-section">
                                            <div class="section-header">
                                                <h4><i class="fas fa-clipboard-check me-2"></i>Declaration</h4>
                                            </div>
                                            <div class="declaration-content">
                                                <div class="form-check">
                                                    <input type="checkbox" name="declaration" class="form-check-input" 
                                                           id="declaration" required>
                                                    <label class="form-check-label" for="declaration">
                                                        I hereby declare that all information provided in this application is true and accurate to the best of my knowledge. 
                                                        I understand that any false information may lead to the rejection of my application.
                                                    </label>
                                                </div>
                                                @error('declaration')<div class="error-message">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="submit-section">
                                            <button type="submit" class="btn btn-success btn-lg w-100 submit-btn">
                                                <i class="fas fa-paper-plane me-2"></i>
                                                Submit Application
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" id="prevBtn" class="btn btn-outline-success d-none">Previous</button>
                            <button type="button" id="nextBtn" class="btn btn-success">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
         let currentStep = 1;
        const totalSteps = 3;

        function updateProgressBar(step) {
            const progress = (step / totalSteps) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressPercentage').textContent = Math.round(progress) + '%';
            
            // Update step indicators
            document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                if (index < step) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
        }

        function showStep(step) {
            document.querySelectorAll('.step-content').forEach((el, idx) => {
                el.style.display = 'none';
                if (idx === step - 1) el.style.display = 'block';
            });
            document.getElementById('prevBtn').classList.toggle('d-none', step === 1);

            // Update button text
            const nextBtn = document.getElementById('nextBtn');
            if (step === totalSteps) {
                nextBtn.style.display = 'none'; // Hide next button on last step
            } else {
                nextBtn.style.display = 'inline-block';
                nextBtn.innerHTML = '<i class="fas fa-arrow-right me-2"></i>Next Step';
            }

            updateProgressBar(step);
        }

        document.getElementById('nextBtn').addEventListener('click', () => {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                alert('Please fill in all required fields before proceeding.');
            }

            return isValid;
        }

        showStep(currentStep);

        function previewPhoto(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const preview = document.getElementById('photoPreview');
                    if (preview) {
                        preview.src = e.target.result;
                        
                        // Add animation for photo change
                        preview.style.opacity = '0';
                        setTimeout(() => {
                            preview.style.opacity = '1';
                        }, 100);
                    }
                };
                reader.readAsDataURL(file);
                
                // Update upload button text
                const uploadBtn = document.querySelector('.photo-upload-btn');
                if (uploadBtn) {
                    uploadBtn.innerHTML = '<i class="fas fa-check me-2"></i>Photo Selected';
                    uploadBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                }
            }
        }


            // === Nigerian States and LGAs ===
const lgas = {
  "Abia": [
    "Aba North","Aba South","Arochukwu","Bende","Ikwuano","Isiala Ngwa North",
    "Isiala Ngwa South","Isuikwuato","Obi Ngwa","Ohafia","Osisioma Ngwa",
    "Ugwunagbo","Ukwa East","Ukwa West","Umuahia North","Umuahia South","Umunneochi"
  ],

  "Adamawa": [
    "Demsa","Fufore","Ganye","Girei","Gombi","Guyuk","Hong","Jada","Lamurde",
    "Madagali","Maiha","Mayo Belwa","Michika","Mubi North","Mubi South",
    "Numan","Shelleng","Song","Toungo","Yola North","Yola South"
  ],

  "Akwa Ibom": [
    "Abak","Eastern Obolo","Eket","Esit Eket","Essien Udim","Etim Ekpo","Etinan",
    "Ibeno","Ibesikpo Asutan","Ibiono Ibom","Ika","Ikono","Ikot Abasi","Ikot Ekpene",
    "Ini","Itu","Mbo","Mkpat Enin","Nsit Atai","Nsit Ibom","Nsit Ubium",
    "Obot Akara","Okobo","Onna","Oron","Oruk Anam","Udung Uko","Ukanafun",
    "Uruan","Urue-Offong/Oruko","Uyo"
  ],

  "Anambra": [
    "Aguata","Anambra East","Anambra West","Anaocha","Awka North","Awka South",
    "Ayamelum","Dunukofia","Ekwusigo","Idemili North","Idemili South","Ihiala",
    "Njikoka","Nnewi North","Nnewi South","Ogbaru","Onitsha North","Onitsha South",
    "Orumba North","Orumba South","Oyi"
  ],

  "Bauchi": [
    "Alkaleri","Bauchi","Bogoro","Damban","Darazo","Dass","Gamawa","Ganjuwa",
    "Giade","Itas/Gadau","Jama'are","Katagum","Kirfi","Misau","Ningi",
    "Shira","Tafawa Balewa","Toro","Warji","Zaki"
  ],

  "Bayelsa": [
    "Brass","Ekeremor","Kolokuma/Opokuma","Nembe","Ogbia","Sagbama","Southern Ijaw","Yenagoa"
  ],

  "Benue": [
    "Ado","Agatu","Apa","Buruku","Gboko","Guma","Gwer East","Gwer West","Katsina-Ala",
    "Konshisha","Kwande","Logo","Makurdi","Obi","Ogbadibo","Ohimini","Oju",
    "Okpokwu","Oturkpo","Tarka","Ukum","Ushongo","Vandeikya"
  ],

  "Borno": [
    "Abadam","Askira/Uba","Bama","Bayo","Biu","Chibok","Damboa","Dikwa","Gubio",
    "Guzamala","Gwoza","Hawul","Jere","Kaga","Kala/Balge","Konduga","Kukawa",
    "Kwaya Kusar","Mafa","Magumeri","Maiduguri","Marte","Mobbar","Monguno",
    "Ngala","Nganzai","Shani"
  ],

  "Cross River": [
    "Abi","Akamkpa","Akpabuyo","Bakassi","Bekwarra","Biase","Boki","Calabar Municipal",
    "Calabar South","Etung","Ikom","Obanliku","Obubra","Obudu","Odukpani",
    "Ogoja","Yakurr","Yala"
  ],

  "Delta": [
    "Aniocha North","Aniocha South","Bomadi","Burutu","Ethiope East","Ethiope West",
    "Ika North East","Ika South","Isoko North","Isoko South","Ndokwa East","Ndokwa West",
    "Okpe","Oshimili North","Oshimili South","Patani","Sapele","Udu","Ughelli North",
    "Ughelli South","Ukwuani","Uvwie","Warri North","Warri South","Warri South West"
  ],

  "Ebonyi": [
    "Abakaliki","Afikpo North","Afikpo South","Ebonyi","Ezza North","Ezza South",
    "Ikwo","Ishielu","Ivo","Izzi","Ohaozara","Ohaukwu","Onicha"
  ],

  "Edo": [
    "Akoko-Edo","Egor","Esan Central","Esan North-East","Esan South-East",
    "Esan West","Etsako Central","Etsako East","Etsako West","Igueben",
    "Ikpoba-Okha","Oredo","Orhionmwon","Ovia North-East","Ovia South-West",
    "Uhunmwonde"
  ],

  "Ekiti": [
    "Ado Ekiti","Efon","Ekiti East","Ekiti South-West","Ekiti West","Emure",
    "Gbonyin","Ido Osi","Ijero","Ikere","Ikole","Ilejemeje","Irepodun/Ifelodun",
    "Ise/Orun","Moba","Oye"
  ],

  "Enugu": [
    "Aninri","Awgu","Enugu East","Enugu North","Enugu South","Ezeagu","Igbo-Etiti",
    "Igbo-Eze North","Igbo-Eze South","Isi-Uzo","Nkanu East","Nkanu West",
    "Nsukka","Oji River","Udenu","Udi","Uzo-Uwani"
  ],

  "FCT": [
    "Abaji","Bwari","Gwagwalada","Kuje","Kwali","Municipal Area Council"
  ],

  "Gombe": [
    "Akko","Balanga","Billiri","Dukku","Funakaye","Gombe","Kaltungo",
    "Kwami","Nafada","Shongom","Yamaltu/Deba"
  ],

  "Imo": [
    "Aboh Mbaise","Ahiazu Mbaise","Ehime Mbano","Ezinihitte","Ideato North",
    "Ideato South","Ihitte/Uboma","Ikeduru","Isiala Mbano","Isu","Mbaitoli",
    "Ngor Okpala","Njaba","Nkwerre","Nwangele","Obowo","Oguta","Ohaji/Egbema",
    "Okigwe","Orlu","Orsu","Oru East","Oru West","Owerri Municipal","Owerri North","Owerri West","Onuimo"
  ],

  "Jigawa": [
    "Auyo","Babura","Biriniwa","Birnin Kudu","Buji","Dutse","Gagarawa","Garki",
    "Gumel","Guri","Gwaram","Gwiwa","Hadejia","Jahun","Kafin Hausa","Kazaure",
    "Kiri Kasama","Kiyawa","Maigatari","Malam Madori","Miga","Ringim","Roni",
    "Sule Tankarkar","Taura","Yankwashi"
  ],

  "Kaduna": [
    "Birnin Gwari","Chikun","Giwa","Igabi","Ikara","Jaba","Jema'a","Kachia",
    "Kaduna North","Kaduna South","Kagarko","Kajuru","Kaura","Kauru","Kubau",
    "Kudan","Lere","Makarfi","Sabon Gari","Sanga","Soba","Zangon Kataf","Zaria"
  ],

  "Kano": [
    "Ajingi","Albasu","Bagwai","Bebeji","Bichi","Bunkure","Dala","Dambatta","Dawakin Kudu",
    "Dawakin Tofa","Doguwa","Fagge","Gabasawa","Garko","Garun Mallam","Gaya","Gezawa",
    "Gwale","Gwarzo","Kabo","Kano Municipal","Karaye","Kibiya","Kiru","Kumbotso",
    "Kunchi","Kura","Madobi","Makoda","Minjibir","Nasarawa","Rano","Rimin Gado",
    "Rogo","Shanono","Sumaila","Takai","Tarauni","Tofa","Tsanyawa","Tudun Wada",
    "Ungogo","Warawa","Wudil"
  ],

  "Katsina": [
    "Bakori","Batagarawa","Batsari","Baure","Bindawa","Charanchi","Dandume","Danja",
    "Dan Musa","Daura","Dutsi","Dutsin-Ma","Faskari","Funtua","Ingawa","Jibia",
    "Kafur","Kaita","Kankara","Kankia","Katsina","Kurfi","Kusada","Mai'Adua",
    "Malumfashi","Mani","Mashi","Matazu","Musawa","Rimi","Sabuwa","Safana",
    "Sandamu","Zango"
  ],

  "Kebbi": [
    "Aleiro","Arewa Dandi","Argungu","Augie","Bagudo","Birnin Kebbi","Bunza",
    "Dandi","Fakai","Gwandu","Jega","Kalgo","Koko/Besse","Maiyama","Ngaski",
    "Sakaba","Shanga","Suru","Wasagu/Danko","Yauri","Zuru"
  ],

  "Kogi": [
    "Adavi","Ajaokuta","Ankpa","Bassa","Dekina","Ibaji","Idah","Igalamela-Odolu",
    "Ijumu","Kabba/Bunu","Kogi","Koton Karfe","Mopa-Muro","Ofu","Ogori/Magongo",
    "Okehi","Okene","Olamaboro","Omala","Yagba East","Yagba West"
  ],

  "Kwara": [
    "Asa","Baruten","Edu","Ekiti","Ifelodun","Ilorin East","Ilorin South",
    "Ilorin West","Irepodun","Isin","Kaiama","Moro","Offa","Oke Ero","Oyun","Patigi"
  ],

  "Lagos": [
    "Agege","Ajeromi-Ifelodun","Alimosho","Amuwo-Odofin","Apapa","Badagry","Epe",
    "Eti-Osa","Ibeju-Lekki","Ifako-Ijaiye","Ikeja","Ikorodu","Kosofe","Lagos Island",
    "Lagos Mainland","Mushin","Ojo","Oshodi-Isolo","Shomolu","Surulere"
  ],

  "Nasarawa": [
    "Akwanga","Awe","Doma","Karu","Keana","Keffi","Kokona","Lafia","Nasarawa",
    "Nasarawa Egon","Obi","Toto","Wamba"
  ],

  "Niger": [
    "Agaie","Agwara","Bida","Borgu","Bosso","Chanchaga","Edati","Gbako","Gurara",
    "Katcha","Kontagora","Lapai","Lavun","Magama","Mariga","Mashegu","Mokwa",
    "Muya","Paikoro","Rafi","Rijau","Shiroro","Suleja","Tafa","Wushishi"
  ],

  "Ogun": [
    "Abeokuta North","Abeokuta South","Ado-Odo/Ota","Egbado North","Egbado South",
    "Ewekoro","Ifo","Ijebu East","Ijebu North","Ijebu North East","Ijebu Ode",
    "Ikenne","Imeko Afon","Ipokia","Obafemi Owode","Odeda","Odogbolu","Ogun Waterside","Remo North","Sagamu"
  ],

  "Ondo": [
    "Akoko North-East","Akoko North-West","Akoko South-East","Akoko South-West",
    "Akure North","Akure South","Ese Odo","Idanre","Ifedore","Ilaje","Ile Oluji/Okeigbo",
    "Irele","Odigbo","Okitipupa","Ondo East","Ondo West","Ose","Owo"
  ],

  "Osun": [
    "Aiyedaade","Aiyedire","Atakunmosa East","Atakunmosa West","Boluwaduro","Boripe",
    "Ede North","Ede South","Egbedore","Ejigbo","Ife Central","Ife East","Ife North",
    "Ife South","Ifelodun","Ila","Ilesha East","Ilesha West","Irepodun","Irewole",
    "Isokan","Iwo","Obokun","Odo Otin","Ola Oluwa","Olorunda","Oriade","Orolu","Osogbo"
  ],

  "Oyo": [
    "Afijio","Akinyele","Atiba","Atisbo","Egbeda","Ibadan North","Ibadan North-East",
    "Ibadan North-West","Ibadan South-East","Ibadan South-West","Ibarapa Central",
    "Ibarapa East","Ibarapa North","Ido","Irepo","Iseyin","Itesiwaju","Iwajowa",
    "Kajola","Lagelu","Ogbomosho North","Ogbomosho South","Ogo Oluwa","Olorunsogo",
    "Oluyole","Ona Ara","Orelope","Ori Ire","Oyo East","Oyo West","Saki East","Saki West",
    "Surulere"
  ],

  "Plateau": [
    "Barkin Ladi","Bassa","Bokkos","Jos East","Jos North","Jos South","Kanam",
    "Kanke","Langtang North","Langtang South","Mangu","Mikang","Pankshin",
    "Qua'an Pan","Riyom","Shendam","Wase"
  ],

  "Rivers": [
    "Abua–Odual","Ahoada East","Ahoada West","Akuku-Toru","Andoni","Asari-Toru",
    "Bonny","Degema","Eleme","Emohua","Etche","Gokana","Ikwerre","Khana",
    "Obio-Akpor","Ogba–Egbema–Ndoni","Ogu–Bolo","Okrika","Omuma","Opobo/Nkoro",
    "Oyigbo","Port Harcourt","Tai"
  ],

  "Sokoto": [
    "Binji","Bodinga","Dange Shuni","Gada","Goronyo","Gudu","Gwadabawa","Illela",
    "Isa","Kebbe","Kware","Rabah","Sabon Birni","Shagari","Silame","Sokoto North",
    "Sokoto South","Tambuwal","Tangaza","Tureta","Wamakko","Wurno","Yabo"
  ],

  "Taraba": [
    "Ardo Kola","Bali","Donga","Gashaka","Gassol","Ibi","Jalingo","Karim Lamido",
    "Kumi","Lau","Sardauna","Takum","Ussa","Wukari","Yorro","Zing"
  ],

  "Yobe": [
    "Bade","Bursari","Damaturu","Fika","Fune","Geidam","Gujba","Gulani","Jakusko",
    "Karasuwa","Machina","Nangere","Nguru","Potiskum","Tarmuwa","Yunusari","Yusufari"
  ],

  "Zamfara": [
    "Anka","Bakura","Birnin Magaji","Bukkuyum","Bungudu","Gummi","Gusau","Kaura Namoda",
    "Maradun","Maru","Shinkafi","Talata Mafara","Tsafe","Zurmi"
  ]
};

            function populateLGA() {
                const state = document.getElementById('state').value;
                const lgaSelect = document.getElementById('lga');
                lgaSelect.innerHTML = '<option value="">-- Select LGA --</option>';
                if (lgas[state]) {
                    lgas[state].forEach(lga => {
                        const opt = document.createElement('option');
                        opt.value = lga;
                        opt.textContent = lga;
                        lgaSelect.appendChild(opt);
                    });
                }
            }

            // Populate graduation years dynamically
            const gradSelect = document.getElementById('graduation_year');
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= 1960; year--) {
                const opt = document.createElement('option');
                opt.value = year;
                opt.textContent = year;
                gradSelect.appendChild(opt);
            }
        </script>
                    </div>
                </div>
            </div>
        </section>
    </section>

    <!-- Professional Styling -->
    <style>
        /* =================
           PROFESSIONAL DASHBOARD STYLES
           ================= */
        
        .professional-dashboard-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
        }

        /* Main Content Section */
        .main-content-section {
            padding: 2rem 0 3rem 0;
            position: relative;
            z-index: 3;
            padding-bottom: 3rem;
        }

        .professional-form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            padding: 0;
            overflow: hidden;
            position: relative;
        }

        /* Success & Admin States */
        .success-state-container,
        .submitted-state-container,
        .admin-welcome-container {
            padding: 3rem 1.5rem;
            text-align: center;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-card,
        .submitted-card,
        .admin-welcome-card {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            background: white;
            padding: 3rem 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .success-icon,
        .submitted-icon,
        .admin-welcome-icon {
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .success-title,
        .submitted-title,
        .admin-welcome-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
        }

        .success-message,
        .submitted-message,
        .admin-welcome-message {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .submitted-actions .btn {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
        }

        .submitted-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        /* Admin Welcome Styles */
        .admin-welcome-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 1.5rem;
            text-align: center;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .admin-welcome-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="20" cy="80" r="0.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .admin-welcome-card {
            max-width: 800px;
            width: 100%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 4rem 3rem;
            border-radius: 25px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
        }

        .admin-welcome-icon {
            margin-bottom: 2rem;
            position: relative;
        }

        .admin-welcome-icon i {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 2rem;
            border-radius: 50%;
            box-shadow: 0 15px 35px rgba(0, 123, 255, 0.3);
            animation: adminPulse 3s ease-in-out infinite;
        }

        @keyframes adminPulse {
            0%, 100% { 
                transform: scale(1); 
                box-shadow: 0 15px 35px rgba(0, 123, 255, 0.3);
            }
            50% { 
                transform: scale(1.05); 
                box-shadow: 0 20px 45px rgba(0, 123, 255, 0.4);
            }
        }

        .admin-welcome-title {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .admin-welcome-message {
            font-size: 1.3rem;
            color: #495057;
            margin-bottom: 3rem;
            line-height: 1.7;
            font-weight: 400;
        }

        .admin-stats-summary {
            margin-top: 3rem;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.05);
        }

        .admin-stats-summary .row {
            margin: 0;
        }

        .stat-box {
            background: white;
            padding: 2.5rem 1.5rem;
            border-radius: 15px;
            margin-bottom: 1rem;
            border: none;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #007bff 0%, #6f42c1 50%, #e83e8c 100%);
            transition: height 0.3s ease;
        }

        .stat-box:hover::before {
            height: 8px;
        }

        .stat-box:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .stat-box:nth-child(1) .stat-box::before {
            background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        }

        .stat-box:nth-child(2) .stat-box::before {
            background: linear-gradient(90deg, #28a745 0%, #1e7e34 100%);
        }

        .stat-box:nth-child(3) .stat-box::before {
            background: linear-gradient(90deg, #ffc107 0%, #e0a800 100%);
        }

        .stat-box h4 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }

        .stat-box p {
            color: #6c757d;
            margin: 0;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Admin Welcome Responsive Design */
        @media (max-width: 768px) {
            .admin-welcome-container {
                padding: 2rem 1rem;
                min-height: 500px;
            }
            
            .admin-welcome-card {
                padding: 2.5rem 1.5rem;
                border-radius: 20px;
            }
            
            .admin-welcome-icon i {
                padding: 1.5rem;
                font-size: 2.5rem;
            }
            
            .admin-welcome-title {
                font-size: 2rem;
            }
            
            .admin-welcome-message {
                font-size: 1.1rem;
            }
            
            .stat-box {
                padding: 2rem 1rem;
                margin-bottom: 1.5rem;
            }
            
            .stat-box h4 {
                font-size: 2.5rem;
            }
            
            .admin-stats-summary {
                padding: 1.5rem;
                margin-top: 2rem;
            }
        }

        @media (max-width: 576px) {
            .admin-welcome-title {
                font-size: 1.8rem;
            }
            
            .admin-welcome-message {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
            
            .stat-box h4 {
                font-size: 2rem;
            }
            
            .stat-box p {
                font-size: 0.85rem;
            }
        }

        /* Professional Form Styling */
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .step-card {
            padding: 1.5rem;
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .step-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1.5rem;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .step-title h3 {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .step-title p {
            color: #6c757d;
            margin: 0;
            font-size: 1rem;
        }

        .form-section {
            margin-bottom: 1.5rem;
            padding: 1.2rem;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }

        .section-header {
            margin-bottom: 1rem;
        }

        .section-header h4 {
            color: #2c3e50;
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
        }

        .form-group {
            margin-bottom: 0.75rem;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .required {
            color: #dc3545;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #28a745;
            z-index: 2;
        }

        /* Compact Form Styling */
        .form-control {
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        .form-control:focus {
            padding: 0.6rem 0.75rem;
        }

        .input-wrapper .form-control {
            padding-left: 40px;
        }

        .btn {
            padding: 0.65rem 1.5rem;
            font-size: 0.9rem;
        }

        /* Year Select Dropdowns */
        .year-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1em 1em;
            padding-right: 2.5rem;
        }

        .year-select:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2328a745' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        }

        /* All Select Elements */
        select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1em 1em;
            padding-right: 2.5rem;
        }

        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2328a745' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        }

        .form-control {
            padding-left: 40px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Photo Upload Styling */
        .photo-upload-section {
            margin-bottom: 2rem;
        }

        .photo-upload-container {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            border: 2px dashed #28a745;
        }

        .photo-preview-wrapper {
            margin-bottom: 1.5rem;
        }

        .photo-preview-circle {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            cursor: pointer;
        }

        .photo-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.3s ease;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(40, 167, 69, 0.8);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .photo-preview-circle:hover .photo-overlay {
            opacity: 1;
        }

        .photo-upload-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .photo-upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .photo-input-hidden {
            display: none;
        }

        .photo-requirements {
            margin-top: 1rem;
        }

        /* Progress Indicator */
        .progress-container {
            margin-bottom: 1.5rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .progress-label {
            font-weight: 600;
            color: #495057;
        }

        .progress-percentage {
            font-weight: 700;
            color: #28a745;
        }

        .progress-bar-wrapper {
            margin-bottom: 1rem;
        }

        .progress-bar-bg {
            background: #e9ecef;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            height: 100%;
            transition: width 0.5s ease;
            border-radius: 4px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
        }

        .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            opacity: 0.5;
            transition: all 0.3s ease;
            flex: 1;
        }

        .step-indicator.active {
            opacity: 1;
        }

        .step-indicator.active .step-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            box-shadow: 0 3px 12px rgba(40, 167, 69, 0.3);
        }

        .step-indicator.active .step-icon i {
            color: white;
        }

        .step-indicator span {
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            color: #6c757d;
        }

        /* Academic Records Styling */
        .olevel-container {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .exam-sitting-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
            border: 1px solid #e9ecef;
        }

        .exam-sitting-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .exam-sitting-header h5 {
            color: #2c3e50;
            font-weight: 700;
            margin: 0;
        }

        .subjects-table-container {
            margin-top: 1.5rem;
        }

        .subjects-table {
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .subjects-table thead {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .subjects-table th {
            font-weight: 600;
            border: none;
            padding: 0.75rem;
        }

        .subjects-table td {
            padding: 0.5rem;
            vertical-align: middle;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }

        .subjects-table tbody tr:hover td {
            background: #e9ecef;
        }

        .subjects-table .form-control {
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            padding-left: 0.75rem;
            width: 100%;
            min-height: 40px;
            background: white;
            border-radius: 6px;
        }

        .subjects-table .form-control:focus {
            background: white;
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
        }

        /* JAMB Details */
        .jamb-details-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Documents Section */
        .documents-grid {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .document-upload-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }

        .document-upload-card:hover {
            border-color: #28a745;
            background: #f1f8f4;
        }

        .document-header {
            margin-bottom: 1rem;
        }

        .document-header i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .document-header h6 {
            color: #2c3e50;
            font-weight: 600;
            margin: 0;
        }

        .upload-area input[type="file"] {
            padding: 0.5rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            background: white;
            width: 100%;
        }

        /* Review Section */
        .review-section {
            text-align: center;
        }

        .review-notice {
            margin-bottom: 2rem;
        }

        .declaration-content {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: left;
        }

        .form-check-label {
            font-size: 0.95rem;
            line-height: 1.5;
            color: #495057;
        }

        .submit-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
        }

        /* Navigation Buttons */
        .btn-outline-success {
            border: 2px solid #28a745;
            color: #28a745;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-outline-success:hover {
            background: #28a745;
            color: white;
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .step-card {
                padding: 1rem;
            }
            
            .step-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
                margin-right: 1rem;
            }
            
            .step-title h3 {
                font-size: 1.5rem;
            }
            
            .form-section {
                padding: 1rem;
            }
            
            .photo-preview-circle {
                width: 120px;
                height: 120px;
            }
            
            .progress-steps {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .step-indicator {
                flex: 0 1 calc(25% - 0.375rem);
            }
            
            .step-indicator span {
                font-size: 0.7rem;
            }
        }

        /* Form Header */
        .form-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 3rem 2rem 2rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }

        .form-header-content {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .form-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 0;
        }

        /* Enhanced Progress Indicator */
        .progress-container {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .progress-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .progress-percentage {
            font-weight: 700;
            color: #28a745;
            font-size: 1.1rem;
        }

        .progress-bar-wrapper {
            margin-bottom: 1rem;
        }

        .progress-bar-bg {
            background: #e9ecef;
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-bar-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 50%, transparent 100%);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .progress-steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .step-indicator {
            text-align: center;
            padding: 0.75rem 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .step-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.4rem;
            transition: all 0.3s ease;
        }

        .step-icon i {
            font-size: 14px;
            color: #6c757d;
        }

        .step-indicator span {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
        }

        .step-indicator.active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .step-indicator.active span {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 13px;
        }

        .step-indicator i {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .step-indicator span {
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Step Cards */
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
        }

        .step-card {
            padding: 3rem 2rem;
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .step-icon {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            font-size: 1.5rem;
        }

        .step-title h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .step-title p {
            color: #6c757d;
            margin-bottom: 0;
        }

        /* Enhanced Photo Upload */
        .photo-upload-section {
            margin-bottom: 3rem;
        }

        .photo-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 15px;
            border: 2px dashed #dee2e6;
            transition: all 0.3s ease;
        }

        .photo-upload-container:hover {
            border-color: #28a745;
            background: #f0fff4;
        }

        .photo-preview-wrapper {
            margin-bottom: 1.5rem;
        }

        .photo-preview-circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 4px solid white;
        }

        .photo-preview-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(40, 167, 69, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .photo-preview-circle:hover .photo-overlay {
            opacity: 1;
        }

        .photo-overlay span {
            margin-top: 0.5rem;
            font-weight: 600;
        }

        .photo-upload-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
        }

        .photo-upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .photo-input-hidden {
            display: none;
        }

        .photo-requirements {
            text-align: center;
        }

        /* Form Sections */
        .form-sections {
            margin-top: 2rem;
        }

        .form-section {
            margin-bottom: 3rem;
        }

        .section-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-header h4 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0;
        }

        .section-header i {
            color: #28a745;
        }

        /* Enhanced Form Groups */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .required {
            color: #dc3545;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 2;
        }

        .form-control {
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
        }

        .error-message {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
        }

        .error-message::before {
            content: "⚠️";
            margin-right: 0.5rem;
        }

        /* Responsive Design for Statistics */
        @media (max-width: 768px) {
            .form-header {
                padding: 2rem 1rem;
            }
            
            .form-header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .form-icon {
                margin-right: 0;
                margin-bottom: 1rem;
            }
            
            .step-card {
                padding: 2rem 1rem;
            }
            
            .progress-steps {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
            }
            
            .step-indicator {
                padding: 0.75rem 0.25rem;
            }
            
            .step-indicator span {
                font-size: 0.75rem;
            }
        }
    </style>
    @endif
</x-app-layout>
