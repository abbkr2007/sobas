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
                        
                        <!-- Heading -->
                           <div class="sidebar-header d-flex flex-column align-items-center py-3">
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

                        <p class="text-center text-muted mb-4">Login to access your account.</p>

                        <!-- Session & Validation Messages -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />
                        <x-auth-validation-errors class="mb-3" :errors="$errors" />

                        @if (session('success'))
                           <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('error'))
                           <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" data-toggle="validator" novalidate>
                           {{csrf_field()}}
                           <div class="row g-3">
                              <div class="col-12">
                                 <label class="form-label text-success">Email or Matric Number<span class="text-danger">*</span></label>
                                 <input id="email" type="email" name="email" 
                                        value="{{ env('IS_DEMO') ? 'admin@example.com' : old('email') }}" 
                                        class="form-control border-success" placeholder="Enter your email" required autofocus>
                              </div>
                              <div class="col-12">
                                 <label class="form-label text-success">Password<span class="text-danger">*</span></label>
                                 <input class="form-control border-success" type="password" 
                                        name="password" placeholder="********" 
                                        value="{{ env('IS_DEMO') ? 'password' : '' }}" required autocomplete="current-password">
                              </div>

                              <div class="col-6">
                                 <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">Remember Me</label>
                                 </div>
                              </div>
                              <div class="col-6 text-end">
                                 <a href="{{route('auth.recoverpw')}}" class="text-success">Forgot Password?</a>
                              </div>
                           </div>

                           <!-- Submit Button -->
                           <div class="d-grid mt-4">
                              <button type="submit" class="btn btn-success btn-lg text-white">
                                 {{ __('Sign In') }}
                              </button>
                           </div>

                           <!-- Already have account -->
                           <p class="mt-3 text-center text-muted">
                              Don’t have an account?
                              <a href="{{route('register')}}" class="fw-semibold text-success text-decoration-none">Register here...</a>
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
