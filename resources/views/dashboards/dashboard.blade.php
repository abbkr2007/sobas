<x-app-layout :assets="$assets ?? []">
    <section class="dashboard-content py-5 bg-light">
        <div class="container">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    
                    <!-- Heading -->
                    <div class="text-center mb-5">
                        <h2 class="fw-bold text-success mb-2">Application Form</h2>
                        <p class="text-muted">Please provide accurate information to complete your application</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 8px;">
                        <div id="progressBar" class="progress-bar bg-success" role="progressbar" style="width: 25%;"></div>
                    </div>

                    <!-- Form -->
                    <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data" id="applicationForm">
                        @csrf

                        <!-- Step 1: Bio Data -->
                        <div class="step-content" id="step-1">
                            <h5 class="fw-bold text-success mb-3">Bio Data</h5>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Application ID</label>
                                    <input type="text" name="application_id" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Surname</label>
                                    <input type="text" name="surname" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Firstname</label>
                                    <input type="text" name="firstname" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Middlename</label>
                                    <input type="text" name="middlename" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Place of Birth</label>
                                    <input type="text" name="place_of_birth" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <select name="gender" class="form-select border-success" required>
                                        <option value="">-- Select --</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">State</label>
                                    <select name="state" class="form-select border-success" required>
                                        <option value="">-- Select --</option>
                                        <option>Kano</option>
                                        <option>Lagos</option>
                                        <option>Kaduna</option>
                                        <option>Abuja</option>
                                        <option>Others...</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">LGA</label>
                                    <input type="text" name="lga" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Town</label>
                                    <input type="text" name="town" class="form-control border-success">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Country</label>
                                    <input type="text" name="country" class="form-control border-success" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Specify (Foreign Students)</label>
                                    <input type="text" name="foreign_country" class="form-control border-success">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Home Address</label>
                                    <textarea name="home_address" rows="2" class="form-control border-success"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Guardian</label>
                                    <input type="text" name="guardian" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Guardian Address</label>
                                    <input type="text" name="guardian_address" class="form-control border-success">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Guardian Phone</label>
                                    <input type="text" name="guardian_phone" class="form-control border-success">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Application Type</label>
                                    <select name="application_type" class="form-select border-success" required>
                                        <option value="">-- Select --</option>
                                        <option>Undergraduate</option>
                                        <option>Postgraduate</option>
                                        <option>Diploma</option>
                                        <option>Certificate</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Education -->
                        <div class="step-content d-none" id="step-2">
                            <h5 class="fw-bold text-success mb-3">Education</h5>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Highest Qualification</label>
                                    <input type="text" name="qualification" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Institution</label>
                                    <input type="text" name="institution" class="form-control border-success" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Graduation Year</label>
                                    <input type="number" name="graduation_year" class="form-control border-success">
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Documents -->
                        <div class="step-content d-none" id="step-3">
                            <h5 class="fw-bold text-success mb-3">Documents</h5>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Upload Resume</label>
                                    <input type="file" name="resume" class="form-control border-success" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Supporting Document</label>
                                    <input type="file" name="supporting_document" class="form-control border-success">
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Review -->
                        <div class="step-content d-none" id="step-4">
                            <h5 class="fw-bold text-success mb-3">Review & Submit</h5>
                            <p class="text-muted">Kindly review your details before final submission.</p>
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                Submit Application
                            </button>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" id="prevBtn" class="btn btn-outline-secondary d-none">Previous</button>
                            <button type="button" id="nextBtn" class="btn btn-success">Next</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Script -->
        <script>
            let currentStep = 1;
            const totalSteps = 4;

            function updateProgressBar(step) {
                const progress = (step / totalSteps) * 100;
                document.getElementById('progressBar').style.width = progress + '%';
            }

            function showStep(step) {
                document.querySelectorAll('.step-content').forEach((el, idx) => {
                    el.classList.add('d-none');
                    if (idx === step - 1) el.classList.remove('d-none');
                });
                document.getElementById('prevBtn').classList.toggle('d-none', step === 1);
                document.getElementById('nextBtn').innerText = step === totalSteps ? 'Finish' : 'Next';
                updateProgressBar(step);
            }

            document.getElementById('nextBtn').addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                } else {
                    document.getElementById('applicationForm').submit();
                }
            });

            document.getElementById('prevBtn').addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            showStep(currentStep);
        </script>
    </section>
</x-app-layout>
