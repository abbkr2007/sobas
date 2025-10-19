<!-- Desktop Sidebar -->
<aside class="professional-sidebar d-none d-lg-block">
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="Site Logo" 
                     class="logo-image">
                <div class="logo-text d-none d-xl-block">
                    <h5 class="app-name mb-0">{{ env('APP_NAME', 'SOBAS') }}</h5>
                    <small class="app-tagline">Application System</small>
                </div>
            </div>
        </a>
    </div>

    <div class="sidebar-body">
        <div class="sidebar-navigation">
            @include('partials.dashboard.vertical-nav') 
        </div>
        
        <!-- Professional Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="user-info-card">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details">
                    <h6 class="user-name mb-0">{{ auth()->user()->first_name ?? 'User' }}</h6>
                    <small class="user-role text-muted">{{ ucfirst(auth()->user()->user_type ?? 'Guest') }}</small>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Enhanced Mobile Menu Toggle Button -->
<div class="d-lg-none position-fixed p-3" style="z-index: 1050; top: 10px; left: 10px;">
    <button class="btn btn-success d-lg-none mobile-menu-toggle" 
            type="button" 
            data-bs-toggle="offcanvas" 
            data-bs-target="#mobileSidebar" 
            aria-controls="mobileSidebar"
            aria-label="Open navigation menu"
            style="border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); padding: 8px 12px; margin: 0;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="white" class="menu-icon">
            <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        </svg>
    </button>
</div>

<!-- Enhanced Mobile Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start d-lg-none" 
     tabindex="-1" 
     id="mobileSidebar" 
     aria-labelledby="mobileSidebarLabel" 
     style="background-color: #ffffff; width: min(280px, 85vw);">
    
    <div class="offcanvas-header border-bottom d-flex align-items-center justify-content-between p-3" 
         style="border-color: #28a745 !important; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Site Logo" 
                 class="img-fluid" 
                 style="max-width: 120px; height: auto; filter: brightness(1.1);">
        </div>
        <button type="button" 
                class="btn btn-outline-light btn-sm" 
                data-bs-dismiss="offcanvas" 
                aria-label="Close navigation menu"
                style="border-radius: 50%; width: 36px; height: 36px; padding: 0;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </button>
    </div>
    
    <div class="offcanvas-body p-0" style="overflow-y: auto; -webkit-overflow-scrolling: touch;">
        <div class="mobile-nav-wrapper" style="padding: 1rem;">
            @include('partials.dashboard.vertical-nav')
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced mobile navigation experience
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    
    if (mobileMenuToggle && mobileSidebar) {
        // Add haptic feedback for touch devices
        if ('vibrate' in navigator) {
            mobileMenuToggle.addEventListener('click', function() {
                navigator.vibrate(50);
            });
        }
        
        // Handle swipe gestures on mobile
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        
        document.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        document.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            currentX = e.touches[0].clientX;
        });
        
        document.addEventListener('touchend', function(e) {
            if (!isDragging) return;
            isDragging = false;
            
            const diffX = currentX - startX;
            const minSwipeDistance = 100;
            
            // Swipe right from left edge to open menu
            if (startX < 50 && diffX > minSwipeDistance) {
                const offcanvas = new bootstrap.Offcanvas(mobileSidebar);
                offcanvas.show();
            }
            // Swipe left to close menu
            else if (diffX < -minSwipeDistance && mobileSidebar.classList.contains('show')) {
                const offcanvas = bootstrap.Offcanvas.getInstance(mobileSidebar);
                if (offcanvas) offcanvas.hide();
            }
        });
        
        // Auto-close menu when clicking on nav items
        mobileSidebar.addEventListener('click', function(e) {
            if (e.target.closest('a') && !e.target.closest('.dropdown-toggle')) {
                setTimeout(() => {
                    const offcanvas = bootstrap.Offcanvas.getInstance(mobileSidebar);
                    if (offcanvas) offcanvas.hide();
                }, 150);
            }
        });
        
        // Handle orientation changes
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                // Recalculate sidebar dimensions
                const currentWidth = window.innerWidth;
                const sidebarWidth = Math.min(280, currentWidth * 0.85);
                mobileSidebar.style.width = sidebarWidth + 'px';
            }, 100);
        });
    }
    
    // Enhance navigation for all devices
    const navLinks = document.querySelectorAll('.sidebar a, .mobile-nav-wrapper a');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Add visual feedback
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    });
    
    // Performance optimization: Lazy load navigation icons
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        observer.observe(img);
    });
});
</script>

<style>
/* Professional Sidebar Styles */
.professional-sidebar {
    width: 280px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: #ffffff;
    border-right: 1px solid #e9ecef;
    z-index: 1030;
    overflow: hidden;
    box-shadow: 4px 0 15px rgba(44, 90, 160, 0.08);
}

.sidebar-header {
    padding: 2rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.navbar-brand {
    text-decoration: none;
    color: white;
    display: block;
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-image {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    object-fit: contain;
    background: rgba(255, 255, 255, 0.15);
    padding: 8px;
    backdrop-filter: blur(10px);
}

.logo-text {
    flex: 1;
}

.app-name {
    color: white;
    font-weight: 700;
    font-size: 1.2rem;
    letter-spacing: -0.02em;
    margin-bottom: 0;
}

.app-tagline {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.sidebar-body {
    height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
    position: relative;
}

.sidebar-navigation {
    flex: 1;
    padding: 1.5rem 0;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Professional Navigation Items */
.navbar-nav .nav-item {
    margin-bottom: 4px;
}

.navbar-nav .nav-link {
    padding: 12px 20px;
    display: flex;
    align-items: center;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    position: relative;
}

.navbar-nav .nav-link .icon {
    margin-right: 12px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.navbar-nav .nav-link .item-name {
    flex: 1;
}

/* Force Green Theme Override - Higher Specificity */
.professional-sidebar .navbar-nav .nav-item .nav-link {
    background: transparent !important;
    color: #6c757d !important;
    border: none !important;
}

.professional-sidebar .navbar-nav .nav-item .nav-link.active,
.professional-sidebar .navbar-nav .nav-item .nav-link[aria-expanded="true"] {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: #ffffff !important;
}

.professional-sidebar .navbar-nav .nav-item .nav-link:hover:not(.active) {
    background: rgba(40, 167, 69, 0.08) !important;
    color: #28a745 !important;
}

.sidebar-navigation::-webkit-scrollbar {
    width: 4px;
}

.sidebar-navigation::-webkit-scrollbar-track {
    background: rgba(40, 167, 69, 0.05);
}

.sidebar-navigation::-webkit-scrollbar-thumb {
    background: rgba(40, 167, 69, 0.2);
    border-radius: 2px;
}

.sidebar-navigation::-webkit-scrollbar-thumb:hover {
    background: rgba(40, 167, 69, 0.4);
}

.sidebar-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

.user-info-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #ffffff;
    border-radius: 10px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.08);
}

.user-info-card:hover {
    background: rgba(40, 167, 69, 0.05);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.15);
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.user-name {
    color: #2c3e50;
    font-weight: 600;
    font-size: 0.9rem;
}

.user-role {
    color: #6c757d;
    font-size: 0.75rem;
    text-transform: capitalize;
}

/* Enhanced mobile sidebar styles */
.mobile-menu-toggle {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.mobile-menu-toggle:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(40, 167, 69, 0.4) !important;
}

.mobile-menu-toggle:active {
    transform: scale(0.95);
}

.offcanvas {
    backdrop-filter: blur(5px);
}

.offcanvas-backdrop {
    background-color: rgba(0, 0, 0, 0.4) !important;
}

.mobile-nav-wrapper .nav-link {
    padding: 12px 16px;
    margin: 4px 0;
    border-radius: 8px;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}

.mobile-nav-wrapper .nav-link:hover,
.mobile-nav-wrapper .nav-link.active {
    background-color: rgba(40, 167, 69, 0.1);
    transform: translateX(4px);
}

.mobile-nav-wrapper .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 0;
    background: linear-gradient(135deg, #28a745, #20c997);
    transition: width 0.3s ease;
}

.mobile-nav-wrapper .nav-link.active::before {
    width: 4px;
}

/* Touch-friendly improvements */
@media (pointer: coarse) {
    .mobile-nav-wrapper .nav-link {
        min-height: 48px;
        display: flex;
        align-items: center;
    }
    
    .offcanvas-header button {
        min-width: 44px;
        min-height: 44px;
    }
}

/* Device-specific optimizations */
@media (max-width: 320px) {
    .offcanvas {
        width: 90vw !important;
    }
    
    .mobile-nav-wrapper {
        padding: 0.75rem !important;
    }
    
    .mobile-nav-wrapper .nav-link {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
}

/* Landscape phone optimization */
@media (orientation: landscape) and (max-height: 500px) {
    .offcanvas-header {
        padding: 0.75rem !important;
    }
    
    .mobile-nav-wrapper {
        padding: 0.5rem !important;
    }
    
    .mobile-nav-wrapper .nav-link {
        padding: 8px 12px;
        margin: 2px 0;
    }
}

/* Animation enhancements */
.offcanvas.show {
    animation: slideInFromLeft 0.3s ease-out;
}

@keyframes slideInFromLeft {
    from {
        transform: translateX(-100%);
    }
    to {
        transform: translateX(0);
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .mobile-nav-wrapper .nav-link {
        border: 1px solid currentColor;
        margin: 6px 0;
    }
    
    .mobile-menu-toggle {
        border: 2px solid currentColor;
    }
}
</style>
