<x-guest-layout>
   <section class="login-content">
      <div class="row m-0 align-items-center bg-white min-vh-100">
         <div class="col-12 d-flex justify-content-center align-items-center px-3">
            <div class="w-100" style="max-width: 600px;">
               <div class="card shadow-lg border-0 rounded-3">
                  <div class="card-body p-4 p-md-5 text-center">
                     <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center justify-content-center bg-success p-2 rounded-3 shadow-sm mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Site Logo" width="140" class="logo-global img-fluid">
                     </a>

                     <h1 class="h5 text-center fw-bold text-success mb-1">PORTAL OF SCHOOL OF BASIC</h1>
                     <h2 class="h5 text-center fw-bold text-success mb-4">AND ADVANCED STUDIES</h2>

                     <div class="mb-3">
                        <i class="fas fa-lock fa-2x text-danger"></i>
                     </div>

                     <div class="alert alert-warning border border-warning rounded-3 mb-4 py-3">
                        <h3 class="h6 fw-bold text-warning mb-2">Application Closed</h3>
                        <p class="small mb-0">
                           {{ $message ?? 'Application portal is currently closed. Please try again later.' }}
                        </p>
                     </div>

                     <div class="bg-light p-4 rounded-3 mb-4">
                        <p class="text-muted mb-2">
                           <strong>Already have an account?</strong> You can still log in.
                        </p>
                        <p class="text-muted small mb-0">
                           Please contact the admin or check back later.
                        </p>
                     </div>

                     <div class="d-grid">
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                           <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <style>
      .login-content {
         background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
         min-height: 100vh;
      }
   </style>
</x-guest-layout>
