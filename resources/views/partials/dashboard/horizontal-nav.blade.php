<nav id="navbar_main" class="mobile-offcanvas nav navbar navbar-expand-xl hover-nav horizontal-nav mx-md-auto">
   <div class="container-fluid">
      <div class="offcanvas-header">
         <div class="navbar-brand">
            <img src="{{ asset('images/logo.png') }}" alt="SOBAS Portal Logo" style="width: 38px; height: 38px; object-fit: contain;" class="me-2">
            <h4 class="logo-title" style="font-size: 1rem; line-height: 1.2; font-weight: 700;">SOBAS Portal</h4>
         </div>
         <button class="btn-close float-end"></button>
      </div>
      <ul class="navbar-nav">
         <li class="nav-item"><a class="nav-link active" href="{{route('menu-style.horizontal')}}"> Horizontal </a></li>
         <li class="nav-item"><a class="nav-link" href="{{route('menu-style.dualhorizontal')}}"> Dual Horizontal </a></li>
         <li class="nav-item"><a class="nav-link" href="{{route('menu-style.dualcompact')}}"><span class="item-name">Dual Compact</span></a></li>
         <li class="nav-item"><a class="nav-link" href="{{route('menu-style.boxed')}}"> Boxed Horizontal </a></li>
         <li class="nav-item"><a class="nav-link" href="{{route('menu-style.boxedfancy')}}"> Boxed Fancy</a></li>

      </ul>
   </div> <!-- container-fluid.// -->
</nav>
