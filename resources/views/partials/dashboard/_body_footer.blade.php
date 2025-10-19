<!-- Responsive Main Page Footer -->
<footer class="footer bg-white border-top py-2 responsive-footer" style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000;">
    <div class="footer-body d-flex justify-content-center align-items-center h-100 w-100 px-2">
        <!-- Single Footer for All Devices -->
        <div class="text-center text-success fw-semibold w-100" style="font-size: 0.9rem;">
            <div class="d-flex justify-content-center align-items-center">
                © <script>document.write(new Date().getFullYear())</script> {{ env('APP_NAME') }} 
                <span class="text-muted mx-2"> | Product of </span> 
                <a href="https://mbgsystem.com" target="_blank" class="fw-bold text-success text-decoration-none">
                     MBG-System
                </a>
            </div>
        </div>
    </div>
</footer>

<style>
.responsive-footer {
    height: 50px;
    transition: all 0.3s ease;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.footer-body {
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 auto !important;
    text-align: center !important;
}

@media (max-width: 767.98px) {
    .responsive-footer {
        height: 60px;
        padding: 0.25rem 0 !important;
    }
    
    .responsive-footer div {
        font-size: 0.75rem !important;
    }
    
    .main-content {
        padding-bottom: 70px !important;
    }
}

@media (min-width: 768px) {
    .responsive-footer {
        height: 50px;
    }
    
    .main-content {
        padding-bottom: 60px !important;
    }
}

/* Remove sidebar margin to ensure full-width centering */
@media (min-width: 992px) {
    .responsive-footer {
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
    }
}

/* Force center alignment for all footer content */
.responsive-footer * {
    text-align: center !important;
}

.responsive-footer .footer-body > div {
    margin: 0 auto !important;
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
}
</style>
