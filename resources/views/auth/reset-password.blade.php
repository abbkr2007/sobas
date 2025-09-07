<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white min-vh-100">            
         <!-- Left Image Section -->
         <div class="col-lg-6 d-none d-lg-flex bg-success p-0 vh-100 overflow-hidden">
            <img src="{{asset('https://tse1.mm.bing.net/th/id/OIP.2_r8f2rddgtpuRYYVuu06QHaEH?rs=1&pid=ImgDetMain&o=7&rm=3')}}" 
                 class="w-100 h-100 object-fit-cover gradient-main animated-scaleX" alt="images">
         </div>

         <!-- Right Form Section -->
         <div class="col-lg-6 col-md-12 px-4">
            <div class="d-flex justify-content-center align-items-center min-vh-100">
               <div class="w-100" style="max-width: 600px;">
                  <div class="card shadow-lg border-0 rounded-3">
                     <div class="card-body p-4 p-md-5">

                        <!-- Heading -->
                        <h1 class="h4 text-center fw-bold text-success mb-3">
                           Reset Password
                        </h1>
                        <p class="text-center text-muted mb-4">
                           Enter your new password below to reset it.
                        </p>

                        <!-- Validation Errors -->
                        <x-auth-validation-errors class="mb-3" :errors="$errors" />

                        <!-- Reset Password Form -->
                        <form method="POST" action="{{ route('password.update') }}" novalidate>
                           @csrf
                           <input type="hidden" name="token" value="{{ $request->route('token') }}">

                           <div class="row g-3">
                              <div class="col-12">
                                 <label for="email" class="form-label text-success">Email</label>
                                 <input type="email" class="form-control border-success" 
                                        id="email" name="email" 
                                        value="{{ old('email', $request->email) }}" 
                                        readonly required autofocus>
                              </div>

                              <div class="col-12">
                                 <label for="password" class="form-label text-success">New Password</label>
                                 <input type="password" class="form-control border-success" 
                                        id="password" name="password" required>
                              </div>

                              <div class="col-12">
                                 <label for="password_confirmation" class="form-label text-success">Confirm Password</label>
                                 <input type="password" class="form-control border-success" 
                                        id="password_confirmation" name="password_confirmation" required>
                              </div>
                           </div>

                           <!-- Submit Button -->
                           <div class="d-grid mt-4">
                              <button type="submit" class="btn btn-success btn-lg text-white">
                                 {{ __('Reset Password') }}
                              </button>
                           </div>

                           <!-- Back to login link -->
                           <p class="mt-3 text-center text-muted">
                              Remember your password? 
                              <a href="{{route('auth.signin')}}" class="fw-semibold text-success text-decoration-none">
                                 Sign In
                              </a>
                           </p>
                        </form>

                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</x-guest-layout>
