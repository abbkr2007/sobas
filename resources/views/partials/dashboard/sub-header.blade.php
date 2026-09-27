@php
    $headerUser = auth()->user();
    $headerUserName = trim(($headerUser->first_name ?? '') . ' ' . ($headerUser->last_name ?? '')) ?: 'Guest';
    $headerViewingSession = $headerUser && $headerUser->user_type === 'admin'
        ? app(\App\Services\AcademicSessionService::class)->viewing()
        : null;
    $headerApplicationCount = $headerViewingSession
        ? \App\Models\Application::where('academic_session_id', $headerViewingSession->id)->count()
        : 0;
@endphp

<div class="professional-dashboard-header" style="background: linear-gradient(110deg, #174d41 0%, #176c59 68%, #175965 100%); position: relative; z-index: 5; overflow: hidden;">

    <div class="container-fluid" style="padding: 1.75rem 0; position: relative; z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7">
                <!-- Professional Welcome Section -->
                <div class="welcome-content">

                    <h1 class="welcome-title display-6 fw-bold text-white mb-2">
                        Welcome back, <span class="text-white fw-bold">{{ $headerUserName }}</span>
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
                    @if($headerUser && $headerUser->user_type === 'admin')
                    <div class="info-card bg-white bg-opacity-10 backdrop-blur rounded-3 p-3">
                        <div class="d-flex align-items-center">
                            <div class="info-icon bg-success bg-opacity-20 rounded-circle p-2 me-3">
                                <i class="fas fa-users text-success"></i>
                            </div>
                            <div class="text-white">
                                <div class="fw-bold">{{ number_format($headerApplicationCount) }}</div>
                                <small class="text-white-50">Total Applications</small>
                                <small class="d-block text-white-50">{{ $headerViewingSession ? $headerViewingSession->label . ' session' : 'No session selected' }}</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* Professional Dashboard Header Styles */
.professional-dashboard-header {
    min-height: 170px;
    position: relative;
    overflow: hidden;
}

.professional-dashboard-header .welcome-content {
    animation: slideInFromLeft 0.8s ease-out;
}

.professional-dashboard-header .header-info-cards {
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

.professional-dashboard-header .welcome-badge {
    margin-bottom: 1rem;
    animation: fadeInDown 1s ease-out 0.2s both;
}

@keyframes fadeInDown {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}

.professional-dashboard-header .welcome-title {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    overflow-wrap: anywhere;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.professional-dashboard-header .info-card {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.professional-dashboard-header .info-card:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.professional-dashboard-header .info-icon {
    transition: all 0.3s ease;
}

.professional-dashboard-header .info-card:hover .info-icon {
    transform: scale(1.1);
}

.professional-dashboard-header .btn-light:hover {
    background-color: #f8f9fa;
    border-color: #f8f9fa;
    color: #28a745;
}

.professional-dashboard-header .btn-outline-light:hover {
    background-color: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.3);
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .professional-dashboard-header {
        min-height: 150px;
    }

    .professional-dashboard-header .container-fluid {
        padding: 1.25rem 0 !important;
    }

    .professional-dashboard-header .welcome-title {
        font-size: 1.75rem !important;
    }

    .professional-dashboard-header .header-info-cards {
        margin-top: 0.75rem;
    }

    .professional-dashboard-header .info-card {
        margin-bottom: 0.5rem !important;
    }

}

@media (max-width: 576px) {
    .professional-dashboard-header {
        min-height: 140px;
    }

    .professional-dashboard-header .welcome-title {
        font-size: 1.5rem !important;
        line-height: 1.3;
    }

    .welcome-subtitle {
        font-size: 1rem !important;
    }

    .professional-dashboard-header .header-info-cards .info-card {
        padding: 0.75rem !important;
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
@media (prefers-reduced-motion: reduce) {
    .professional-dashboard-header .welcome-content,
    .professional-dashboard-header .header-info-cards {
        animation: none;
    }

    .professional-dashboard-header .info-card,
    .professional-dashboard-header .info-icon {
        transition: none;
        transform: none;
    }
}
</style>
