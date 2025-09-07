<div class="iq-navbar-header bg-success text-white py-4" style="min-height: 150px;">
    <div class="container-fluid iq-container">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    
                    <!-- Welcome and Name on the same line -->
                    <div class="d-flex align-items-baseline">
                        <h4 class="fw-bold mb-0 me-2">Welcome back,</h4>
                        <h4 class="fw-bolder text-white mb-0">
                            {{ auth()->user()->first_name ?? 'Guest' }}
                        </h4>
                    </div>

                    <!-- Optional Sub-text -->
                    <!-- <p class="mb-0 small text-light">Glad to see you again</p> -->
                    
                </div>
            </div>
        </div>
    </div>
</div>
