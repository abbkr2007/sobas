@extends('layouts.app')

@section('content')
<section class="login-content">
   <div class="row m-0 align-items-center bg-white min-vh-100">
      <div class="col-12 d-flex justify-content-center align-items-center">
         <div class="w-100" style="max-width: 600px;">
            <div class="card shadow-lg border-0 rounded-3">
               <div class="card-body p-4 p-md-5 text-center">

                  <!-- Logo Section -->
                  <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center justify-content-center bg-success p-2 rounded-3 shadow-sm mb-3">
                     <img src="{{ asset('images/logo.png') }}" alt="Site Logo" width="140" class="logo-global img-fluid">
                  </a>

                  <!-- Title Section -->
                  <h5 class="h5 text-center fw-bold text-success mb-4">
                     PORTAL OF SCHOOL OF BASIC <br> AND ADVANCED STUDIES
                  </h5>

                  <!-- Closed Icon -->
                  <div class="mb-4">
                     <i class="fas fa-lock fa-5x text-danger"></i>
                  </div>

                  <!-- Closed Message -->
                  <div class="alert alert-warning border border-warning rounded-3 mb-4">
                     <h4 class="alert-heading fw-bold text-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>Registration Closed
                     </h4>
                     <p class="mb-0 fs-5">
                        {{ $message ?? 'Registration portal is currently closed. Please try again later.' }}
                     </p>
                  </div>

                  <!-- Information Box -->
                  <div class="bg-light p-4 rounded-3 mb-4">
                     <p class="text-muted mb-3">
                        <strong>Already have an account?</strong> You can still log in with your credentials.
                     </p>
                     <p class="text-muted small mb-0">
                        For questions about registration status, please contact the admin or check back later.
                     </p>
                  </div>

                  <!-- Buttons -->
                  <div class="d-grid gap-2">
                     <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Login to Your Account
                     </a>
                     <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-2"></i>Back to Home
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
@endsection
