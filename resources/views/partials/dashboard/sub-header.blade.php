<div class="professional-dashboard-header" style="background: linear-gradient(135deg, #28a745 0%, #20c997 50%, #17a2b8 100%); position: relative; z-index: 5; overflow: hidden;">
    
    <!-- Background Elements -->
    <div class="header-bg-elements">
        <div class="bg-circle-1"></div>
        <div class="bg-circle-2"></div>
        <div class="bg-pattern"></div>
    </div>
    
    <div class="container-fluid" style="padding: 3rem 0 2rem 0; position: relative; z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7">
                <!-- Professional Welcome Section -->
                <div class="welcome-content">

                    <h1 class="welcome-title display-6 fw-bold text-white mb-2">
                        Welcome back, <span class="text-dark fw-bold">{{ auth()->user()->first_name ?? 'Guest' }}</span>
                    </h1>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-5 text-end">
                <!-- Professional Info Cards -->
                <div class="header-info-cards">
                    <div class="info-card bg-white bg-opacity-10 backdrop-blur rounded-3 p-3 mb-2">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-warning bg-opacity-20 rounded-circle p-2 me-3">
                                <i class="fas fa-calendar-alt text-warning"></i>
                            </div>
                            <div class="text-white">
                                <div class="fw-bold">{{ now()->format('M d, Y') }}</div>
                                <small class="text-white-50">{{ now()->format('l') }}</small>
                            </div>
                        </div>
                    </div>
                    @if(auth()->user() && auth()->user()->user_type == 'admin')
                    <div class="info-card bg-white bg-opacity-10 backdrop-blur rounded-3 p-3">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-success bg-opacity-20 rounded-circle p-2 me-3">
                                <i class="fas fa-users text-success"></i>
                            </div>
                            <div class="text-white">
                                <div class="fw-bold">{{ \App\Models\Application::count() ?? 0 }}</div>
                                <small class="text-white-50">Total Applications</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Quick Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="quick-actions d-flex flex-wrap gap-3">
                    <!-- Quick action buttons removed -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Professional Dashboard Header Styles */
.professional-dashboard-header {
    min-height: 300px;
    position: relative;
    overflow: hidden;
}

.header-bg-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.bg-circle-1 {
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.05);
    top: -200px;
    right: -100px;
    animation: float 6s ease-in-out infinite;
}

.bg-circle-2 {
    position: absolute;
    width: 600px;
    height: 600px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.03);
    bottom: -300px;
    left: -200px;
    animation: float 8s ease-in-out infinite reverse;
}

.bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
        radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.welcome-content {
    animation: slideInFromLeft 0.8s ease-out;
}

.header-info-cards {
    animation: slideInFromRight 0.8s ease-out;
}

@keyframes slideInFromLeft {
    0% { opacity: 0; transform: translateX(-50px); }
    100% { opacity: 1; transform: translateX(0); }
}

@keyframes slideInFromRight {
    0% { opacity: 0; transform: translateX(50px); }
    100% { opacity: 1; transform: translateX(0); }
}

.welcome-badge {
    margin-bottom: 1rem;
    animation: fadeInDown 1s ease-out 0.2s both;
}

@keyframes fadeInDown {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.welcome-title {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.info-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.info-card:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.info-icon {
    transition: all 0.3s ease;
}

.info-card:hover .info-icon {
    transform: scale(1.1);
}

.quick-actions {
    animation: fadeInUp 1s ease-out 0.4s both;
}

@keyframes fadeInUp {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.quick-actions .btn {
    transition: all 0.3s ease;
    border-width: 2px;
    font-weight: 600;
}

.quick-actions .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.btn-light:hover {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
    color: #28a745;
}

.btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .professional-dashboard-header {
        min-height: 250px;
    }
    
    .professional-dashboard-header .container-fluid {
        padding: 2rem 0 1.5rem 0 !important;
    }
    
    .welcome-title {
        font-size: 1.75rem !important;
    }
    
    .header-info-cards {
        margin-top: 2rem;
    }
    
    .info-card {
        margin-bottom: 0.5rem !important;
    }
    
    .quick-actions {
        justify-content: center;
        margin-top: 1.5rem;
    }
    
    .quick-actions .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
    
    .bg-circle-1 {
        width: 300px;
        height: 300px;
        top: -150px;
        right: -50px;
    }
    
    .bg-circle-2 {
        width: 400px;
        height: 400px;
        bottom: -200px;
        left: -100px;
    }
}

@media (max-width: 576px) {
    .professional-dashboard-header {
        min-height: 220px;
    }
    
    .welcome-title {
        font-size: 1.5rem !important;
        line-height: 1.3;
    }
    
    .welcome-subtitle {
        font-size: 1rem !important;
    }
    
    .header-info-cards .info-card {
        padding: 0.75rem !important;
    }
    
    .quick-actions .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .iq-navbar-header {
        min-height: 80px !important;
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
    
    .iq-navbar-header h4 {
        font-size: 1rem !important;
    }
    
    .d-flex.align-items-baseline {
        flex-direction: column !important;
        align-items: flex-start !important;
    }
    
    .iq-navbar-header .me-2 {
        margin-right: 0 !important;
        margin-bottom: 0.25rem;
    }
}

/* Ensure proper spacing on all screen sizes */
@media (min-width: 992px) {
    .iq-navbar-header {
        min-height: 150px !important;
    }
}
</style>
