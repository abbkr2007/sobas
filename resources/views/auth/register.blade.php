<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white min-vh-100">            
<<div class="row" style="min-height: 100vh; background-color: #f0f2f5;">
    <!-- Guidelines Column -->
    <div class="col-12 col-lg-6 d-flex justify-content-center align-items-start p-3 p-lg-5">
        <div class="w-100 p-4 p-lg-5 shadow-sm rounded" 
             style="background-color: #ffffff; color: #333;">
           <h5 class="fw-bold mb-4 text-primary">Guidelines for Online Application</h5>

            <ol class="fs-6" style="line-height: 2;">
                <li>🖥️ Visit <a href="https://sobas.cloud/" class="text-primary text-decoration-underline fw-bold" target="_blank">https://sobas.cloud/</a> to access the online application portal.</li>
                <li>✏️ Fill in all required fields carefully with accurate personal information, including <strong>First Name, Last Name, Email, and Mobile Number</strong>.</li>
                <li>💳 Click the <strong class="fw-bold text-primary">Buy Form</strong> button to make your payment securely.</li>
                <li>📄 After successful payment, your unique <strong>Matric Number</strong> and <strong>Password</strong> will be generated and displayed on the page.</li>
                <li>📧 A copy of your Matric Number, Password, and payment receipt will also be sent to your registered email for your records.</li>
                <li>🔑 Use the <strong class="fw-bold text-primary">Matric Number and Password</strong> to log in and access the full application form.</li>
                <li>📝 Carefully complete all required fields in the application form, ensuring that your information is accurate.</li>
                <li>✅ Review your entries thoroughly, submit the form, and <strong class="fw-bold">print your Bio-Data form</strong> for your records.</li>
                <li>⏳ After submission, wait for the admission decision as communicated by the institution.</li>
            </ol>
        </div>
    </div>
         <!-- Right Form Section -->
         <div class="col-lg-6 col-md-12 px-4">
            <div class="d-flex justify-content-center align-items-center min-vh-100">
               <div class="w-100" style="max-width: 600px;">
                  <div class="card shadow-lg border-0 rounded-3">
                     <div class="card-body p-4 p-md-5">
                        
                        <!-- Logo / Heading -->
                                                   <!-- Logo Section -->
                           <a href="{{ route('dashboard') }}" 
                              class="navbar-brand d-flex align-items-center justify-content-center bg-success p-2 rounded-3 shadow-sm">
                              <img src="{{ asset('images/logo.png') }}" 
                                    alt="Site Logo" 
                                    width="140" 
                                    class="logo-global img-fluid">
                           </a>

                           <!-- Title Section -->
                           <a href="{{ route('dashboard') }}" 
                              class="d-flex align-items-center justify-content-center mt-3 text-decoration-none">
                              <h5 class="h5 text-center fw-bold text-success mb-0">
                                    PORTAL OF SCHOOL OF BASIC <br> AND ADVANCED STUDIES
                              </h5>
                           </a>
                     
                        </div>
                        
                        <!-- <p class="text-center text-muted mb-4"></p> -->

                        <!-- Session & Validation Messages -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />
                        <x-auth-validation-errors class="mb-3" :errors="$errors" />

                        @if (session('success'))
                           <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                           <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Registration Form -->
                        <form method="POST" action="{{route('register')}}" data-toggle="validator" novalidate>
                           {{csrf_field()}}
                           <div class="row g-3">
                              <div class="col-md-6">
                                 <label class="form-label text-success">First Name<span class="text-danger">*</span></label>
                                 <input name="first_name" value="{{old('first_name')}}" class="form-control border-success" type="text" required autofocus>
                              </div>
                              <div class="col-md-6">
                                 <label class="form-label text-success">Last Name<span class="text-danger">*</span></label>
                                 <input class="form-control border-success" type="text" name="last_name" value="{{old('last_name')}}" required>
                              </div>

                              <div class="col-md-6">
                                 <label class="form-label text-success">Email<span class="text-danger">*</span></label>
                                 <input name="email" value="{{old('email')}}" class="form-control border-success" type="email" required>
                              </div>
                              <div class="col-md-6">
                                 <label class="form-label text-success">Mobile Number<span class="text-danger">*</span></label>
                                 <input class="form-control border-success" type="text" name="phone_number" value="{{old('phone_number')}}" required>
                              </div>

                              <!-- Hidden password auto-generation -->
                              <input type="password" id="password" name="password" class="d-none" required>
                              <input type="password" id="password_confirmation" name="password_confirmation" class="d-none" required>
                           </div>

                           <!-- Submit Button -->
                           <div class="d-grid mt-4">
                              <button type="submit" class="btn btn-success btn-lg text-white">
                                 {{ __('Buy Form') }}
                              </button>
                           </div>

                           <!-- Already have account -->
                           <p class="mt-3 text-center text-muted">
                              Already have an account? Click Here...
                            <a href="{{ route('auth.signin') }}" class="fw-bold text-success text-decoration-none fs-4">Fill Form</a>

                           </p>
                        </form>

                        <!-- Script for auto-password -->
                        <script>
                           document.addEventListener('DOMContentLoaded', function() {
                              function generatePassword(length) {
                                 const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
                                 let password = "";
                                 for (let i = 0; i < length; i++) {
                                    password += charset[Math.floor(Math.random() * charset.length)];
                                 }
                                 return password;
                              }
                              const newPassword = generatePassword(12);
                              document.getElementById('password').value = newPassword;
                              document.getElementById('password_confirmation').value = newPassword;
                           });
                        </script>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</x-guest-layout>
