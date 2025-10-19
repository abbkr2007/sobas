<div id="loading">
    @include('partials.dashboard._body_loader')
</div>
@include('partials.dashboard._body_sidebar')
<main class="main-content d-flex flex-column" style="min-height: 100vh; padding-bottom: 60px;">
    <div class="position-relative">
    @include('partials.dashboard._body_header')
    @include('partials.dashboard.sub-header')
    </div>
    
    <div class="professional-main-content flex-grow-1">
    {{ $slot }}
    </div>
    
    @include('partials.dashboard._body_footer')
</main>

<style>
/* Global CSS Variables - Professional Green Color Scheme */
:root {
    --primary-color: #28a745;
    --primary-hover: #218838;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --success-hover: #218838;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --sidebar-width: 280px;
    --header-height: 60px;
    --border-radius: 12px;
    --box-shadow-light: 0 4px 15px rgba(40, 167, 69, 0.1);
    --box-shadow-medium: 0 8px 25px rgba(40, 167, 69, 0.15);
    --transition-fast: 0.3s ease;
    --gradient-primary: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --gradient-success: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    --text-primary: #2c3e50;
    --text-secondary: #6c757d;
    --bg-light: #f8f9fa;
    --bg-white: #ffffff;
}

/* GLOBAL COLOR OVERRIDES - Force green theme everywhere */
/* Override any red colors from framework CSS */
*:not(.text-danger):not(.btn-danger):not(.alert-danger) {
    --bs-danger: #28a745 !important;
    --bs-danger-rgb: 40, 167, 69 !important;
}

/* Force all pagination to be green */
.dataTables_paginate .paginate_button,
.dataTables_wrapper .dataTables_paginate .paginate_button,
div.dataTables_wrapper div.dataTables_paginate ul.pagination li a,
.pagination .page-link,
button.paginate_button {
    background-color: white !important;
    border-color: #e9ecef !important;
    color: #495057 !important;
}

.dataTables_paginate .paginate_button.current,
.dataTables_paginate .paginate_button:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button:hover,
div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active a,
.pagination .page-item.active .page-link,
.pagination .page-link:hover,
button.paginate_button.current,
button.paginate_button:hover {
    background: #28a745 !important;
    background-color: #28a745 !important;
    border-color: #28a745 !important;
    color: white !important;
}

/* Base Layout Styles - Enhanced Responsive */
.main-content {
    transition: margin-left var(--transition-fast);
    min-height: 100vh;
    padding-bottom: 60px;
    position: relative;
    overflow-x: hidden;
}

.professional-main-content {
    background-color: #f8f9fa;
    min-height: calc(100vh - 300px);
    position: relative;
    z-index: 1;
    padding: 1rem;
}

/* Responsive Layout Adjustments */
@media (max-width: 320px) {
    .professional-main-content {
        padding: 0.5rem;
        min-height: calc(100vh - 200px);
    }
    
    .container {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0 !important;
        padding: 0.75rem;
    }
    
    .professional-main-content {
        padding: 0.75rem;
        margin-top: 70px; /* Account for mobile header */
    }
    
    .container {
        max-width: 100%;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .professional-main-content {
        padding: 1.25rem;
    }
}

@media (min-width: 1024px) {
    .main-content {
        margin-left: var(--sidebar-width);
    }
    
    .professional-main-content {
        padding: 2rem;
    }
}

/* Professional Layout Enhancements */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    background-color: #ffffff;
    color: #2c3e50;
    line-height: 1.6;
}

.main-content {
    background: linear-gradient(180deg, rgba(44, 90, 160, 0.02) 0%, rgba(248, 249, 250, 1) 100%);
}

/* Professional Sidebar Styles */
.professional-sidebar {
    background: #ffffff;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
    border-right: 1px solid #e9ecef;
    position: fixed;
    width: var(--sidebar-width);
    height: 100vh;
    top: 0;
    left: 0;
    z-index: 1030;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.sidebar-header {
    padding: 1.5rem 1rem;
    border-bottom: 1px solid #e9ecef;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.logo-image {
    max-width: 120px;
    height: auto;
    filter: brightness(1.1);
}

.app-name {
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.app-tagline {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.75rem;
}

/* Mobile Offcanvas Enhancements */
.mobile-nav-wrapper .nav-item {
    margin-bottom: 0.25rem;
}

.mobile-nav-wrapper .nav-link {
    border-radius: 8px !important;
    margin: 0.25rem 0 !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.95rem;
}

/* Desktop Layout (992px and up) */
@media (min-width: 992px) {
    .main-content {
        margin-left: var(--sidebar-width);
        padding-left: 1rem;
    }
    
    .professional-sidebar {
        display: block !important;
    }
}

/* Tablet Layout (768px to 991.98px) */
@media (min-width: 768px) and (max-width: 991.98px) {
    .main-content {
        margin-left: 0 !important;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .professional-main-content {
        margin-top: 60px;
        padding: 1.25rem;
    }
    
    .container-fluid {
        max-width: 100%;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .btn {
        padding: 0.625rem 1.25rem;
    }
}

/* Large Tablet/Small Desktop (992px to 1199.98px) */
@media (min-width: 992px) and (max-width: 1199.98px) {
    .professional-sidebar {
        width: 260px;
    }
    
    :root {
        --sidebar-width: 260px;
    }
    
    .main-content {
        margin-left: 260px;
    }
}

/* Large Desktop (1200px and up) */
@media (min-width: 1200px) {
    .professional-sidebar {
        width: 300px;
    }
    
    :root {
        --sidebar-width: 300px;
    }
    
    .main-content {
        margin-left: 300px;
    }
}

/* Extra Large Desktop (1440px and up) */
@media (min-width: 1440px) {
    .professional-sidebar {
        width: 320px;
    }
    
    :root {
        --sidebar-width: 320px;
    }
    
    .main-content {
        margin-left: 320px;
    }
    
    .professional-main-content {
        padding: 2.5rem;
    }
    
    .container-fluid {
        max-width: 1400px;
        margin: 0 auto;
    }
}

/* Responsive Utility Classes for Better Mobile Experience */
.mobile-hidden {
    display: none !important;
}

.mobile-only {
    display: block !important;
}

@media (min-width: 768px) {
    .mobile-hidden {
        display: block !important;
    }
    
    .mobile-only {
        display: none !important;
    }
}

/* Touch-friendly interactive elements */
@media (max-width: 767.98px) {
    .btn, .btn-sm {
        min-height: 44px; /* Touch-friendly minimum size */
        padding: 0.75rem 1rem;
    }
    
    .form-control, .form-select {
        min-height: 44px;
        padding: 0.75rem;
    }
    
    /* Improve mobile navigation */
    .navbar-nav .nav-link {
        padding: 1rem !important;
        font-size: 1rem;
    }
    
    /* Better mobile table experience */
    .table-responsive {
        border: none;
        -webkit-overflow-scrolling: touch;
    }
    
    .table {
        min-width: 600px;
    }
    
    /* Mobile-first card spacing */
    .card + .card {
        margin-top: 1rem;
    }
}

/* Responsive text utilities */
@media (max-width: 575.98px) {
    .fs-responsive {
        font-size: 0.875rem !important;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .fs-responsive {
        font-size: 0.9375rem !important;
    }
}

@media (min-width: 768px) {
    .fs-responsive {
        font-size: 1rem !important;
    }
}

/* Print styles for better document generation */
@media print {
    .sidebar, .mobile-menu-toggle, .btn, .navbar, .footer {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .card {
        border: 2px solid #000 !important;
    }
    
    .btn-success {
        background-color: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
    }
}

/* Reduced motion support for accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Tablet Layout (768px to 991.98px) */
@media (max-width: 991.98px) {
    .main-content {
        margin-left: 0 !important;
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    .content-inner {
        margin-top: 3rem !important;
        padding-top: 2rem !important;
    }
}
    
    .sidebar {
        transform: translateX(-100%);
        position: fixed;
        z-index: 1040;
        width: var(--sidebar-width);
        height: 100vh;
        top: 0;
        left: 0;
        transition: transform var(--transition-fast);
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
    
    .container-fluid {
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
    
    .card {
        margin-bottom: 20px;
    }
    
    .btn {
        font-size: 0.9rem;
    }
}

/* Mobile Layout (320px to 767.98px) */
@media (max-width: 320px) {
    :root {
        --sidebar-width: 260px;
    }
    
    .container, .container-fluid {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    
    .card {
        margin-bottom: 0.75rem;
    }
    
    .btn {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
}

@media (max-width: 767.98px) {
    .main-content {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        margin-left: 0 !important;
    }
    
    .professional-main-content {
        margin-top: 60px;
        padding: 0.75rem;
    }
    
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .card-body {
        padding: 1rem;
    }
    
    h1, .h1 { font-size: 1.75rem; }
    h2, .h2 { font-size: 1.5rem; }
    h3, .h3 { font-size: 1.25rem; }
    h4, .h4 { font-size: 1.1rem; }
    h5, .h5 { font-size: 1rem; }
    
    .btn {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
    
    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8rem;
    }
    
    .form-control, .form-select {
        font-size: 16px; /* Prevents zoom on iOS */
    }
}
    
    .container-fluid {
        padding-left: 8px !important;
        padding-right: 8px !important;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .card-header {
        padding: 12px 15px;
    }
    
    .card-header h4 {
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    
    .btn {
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
    }
    
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}

/* Small Mobile (576px and below) */
@media (max-width: 576px) {
    .main-content {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    .container-fluid {
        padding-left: 5px !important;
        padding-right: 5px !important;
    }
    
    .card-body {
        padding: 10px;
    }
    
    .card-header {
        padding: 8px 10px;
    }
    
    .card-header h4 {
        font-size: 1rem;
    }
    
    .btn {
        font-size: 0.8rem;
        padding: 0.3rem 0.6rem;
    }
    
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
    }
}

/* Table Responsive Enhancements */
.table-responsive {
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow-light);
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 13px;
        border: none;
        box-shadow: none;
    }
    
    .custom-table {
        font-size: 12px;
    }
    
    .custom-table th,
    .custom-table td {
        padding: 6px 8px !important;
        font-size: 0.8rem;
    }
    
    .table thead th {
        font-size: 0.85rem;
        border-bottom: 2px solid #dee2e6;
    }
    
    .table tbody td {
        font-size: 0.8rem;
        border-top: 1px solid #dee2e6;
    }
}

@media (max-width: 576px) {
    .custom-table th,
    .custom-table td {
        padding: 4px 6px !important;
        font-size: 0.75rem;
    }
    
    .table thead th {
        font-size: 0.8rem;
    }
    
    .table tbody td {
        font-size: 0.75rem;
    }
}

/* DataTables Mobile Optimizations */
@media (max-width: 768px) {
    .dataTables_wrapper {
        font-size: inherit;
    }
    
    .dataTables_wrapper .dataTables_filter {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        width: 100%;
        max-width: 300px;
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    
    .dataTables_wrapper .dataTables_length {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .dataTables_wrapper .dataTables_info {
        text-align: center;
        margin: 15px 0;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .dataTables_wrapper .dataTables_paginate {
        text-align: center;
        margin: 15px 0;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 2px;
        border-radius: 4px;
        font-size: 0.875rem;
        border: 1px solid #dee2e6;
        background: white;
        color: #495057;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
        color: white !important;
    }

    /* Additional strong overrides for pagination colors */
    table.dataTable tbody tr:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button,
    .dataTables_wrapper .dataTables_paginate .paginate_button:visited,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li span {
        background-color: white !important;
        border-color: #e9ecef !important;
        color: #495057 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active a,
    div.dataTables_wrapper div.dataTables_paginate ul.pagination li.active span {
        background: #28a745 !important;
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }

    /* Override any Bootstrap pagination red colors */
    .pagination .page-item.active .page-link,
    .pagination .page-link:hover,
    .page-item.active .page-link,
    .btn-danger,
    .text-danger {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: white !important;
    }

    .btn-danger:hover,
    .btn-danger:focus,
    .btn-danger:active {
        background-color: #1e7e34 !important;
        border-color: #1c7430 !important;
        color: white !important;
    }

    /* Force DataTables pagination visibility */
    .dataTables_wrapper .dataTables_paginate,
    .dataTables_paginate {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        height: auto !important;
        overflow: visible !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button,
    .dataTables_paginate .paginate_button {
        display: inline-block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
}

/* Button Enhancements */
.btn-primary,
.export-excel-btn {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    transition: all var(--transition-fast);
}

.btn-primary:hover,
.export-excel-btn:hover {
    background-color: var(--primary-hover);
    border-color: #1e7e34;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

@media (max-width: 576px) {
    .export-excel-btn {
        width: 100%;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }
}

/* Badge Responsiveness */
.badge-application-type {
    transition: all var(--transition-fast);
}

@media (max-width: 768px) {
    .badge-application-type {
        font-size: 0.75rem;
        padding: 0.25rem 0.4rem;
    }
}

@media (max-width: 576px) {
    .badge-application-type {
        font-size: 0.7rem;
        padding: 0.2rem 0.3rem;
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }
}

/* Loading States */
.dataTables_processing {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    background: rgba(255, 255, 255, 0.95) !important;
    padding: 20px !important;
    border-radius: var(--border-radius) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
    z-index: 1050 !important;
}

/* Utility Classes */
.text-nowrap {
    white-space: nowrap !important;
}

.text-truncate-mobile {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

@media (max-width: 576px) {
    .text-truncate-mobile {
        max-width: 100px;
    }
}

/* Print Styles */
@media print {
    .sidebar,
    .export-excel-btn,
    .dataTables_filter,
    .dataTables_length,
    .dataTables_info,
    .dataTables_paginate,
    .btn-fixed-end {
        display: none !important;
    }
    
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    
    .table {
        font-size: 11px;
    }
    
    .card {
        border: 1px solid #000;
        box-shadow: none;
    }
}

/* Accessibility Improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus States */
.btn:focus,
.form-control:focus,
.dataTables_filter input:focus {
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    border-color: var(--primary-color);
    outline: none;
}

/* ===== COMPREHENSIVE DEVICE-SPECIFIC ENHANCEMENTS ===== */

/* Ultra-wide screens (1920px+) */
@media (min-width: 1920px) {
    .main-content {
        max-width: 1800px;
        margin-left: auto;
        margin-right: auto;
        padding-left: var(--sidebar-width);
    }
    
    .container-fluid {
        max-width: 1600px;
        margin: 0 auto;
    }
    
    .dataTables_wrapper {
        font-size: 1rem;
    }
    
    .table td, .table th {
        padding: 0.875rem;
    }
}

/* Large Desktop (1440px to 1919px) */
@media (min-width: 1440px) and (max-width: 1919px) {
    .main-content {
        padding: 25px;
    }
    
    .card-body {
        padding: 25px;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 1rem;
        margin: 0 3px;
    }
}

/* Standard Desktop (1200px to 1439px) */
@media (min-width: 1200px) and (max-width: 1439px) {
    .main-content {
        padding: 20px;
    }
    
    .card-body {
        padding: 20px;
    }
}

/* Large Tablet Landscape (1024px to 1199px) */
@media (min-width: 1024px) and (max-width: 1199px) {
    .sidebar {
        width: 260px;
    }
    
    .main-content {
        margin-left: 260px;
        padding: 18px;
    }
    
    .card-body {
        padding: 18px;
    }
    
    .dataTables_wrapper .dataTables_filter input {
        width: 250px;
    }
}

/* iPad Pro / Large Tablet Portrait (768px to 1023px) */
@media (min-width: 768px) and (max-width: 1023px) {
    .main-content {
        margin-left: 0 !important;
        padding: 15px;
    }
    
    .sidebar {
        transform: translateX(-100%);
        width: 280px;
        z-index: 1040;
    }
    
    .card {
        margin-bottom: 15px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .table td, .table th {
        padding: 0.6rem;
        font-size: 0.9rem;
    }
    
    /* Tablet-specific DataTables */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        float: none;
        text-align: center;
        margin-bottom: 10px;
    }
    
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        float: none;
        text-align: center;
        margin-top: 10px;
    }
}

/* Standard Tablet Portrait (481px to 767px) */
@media (min-width: 481px) and (max-width: 767px) {
    body {
        font-size: 14px;
    }
    
    .main-content {
        padding: 12px;
    }
    
    .card-body {
        padding: 12px;
    }
    
    .card-header {
        padding: 10px 12px;
    }
    
    .btn {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }
    
    .table td, .table th {
        padding: 0.5rem;
        font-size: 0.85rem;
    }
    
    .badge-application-type {
        font-size: 0.75rem;
        padding: 0.25rem 0.4rem;
    }
}

/* Large Phone / Small Tablet (376px to 480px) */
@media (min-width: 376px) and (max-width: 480px) {
    body {
        font-size: 13px;
    }
    
    .main-content {
        padding: 10px;
    }
    
    .card-body {
        padding: 10px;
    }
    
    .card-header {
        padding: 8px 10px;
    }
    
    .card-header h4 {
        font-size: 1rem;
    }
    
    .btn {
        font-size: 0.8rem;
        padding: 0.35rem 0.7rem;
    }
    
    .table td, .table th {
        padding: 0.4rem;
        font-size: 0.8rem;
    }
    
    .export-excel-btn {
        width: 100%;
        margin-bottom: 12px;
        font-size: 0.85rem;
    }
    
    /* Compact DataTables pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3rem 0.6rem;
        margin: 0 1px;
        font-size: 0.8rem;
    }
}

/* Standard Phone (321px to 375px) */
@media (min-width: 321px) and (max-width: 375px) {
    body {
        font-size: 12px;
    }
    
    .main-content {
        padding: 8px;
    }
    
    .card-body {
        padding: 8px;
    }
    
    .card-header {
        padding: 6px 8px;
    }
    
    .card-header h4 {
        font-size: 0.95rem;
    }
    
    .btn {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
    }
    
    .table td, .table th {
        padding: 0.3rem;
        font-size: 0.75rem;
    }
    
    .badge-application-type {
        font-size: 0.65rem;
        padding: 0.15rem 0.25rem;
        max-width: 80px;
    }
    
    .iq-navbar-header h4 {
        font-size: 0.9rem !important;
    }
}

/* Small Phone (280px to 320px) */
@media (max-width: 320px) {
    body {
        font-size: 11px;
    }
    
    .main-content {
        padding: 5px;
    }
    
    .card-body {
        padding: 6px;
    }
    
    .card-header {
        padding: 4px 6px;
    }
    
    .card-header h4 {
        font-size: 0.85rem;
    }
    
    .btn {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .btn-sm {
        font-size: 0.65rem;
        padding: 0.15rem 0.3rem;
    }
    
    .table td, .table th {
        padding: 0.25rem;
        font-size: 0.7rem;
    }
    
    .badge-application-type {
        font-size: 0.6rem;
        padding: 0.1rem 0.2rem;
        max-width: 60px;
    }
    
    .export-excel-btn {
        font-size: 0.75rem;
        padding: 0.4rem;
    }
    
    .iq-navbar-header h4 {
        font-size: 0.8rem !important;
    }
    
    /* Ultra-compact DataTables */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.2rem 0.4rem;
        margin: 0;
        font-size: 0.7rem;
    }
    
    .dataTables_wrapper .dataTables_info {
        font-size: 0.7rem;
    }
}

/* ===== ORIENTATION-SPECIFIC STYLES ===== */

/* Landscape mode optimizations */
@media (orientation: landscape) and (max-width: 896px) {
    .iq-navbar-header {
        min-height: 60px !important;
        padding: 0.5rem !important;
    }
    
    .main-content {
        padding-top: 10px !important;
    }
    
    .sidebar {
        width: 250px;
    }
    
    /* Landscape table optimization */
    .table-responsive {
        height: calc(100vh - 200px);
        overflow-y: auto;
    }
    
    .dataTables_wrapper .dataTables_scrollBody {
        max-height: calc(100vh - 300px);
    }
}

/* Portrait mode optimizations */
@media (orientation: portrait) and (max-width: 768px) {
    .table-responsive {
        border: none;
        box-shadow: none;
    }
    
    .dataTables_wrapper {
        overflow-x: auto;
    }
    
    .export-excel-btn {
        position: sticky;
        top: 0;
        z-index: 10;
        margin-bottom: 10px;
    }
}

/* ===== TOUCH DEVICE OPTIMIZATIONS ===== */
@media (pointer: coarse) {
    /* Touch-friendly button sizes */
    .btn {
        min-height: 44px;
        min-width: 44px;
        padding: 0.5rem 1rem;
    }
    
    .btn-sm {
        min-height: 36px;
        min-width: 36px;
    }
    
    /* Larger touch targets for DataTables */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        min-height: 44px;
        padding: 0.5rem 1rem;
        margin: 0 2px;
    }
    
    /* Touch-friendly form controls */
    .form-control,
    .dataTables_filter input {
        min-height: 44px;
        padding: 0.75rem;
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    /* Larger tap targets for table rows */
    .table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    /* Touch scroll optimization */
    .table-responsive {
        -webkit-overflow-scrolling: touch;
        overflow-scrolling: touch;
    }
}

/* ===== HIGH-RESOLUTION DISPLAY OPTIMIZATIONS ===== */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    /* Sharper borders and shadows for Retina displays */
    .card {
        border-width: 0.5px;
    }
    
    .table {
        border-width: 0.5px;
    }
    
    .btn {
        border-width: 0.5px;
    }
    
    /* Enhanced text rendering */
    body {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-rendering: optimizeLegibility;
    }
    
    /* Crisper icons and graphics */
    svg, img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: crisp-edges;
    }
}

/* ===== FOLDABLE DEVICE SUPPORT ===== */
@media (spanning: single-fold-vertical) {
    .main-content {
        display: flex;
        flex-direction: row;
    }
    
    .card {
        flex: 1;
        margin: 10px;
    }
}

@media (spanning: single-fold-horizontal) {
    .main-content {
        display: flex;
        flex-direction: column;
    }
    
    .iq-navbar-header {
        flex-shrink: 0;
    }
}

/* ===== DARK MODE SUPPORT (if enabled) ===== */
@media (prefers-color-scheme: dark) {
    .dataTables_processing {
        background: rgba(33, 37, 41, 0.95) !important;
        color: white !important;
    }
}

/* ===== PERFORMANCE OPTIMIZATIONS ===== */
/* Hardware acceleration for smooth animations */
.sidebar,
.main-content,
.offcanvas,
.modal {
    transform: translateZ(0);
    backface-visibility: hidden;
    perspective: 1000;
}

/* Smooth scrolling for all devices */
html {
    scroll-behavior: smooth;
}

/* Optimize for 60fps animations */
.sidebar,
.export-excel-btn:hover,
.btn:hover {
    will-change: transform;
}

/* ===== ACCESSIBILITY FOR ALL DEVICES ===== */
/* High contrast mode support */
@media (prefers-contrast: high) {
    .table {
        border: 2px solid currentColor;
    }
    
    .btn {
        border: 2px solid currentColor;
    }
    
    .card {
        border: 2px solid currentColor;
    }
}

/* Reduced motion for sensitive users */
@media (prefers-reduced-motion: reduce) {
    .sidebar,
    .main-content,
    .btn,
    .export-excel-btn {
        transition: none !important;
        animation: none !important;
        transform: none !important;
    }
}
</style>
<a class="btn btn-fixed-end btn-warning btn-icon btn-setting" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" role="button" aria-controls="offcanvasExample">
    <svg width="24" viewBox="0 0 24 24" class="animated-rotate" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.8064 7.62361L20.184 6.54352C19.6574 5.6296 18.4905 5.31432 17.5753 5.83872V5.83872C17.1397 6.09534 16.6198 6.16815 16.1305 6.04109C15.6411 5.91402 15.2224 5.59752 14.9666 5.16137C14.8021 4.88415 14.7137 4.56839 14.7103 4.24604V4.24604C14.7251 3.72922 14.5302 3.2284 14.1698 2.85767C13.8094 2.48694 13.3143 2.27786 12.7973 2.27808H11.5433C11.0367 2.27807 10.5511 2.47991 10.1938 2.83895C9.83644 3.19798 9.63693 3.68459 9.63937 4.19112V4.19112C9.62435 5.23693 8.77224 6.07681 7.72632 6.0767C7.40397 6.07336 7.08821 5.98494 6.81099 5.82041V5.82041C5.89582 5.29601 4.72887 5.61129 4.20229 6.52522L3.5341 7.62361C3.00817 8.53639 3.31916 9.70261 4.22975 10.2323V10.2323C4.82166 10.574 5.18629 11.2056 5.18629 11.8891C5.18629 12.5725 4.82166 13.2041 4.22975 13.5458V13.5458C3.32031 14.0719 3.00898 15.2353 3.5341 16.1454V16.1454L4.16568 17.2346C4.4124 17.6798 4.82636 18.0083 5.31595 18.1474C5.80554 18.2866 6.3304 18.2249 6.77438 17.976V17.976C7.21084 17.7213 7.73094 17.6516 8.2191 17.7822C8.70725 17.9128 9.12299 18.233 9.37392 18.6717C9.53845 18.9489 9.62686 19.2646 9.63021 19.587V19.587C9.63021 20.6435 10.4867 21.5 11.5433 21.5H12.7973C13.8502 21.5001 14.7053 20.6491 14.7103 19.5962V19.5962C14.7079 19.088 14.9086 18.6 15.2679 18.2407C15.6272 17.8814 16.1152 17.6807 16.6233 17.6831C16.9449 17.6917 17.2594 17.7798 17.5387 17.9394V17.9394C18.4515 18.4653 19.6177 18.1544 20.1474 17.2438V17.2438L20.8064 16.1454C21.0615 15.7075 21.1315 15.186 21.001 14.6964C20.8704 14.2067 20.55 13.7894 20.1108 13.5367V13.5367C19.6715 13.284 19.3511 12.8666 19.2206 12.3769C19.09 11.8873 19.16 11.3658 19.4151 10.928C19.581 10.6383 19.8211 10.3982 20.1108 10.2323V10.2323C21.0159 9.70289 21.3262 8.54349 20.8064 7.63277V7.63277V7.62361Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        <circle cx="12.1747" cy="11.8891" r="2.63616" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></circle>
    </svg>
</a>
@include('partials.components.setting-offcanvas')
@include('partials.dashboard._scripts')
@include('partials.dashboard._app_toast')
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="formTitle">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="main_form"></div>
    </div>
    </div>
</div>
</div>
