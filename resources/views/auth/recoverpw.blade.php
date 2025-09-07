<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white min-vh-100">            
         <!-- Left Image Section -->
         <div class="col-lg-6 d-none d-lg-flex bg-success p-0 vh-100 overflow-hidden">
           <img src="{{asset('/images/dashboard/maxresdefault.jpg')}}" 
                 class="w-100 h-100 object-fit-cover gradient-main animated-scaleX" alt="images">
         </div>

         <!-- Right Form Section -->
         <div class="col-lg-6 col-md-12 px-4">
            <div class="d-flex justify-content-center align-items-center min-vh-100">
               <div class="w-100" style="max-width: 600px;">
                  <div class="card shadow-lg border-0 rounded-3">
                     <div class="card-body p-4 p-md-5">
                                                   <!-- Logo Section -->
                           <a href="{{ route('dashboard') }}" 
                              class="navbar-brand d-flex align-items-center justify-content-center bg-success p-2 rounded-3 shadow-sm">
                              <img src="{{ asset('images/logo.png') }}" 
                                    alt="Site Logo" 
                                    width="140" 
                                    class="logo-global img-fluid">
                           </a>
                        </div>
                        <!-- Heading -->
                        <h1 class="h4 text-center fw-bold text-success mb-3">
                           Reset Password
                        </h1>
                        <p class="text-center text-muted mb-4">
                           Enter your email address and we'll send you instructions to reset your password.
                        </p>

                        <!-- Session & Validation -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />
                        @if (session('status'))
                           <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        <x-auth-validation-errors class="mb-3" :errors="$errors" />

                        <!-- Reset Email Form -->
                        <form method="POST" action="{{ route('password.email') }}" novalidate>
                           @csrf
                           <div class="row g-3">
                              <div class="col-12">
                                 <label for="email" class="form-label text-success">Email</label>
                                 <input type="email" class="form-control border-success" 
                                        id="email" name="email" 
                                        placeholder="Enter your email" required>
                              </div>
                           </div>

                           <!-- Submit Button -->
                           <div class="d-grid mt-4">
                              <button type="submit" class="btn btn-success btn-lg text-white">
                                 {{ __('Reset') }}
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
