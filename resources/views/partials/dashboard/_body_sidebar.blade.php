<aside class="sidebar sidebar-default navs-rounded-all" style="background-color: #ffffff; border-right: 2px solid #198754;">
    <div class="sidebar-header d-flex align-items-center justify-content-center py-3">
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center" style="background-color: #198754; padding: 10px; border-radius: 8px;">
            <img src="{{ asset('images/logo.png') }}" 
                 alt="Site Logo" 
                 width="140" 
                 class="logo-global img-fluid">
        </a>
    </div>

    <div class="sidebar-body pt-0 data-scrollbar">
        <div class="sidebar-list" id="sidebar">
            @include('partials.dashboard.vertical-nav') 
        </div>
    </div>

    
</aside>
