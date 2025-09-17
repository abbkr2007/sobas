<x-app-layout :assets="$assets ?? []">
    <section class="dashboard-content py-5 bg-light">
        <div class="container">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                 @if(session('success'))
                  <!-- <div class="alert alert-success text-center">
                      {{ session('success') }}
                  </div> -->

                  <script>
                      // open acknowledgment page in new tab
                      window.open("{{ route('applications.show', session('application_id')) }}", "_blank");

                      // refresh the form page after 1 second
                      setTimeout(() => {
                          window.location.reload();
                      }, 6000);
                  </script>

              @elseif($hasSubmitted)
                  <div class="alert alert-success text-center">
                      You have already submitted your application.
                      <br>
                      <a href="{{ route('applications.show', $application->id) }}" target="_blank" class="btn btn-primary mt-2">
                          View Acknowledgment Slip
                      </a>
                  </div>

              @else
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
    
                    <form method="POST" action="{{ route('application.submit') }}" enctype="multipart/form-data" id="applicationForm">
                        @csrf

                        <!-- Step 1: Bio Data -->
                        <div class="step-content" id="step-1">
                        <h5 class="fw-bold text-success mb-3" style="font-size:1.2rem;">Bio Data</h5>

                        <!-- Photo Centered -->
                        <div class="text-center mb-4">
                            <label class="form-label fw-semibold small mb-1 d-block">Passport Photo</label>
                            <div class="border border-success rounded-3 mb-2 mx-auto"
                                style="width:140px; height:140px; overflow:hidden;">
                                <img id="photoPreview" src="{{ asset('images/person.png') }}" 
                                    alt="Photo Preview" class="img-fluid w-100 h-100" style="object-fit:cover;">
                            </div>
                            <input type="file" name="photo" accept="image/*"
                                class="form-control form-control-sm border-success w-auto mx-auto"
                                onchange="previewPhoto(event)" required>
                            @error('photo')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2">
                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Matric Number</label>
                                <input type="text" name="application_id" 
                                    class="form-control form-control-sm border-success" 
                                    value="{{ auth()->user()->mat_id }}" readonly>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Surname</label>
                                <input type="text" name="surname" class="form-control form-control-sm border-success" required>
                                @error('surname')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Firstname</label>
                                <input type="text" name="firstname" class="form-control form-control-sm border-success" required>
                                @error('firstname')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Middlename</label>
                                <input type="text" name="middlename" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-sm border-success" required>
                                @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-sm border-success" value="{{ auth()->user()->email }}" readonly>
                                
                                @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Date of Birth</label>
                                <input type="date" name="dob" class="form-control form-control-sm border-success" placeholder="dd/mm/yyyy" required>
                                @error('dob')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Place of Birth</label>
                                <input type="text" name="place_of_birth" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Gender</label>
                                <select name="gender" class="form-select form-select-sm border-success" required>
                                    <option value="">-- Select --</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                                @error('gender')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">State</label>
                                <select name="state" id="state" class="form-select form-select-sm border-success" required onchange="populateLGA()">
                                    <option value="">-- Select --</option>
                                    <option>Abia</option>
                                    <option>Adamawa</option>
                                    <option>Akwa Ibom</option>
                                    <option>Anambra</option>
                                    <option>Bauchi</option>
                                    <option>Bayelsa</option>
                                    <option>Benue</option>
                                    <option>Borno</option>
                                    <option>Cross River</option>
                                    <option>Delta</option>
                                    <option>Ebonyi</option>
                                    <option>Edo</option>
                                    <option>Ekiti</option>
                                    <option>Enugu</option>
                                    <option>FCT</option>
                                    <option>Gombe</option>
                                    <option>Imo</option>
                                    <option>Jigawa</option>
                                    <option>Kaduna</option>
                                    <option>Kano</option>
                                    <option>Katsina</option>
                                    <option>Kebbi</option>
                                    <option>Kogi</option>
                                    <option>Kwara</option>
                                    <option>Lagos</option>
                                    <option>Nasarawa</option>
                                    <option>Niger</option>
                                    <option>Ogun</option>
                                    <option>Ondo</option>
                                    <option>Osun</option>
                                    <option>Oyo</option>
                                    <option>Plateau</option>
                                    <option>Rivers</option>
                                    <option>Sokoto</option>
                                    <option>Taraba</option>
                                    <option>Yobe</option>
                                    <option>Zamfara</option>
                                </select>
                                @error('state')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">LGA</label>
                                <select name="lga" id="lga" class="form-select form-select-sm border-success" required>
                                    <option value="">-- Select State First --</option>
                                </select>
                                @error('lga')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-2">
                                <label class="form-label fw-semibold small mb-1">Town</label>
                                <input type="text" name="town" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-semibold small mb-1">Country</label>
                                <input type="text" name="country" class="form-control form-control-sm border-success" required>
                                @error('country')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-semibold small mb-1">Specify (Foreign Students)</label>
                                <input type="text" name="foreign_country" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-semibold small mb-1">Home Address</label>
                                <input type="text" name="home_address" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-semibold small mb-1">Guardian</label>
                                <input type="text" name="guardian" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-semibold small mb-1">Guardian Address</label>
                                <input type="text" name="guardian_address" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-4 mb-2">
                                <label class="form-label fw-semibold small mb-1">Guardian Phone</label>
                                <input type="text" name="guardian_phone" class="form-control form-control-sm border-success">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-semibold small mb-1">Application Type</label>
                                <select name="application_type" class="form-select form-select-sm border-success" required>
                                    <option value="">-- Application Type: --</option> 
                                    <option>Matric Arts</option>
                                    <option>Matric Arabic & Islamic Studies</option>
                                    <option>Matric Management Sciences</option>
                                    <option>Matric Science</option>
                                    <option>Matric Social Sciences</option>
                                    <option>Matric Law</option>
                                    <option>Remedial Arts</option>
                                    <option>Remedial Arabic & Islamic Studies</option>
                                    <option>Remedial Management Sciences</option>
                                    <option>Remedial Science</option>
                                    <option>Remedial Social Sciences</option>
                                    <option>Remedial Law</option>
                                    <option>JAMB Training Science</option>
                                    <option>JAMB Training Accounting</option>
                                    <option>JAMB Training Economics</option>
                                    <option>JAMB Training Political Science</option>
                                    <option>JAMB Training Sociology</option>
                                    <option>JAMB Training Business Admin</option>
                                    <option>JAMB Training Public Admin</option>
                                </select>
                                @error('application_type')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>


                    

                      <!-- Step 3: Academic Records & Documents -->
                    <div class="step-content" id="step-2">
                    <h5 class="fw-bold text-success mb-3">Academic Records</h5>

                    <!-- Schools Attended -->
                    <div class="card border-success mb-3" style="max-width: 600px; margin: 0 auto;">
                    <div class="card-header bg-success text-white py-1 px-2" style="font-size: 0.9rem;">
                        Schools Attended
                    </div>
                    <div class="card-body p-2">
                        <div class="row g-2 mb-2">
                        <div class="col-md-6">
                            <input type="text" name="school_name[]" placeholder="School Name"
                                class="form-control form-control-sm border-success">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="school_from[]" placeholder="From (YYYY)"
                                class="form-control form-control-sm border-success">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="school_to[]" placeholder="To (YYYY)"
                                class="form-control form-control-sm border-success">
                        </div>
                        </div>
                    </div>
                    </div>


  <!-- O'Level Results -->
<div class="card border-success mb-3">
  <div class="card-header bg-success text-white">O'Level Results</div>
  <div class="card-body">

    <div class="row">
      <!-- First Sitting -->
      <div class="col-md-6">
        <h6 class="fw-bold text-success mb-3 text-center">First Sitting</h6>

        <!-- Exam Details -->
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Exam Type</label>
            <select name="first_exam_type" class="form-select form-select-sm border-success" required>
              <option value="">-- Select Exam Type --</option>
              <option>WAEC</option>
              <option>NECO</option>
              <option>GCE</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Exam Year</label>
            <input type="number" name="first_exam_year" class="form-control form-control-sm border-success" placeholder="e.g. 2022" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Exam Number</label>
            <input type="text" name="first_exam_number" class="form-control form-control-sm border-success" placeholder="e.g. 12345678" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Center Number</label>
            <input type="text" name="first_center_number" class="form-control form-control-sm border-success" placeholder="e.g. 12345" required>
          </div>
        </div>

        <!-- Subjects Table -->
        <div class="table-responsive mb-4">
          <table class="table table-bordered table-sm align-middle">
            <thead class="table-success">
              <tr>
                <th>Subject</th>
                <th>Grade</th>
              </tr>
            </thead>
            <tbody>
              @for ($i = 1; $i <= 9; $i++)
              <tr>
                <td>
                  <select name="first_subject[]" class="form-select form-select-sm border-success" required>
                    <option value="">-- Select Subject --</option>
                    <option>English Language</option>
                    <option>Mathematics</option>
                    <option>Biology</option>
                    <option>Physics</option>
                    <option>Chemistry</option>
                    <option>Economics</option>
                    <option>Geography</option>
                    <option>Government</option>
                    <option>Literature in English</option>
                    <option>Commerce</option>
                    <option>Accounting</option>
                    <option>Agricultural Science</option>
                    <option>Civic Education</option>
                    <option>Further Mathematics</option>
                    <option>Christian Religious Studies</option>
                    <option>Islamic Religious Studies</option>
                    <option>History</option>
                    <option>French</option>
                  </select>
                </td>
                <td>
                  <select name="first_grade[]" class="form-select form-select-sm border-success" required>
                    <option value="">-- Select Grade --</option>
                    <option>A1</option>
                    <option>B2</option>
                    <option>B3</option>
                    <option>C4</option>
                    <option>C5</option>
                    <option>C6</option>
                    <option>D7</option>
                    <option>E8</option>
                    <option>F9</option>
                  </select>
                </td>
              </tr>
              @endfor
            </tbody>
          </table>
        </div>
      </div>

      <!-- Second Sitting -->
      <div class="col-md-6">
        <h6 class="fw-bold text-success mb-3 text-center">Second Sitting</h6>

        <!-- Exam Details -->
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Exam Type</label>
            <select name="second_exam_type" class="form-select form-select-sm border-success">
              <option value="">-- Select Exam Type --</option>
              <option>WAEC</option>
              <option>NECO</option>
              <option>GCE</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Exam Year</label>
            <input type="number" name="second_exam_year" class="form-control form-control-sm border-success" placeholder="e.g. 2022">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Exam Number</label>
            <input type="text" name="second_exam_number" class="form-control form-control-sm border-success" placeholder="e.g. 12345678">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small mb-1">Center Number</label>
            <input type="text" name="second_center_number" class="form-control form-control-sm border-success" placeholder="e.g. 12345">
          </div>
        </div>

        <!-- Subjects Table -->
        <div class="table-responsive mb-4">
          <table class="table table-bordered table-sm align-middle">
            <thead class="table-success">
              <tr>
                <th>Subject</th>
                <th>Grade</th>
              </tr>
            </thead>
            <tbody>
              @for ($i = 1; $i <= 9; $i++)
              <tr>
                <td>
                  <select name="second_subject[]" class="form-select form-select-sm border-success">
                    <option value="">-- Select Subject --</option>
                    <option>English Language</option>
                    <option>Mathematics</option>
                    <option>Biology</option>
                    <option>Physics</option>
                    <option>Chemistry</option>
                    <option>Economics</option>
                    <option>Geography</option>
                    <option>Government</option>
                    <option>Literature in English</option>
                    <option>Commerce</option>
                    <option>Accounting</option>
                    <option>Agricultural Science</option>
                    <option>Civic Education</option>
                    <option>Further Mathematics</option>
                    <option>Christian Religious Studies</option>
                    <option>Islamic Religious Studies</option>
                    <option>History</option>
                    <option>French</option>
                  </select>
                </td>
                <td>
                  <select name="second_grade[]" class="form-select form-select-sm border-success">
                    <option value="">-- Select Grade --</option>
                    <option>A1</option>
                    <option>B2</option>
                    <option>B3</option>
                    <option>C4</option>
                    <option>C5</option>
                    <option>C6</option>
                    <option>D7</option>
                    <option>E8</option>
                    <option>F9</option>
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


  
  <!-- JAMB Details -->
  <div class="card border-success mb-3">
    <div class="card-header bg-success text-white">JAMB Details</div>
    <div class="card-body">
      <div class="row g-2">
        <div class="col-md-6">
          <input type="text" name="jamb_no" placeholder="JAMB No" class="form-control form-control-sm border-success">
        </div>
        <div class="col-md-6">
          <input type="text" name="jamb_score" placeholder="Score" class="form-control form-control-sm border-success">
        </div>
      </div>
    </div>
  </div>
</div>


                        <!-- Step 4: Review -->
                        <div class="step-content d-none" id="step-3">
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

        <!-- Scripts -->
        <script>
            let currentStep = 1;
            const totalSteps = 3;

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

            function previewPhoto(event) {
                const reader = new FileReader();
                reader.onload = function () {
                    document.getElementById('photoPreview').src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }

            // === Nigerian States and LGAs ===
const lgas = {
    "Abia": ["Aba North","Aba South","Umuahia North","Umuahia South","Bende","Isiala Ngwa North","Isiala Ngwa South","Arochukwu","Ohafia","Ikwuano","Osisioma","Ukwa East","Ukwa West","Ugwunagbo","Obi Ngwa","Ngwa"],
    "Adamawa": ["Yola North","Yola South","Gombi","Hong","Ganye","Mubi North","Mubi South","Song","Numan","Demsa","Lamurde","Guyuk","Michika","Madagali","Mayo Belwa","Fufore","Shelleng","Jada","Toungo"],
    "Akwa Ibom": ["Uyo","Eket","Ikot Ekpene","Abak","Itu","Oron","Onna","Ibeno","Esit Eket","Nsit Ubium","Nsit Ibom","Ikono","Mkpat Enin","Ibiono Ibom","Obot Akara","Ini","Essien Udim","Etim Ekpo","Etinan","Okobo","Mbo","Urue-Offong/Oruko","Udung Uko"],
    "Anambra": ["Awka North","Awka South","Onitsha North","Onitsha South","Nnewi North","Nnewi South","Idemili North","Idemili South","Aguata","Njikoka","Dunukofia","Anaocha","Orumba North","Orumba South","Ogbaru","Ayamelum","Anambra West","Anambra East","Oyi","Ihiala","Ekwusigo"],
    "Bauchi": ["Bauchi","Tafawa Balewa","Dass","Bogoro","Ningi","Misau","Jama'are","Itas/Gadau","Katagum","Shira","Giade","Zaki","Gamawa","Damban","Warji","Ganjuwa","Darazo","Kirfi","Alkaleri","Toro"],
    "Bayelsa": ["Yenagoa","Brass","Ekeremor","Kolokuma/Opokuma","Nembe","Ogbia","Sagbama","Southern Ijaw"],
    "Benue": ["Makurdi","Gboko","Otukpo","Guma","Gwer East","Gwer West","Logo","Katsina-Ala","Ukum","Vandeikya","Konshisha","Oju","Obi","Apa","Agatu","Ado","Okpokwu","Ohimini","Tarka","Buruku"],
    "Borno": ["Maiduguri","Jere","Bama","Gwoza","Konduga","Dikwa","Ngala","Kala/Balge","Bayo","Kwaya Kusar","Damboa","Shani","Askira/Uba","Chibok","Hawul","Biu","Monguno","Marte","Nganzai","Kukawa","Magumeri","Guzamala","Mobbar","Abadam"],
    "Cross River": ["Calabar Municipal","Calabar South","Odukpani","Akamkpa","Biase","Akamkpa","Yakurr","Abi","Obubra","Ikom","Etung","Boki","Obudu","Bekwarra","Ogoja","Yala"],
    "Delta": ["Warri South","Warri North","Warri South West","Udu","Ughelli North","Ughelli South","Ethiope East","Ethiope West","Sapele","Okpe","Isoko North","Isoko South","Ndokwa East","Ndokwa West","Oshimili North","Oshimili South","Aniocha North","Aniocha South","Bomadi","Burutu","Patani","Uvwie"],
    "Ebonyi": ["Abakaliki","Ebonyi","Ezza North","Ezza South","Ohaukwu","Ishielu","Ikwo","Afikpo North","Afikpo South","Ivo","Ohaozara","Onicha"],
    "Edo": ["Benin City","Egor","Ikpoba-Okha","Oredo","Orhionmwon","Uhunmwonde","Akoko-Edo","Etsako East","Etsako West","Etsako Central","Esan Central","Esan North-East","Esan South-East","Esan West","Ovia North-East","Ovia South-West"],
    "Ekiti": ["Ado-Ekiti","Ikere","Irepodun/Ifelodun","Ijero","Gbonyin","Ekiti West","Ekiti East","Ikole","Ido/Osi","Oye","Moba","Emure","Ise/Orun","Efon","Ilejemeje"],
    "Enugu": ["Enugu East","Enugu North","Enugu South","Nsukka","Udi","Ezeagu","Igbo-Etiti","Igbo-Eze North","Igbo-Eze South","Isi-Uzo","Nkanu East","Nkanu West","Uzo Uwani","Awgu","Aninri","Oji River"],
    "FCT": ["Abuja Municipal","Bwari","Kuje","Gwagwalada","Abaji","Kwali"],
    "Gombe": ["Gombe","Akko","Balanga","Billiri","Dukku","Funakaye","Kaltungo","Kwami","Nafada","Shongom","Yamaltu/Deba"],
    "Imo": ["Owerri Municipal","Owerri North","Owerri West","Orlu","Okigwe","Mbaitoli","Ikeduru","Ahiazu Mbaise","Ezinihitte","Aboh Mbaise","Ngor Okpala","Ohaji/Egbema","Oguta","Oru East","Oru West","Isiala Mbano","Ehime Mbano","Ihitte/Uboma","Obowo","Onuimo"],
    "Jigawa": ["Dutse","Hadejia","Kazaure","Gwaram","Birnin Kudu","Buji","Garki","Gagarawa","Gwiwa","Jahun","Kafin Hausa","Kiri Kasama","Birniwa","Maigatari","Malam Madori","Ringim","Roni","Sule Tankarkar","Taura","Yankwashi","Miga","Auyo"],
    "Kaduna": ["Kaduna North","Kaduna South","Zaria","Sabon Gari","Jema'a","Kachia","Kagarko","Sanga","Kaura","Jaba","Lere","Kubau","Kudan","Ikara","Makarfi","Birnin Gwari","Chikun","Giwa","Igabi","Kajuru","Soba"],
    "Kano": ["Kano Municipal","Nasarawa","Fagge","Dala","Gwale","Tarauni","Ungogo","Kumbotso","Tofa","Bebeji","Bagwai","Rimin Gado","Karaye","Shanono","Madobi","Bichi","Gwarzo","Kabo","Rogo","Takai","Wudil","Warawa","Garko","Albasu","Doguwa","Sumaila","Tsanyawa","Minjibir","Makoda","Dambatta","Kunchi","Kiru","Kibiya","Bunkure","Gabasawa"],
    "Katsina": ["Katsina","Daura","Funtua","Malumfashi","Dutsin Ma","Mani","Mashi","Musawa","Kankia","Kafur","Kusada","Sandamu","Zango","Bindawa","Baure","Batagarawa","Kurfi","Rimi","Charanchi","Ingawa","Jibia","Batsari","Safana","Dan Musa","Kankara","Bakori","Danja","Sabuwa"],
    "Kebbi": ["Birnin Kebbi","Argungu","Yauri","Zuru","Bagudo","Jega","Augie","Arewa Dandi","Aliero","Kalgo","Bunza","Maiyama","Ngaski","Shanga","Dandi","Fakai","Gwandu","Koko/Besse","Suru","Sakaba","Wasagu/Danko","Yauri","Zuru"],
    "Kogi": ["Lokoja","Okene","Kabba/Bunu","Yagba West","Yagba East","Ijumu","Mopa-Muro","Adavi","Ajaokuta","Okehi","Ogori/Magongo","Bassa","Idah","Ofu","Igalamela-Odolu","Ibaji","Dekina","Ankpa","Omala"],
    "Kwara": ["Ilorin West","Ilorin East","Ilorin South","Offa","Omu-Aran","Patigi","Kaiama","Baruten","Edu","Ifelodun","Irepodun","Ekiti","Isin","Oke Ero","Asa","Moro"],
    "Lagos": ["Agege","Alimosho","Amuwo-Odofin","Apapa","Badagry","Epe","Eti-Osa","Ibeju-Lekki","Ifako-Ijaiye","Ikeja","Ikorodu","Kosofe","Lagos Island","Lagos Mainland","Mushin","Ojo","Oshodi-Isolo","Shomolu","Surulere"],
    "Nasarawa": ["Lafia","Akwanga","Keffi","Nasarawa","Nasarawa Egon","Obi","Kokona","Doma","Toto","Wamba","Karu"],
    "Niger": ["Minna","Bida","Kontagora","Suleja","Mokwa","New Bussa","Rijau","Agaie","Katcha","Lapai","Paikoro","Rafi","Shiroro","Mariga","Mashegu","Wushishi","Tafa","Borgu","Edati","Bosso","Chanchaga"],
    "Ogun": ["Abeokuta North","Abeokuta South","Ijebu Ode","Ijebu North","Ijebu East","Odogbolu","Sagamu","Remo North","Yewa North","Yewa South","Imeko Afon","Ipokia","Ogun Waterside","Obafemi Owode","Ewekoro","Ifo","Ado-Odo/Ota"],
    "Ondo": ["Akure South","Akure North","Owo","Ondo West","Ondo East","Ose","Okitipupa","Irele","Ilaje","Ese Odo","Idanre","Ifedore"],
    "Osun": ["Oshogbo","Ilesa East","Ilesa West","Ifelodun","Ifedayo","Ola-Oluwa","Iwo","Ede North","Ede South","Egbedore","Ejigbo","Ayedaade","Ayedire","Atakunmosa West","Atakunmosa East","Obokun","Oriade","Irewole","Isokan","Iragbiji","Boluwaduro","Ila","Odo Otin","Olorunda","Osogbo"],
    "Oyo": ["Ibadan North","Ibadan North East","Ibadan North West","Ibadan South East","Ibadan South West","Ogbomosho North","Ogbomosho South","Oyo East","Oyo West","Atiba","Afijio","Iseyin","Kajola","Iwajowa","Ibarapa North","Ibarapa Central","Ibarapa East","Lagelu","Egbeda","Ona Ara","Oluyole","Akinyele","Surulere","Orelope","Saki East","Saki West","Atisbo","Itesiwaju"],
    "Plateau": ["Jos North","Jos South","Jos East","Barkin Ladi","Riyom","Mangu","Bokkos","Pankshin","Kanam","Kanke","Langtang North","Langtang South","Mikang","Shendam","Qua'an Pan","Wase"],
    "Rivers": ["Port Harcourt","Obio-Akpor","Okrika","Ogu–Bolo","Eleme","Tai","Gokana","Khana","Oyigbo","Opobo/Nkoro","Andoni","Bonny","Degema","Asari-Toru","Akuku-Toru","Abua–Odual","Ahoada East","Ahoada West","Emohua","Ikwerre","Etche","Omuma"],
    "Sokoto": ["Sokoto North","Sokoto South","Wamakko","Tambuwal","Bodinga","Dange Shuni","Tureta","Gwadabawa","Illela","Kware","Gudu","Tangaza","Sabon Birni","Gada","Wurno","Isa","Kebbe","Silame","Shagari","Yabo","Binji","Goronyo"],
    "Taraba": ["Jalingo","Ardo Kola","Zing","Yorro","Karim Lamido","Lau","Ibi","Wukari","Bali","Gassol","Kurmi","Sardauna","Takum","Ussa","Donga"],
    "Yobe": ["Damaturu","Potiskum","Nguru","Gashua","Geidam","Gujba","Gulani","Yunusari","Yusufari","Bursari","Machina","Fune","Nangere","Karasuwa","Jakusko"],
    "Zamfara": ["Gusau","Kaura Namoda","Anka","Bukkuyum","Bungudu","Birnin Magaji","Maru","Maradun","Tsafe","Shinkafi","Zurmi","Talata Mafara","Gummi"]
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
    </section>
    @endif
</x-app-layout>
