<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white min-vh-100">            
         <!-- Left Image Section -->
         <div class="col-lg-6 d-none d-lg-flex bg-success p-0 vh-100 overflow-hidden">
            <img src="{{asset('/images/dashboard/maxresdefault.jpg')}}"  class="w-100 h-100 object-fit-cover gradient-main animated-scaleX" alt="images">
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
                              <h3 class="h5 text-center fw-bold text-success mb-0">
                                    PORTAL OF SCHOOL OF BASIC <br> AND ADVANCED STUDIES
                              </h3>
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
                                 {{ __('Register') }}
                              </button>
                           </div>

                           <!-- Already have account -->
                           <p class="mt-3 text-center text-muted">
                              Already have an account?
                              <a href="{{route('auth.signin')}}" class="fw-semibold text-success text-decoration-none">Sign In</a>
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
