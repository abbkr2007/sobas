<ul class="navbar-nav iq-main-menu" id="sidebar">
    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link {{ activeRoute(route('dashboard')) }}"
           href="{{ route('dashboard') }}"
           style="background-color:#28a745; color:#fff !important; border-left:3px solid #1e7e34;">
            <i class="icon" style="color:#fff;">
                <!-- Green Arrow Icon -->
                <svg width="20" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg"
                     style="stroke:#fff;">
                    <path d="M4.25 12.2744L19.25 12.2744"
                          stroke="#fff" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976"
                          stroke="#fff" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
            <span class="item-name" style="color:#fff;">Dashboard</span>
        </a>
    </li>

    @if(auth()->check() && auth()->user()->user_type == 'admin')
    <!-- Users -->
    <li class="nav-item">
        <a class="nav-link {{ activeRoute(route('users.index')) }}"
           href="{{ route('users.index') }}"
           style="color:#28a745;">
            <i class="icon" style="color:#28a745;">
                <svg width="20" viewBox="0 0 24 24" fill="#28a745">
                    <path d="M11.949 12.467C14.285 12.467 16.158 10.583 16.158 8.234C16.158 5.883 14.285 4 11.949 4C9.613 4 7.74 5.883 7.74 8.234C7.74 10.583 9.613 12.467 11.949 12.467Z"></path>
                    <path d="M11.949 14.54C8.499 14.54 5.588 15.104 5.588 17.28C5.588 19.456 8.518 20 11.949 20C15.399 20 18.31 19.436 18.31 17.261C18.31 15.084 15.38 14.54 11.949 14.54Z"></path>
                </svg>
            </i>
            <span class="item-name">Users</span>
        </a>
    </li>
    @endif

    <!-- Logout -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           style="color:#28a745;">
            <i class="icon" style="color:#28a745;">
                <svg width="20" viewBox="0 0 24 24" fill="#28a745">
                    <path d="M15 12L9 6V10H3V14H9V18L15 12Z"></path>
                    <path d="M21 3H12V7H14V5H21V19H14V17H12V21H21C21.55 21 22 20.55 22 20V4C22 3.45 21.55 3 21 3Z"></path>
                </svg>
            </i>
            <span class="item-name">Logout</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </li>
</ul>
