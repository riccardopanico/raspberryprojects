<nav class="main-header navbar navbar-expand dark-mode" style="zoom: 1.27; border-bottom-color: transparent;">
    <ul class="navbar-nav">
        <li class="nav-item" style="zoom: 1.23;">
            <a style="padding: 0 1.5vw; font-size: 28px;/*  color: rgba(0,0,0,.5); */" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item" style="zoom: 1.23;">
            <a class="nav-link" href="#" role="button" style="padding: 0; font-size: 25px; padding-top: 2px; color: white;">
                <b>NIVA - Gestore Presenze</b>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li id="countdown-nav" class="nav-item">
          <a class="nav-link" href="#" role="button">
            <svg viewBox="0 0 50 50">
              <circle class="countdown-circle" cx="25" cy="25" r="22" />
            </svg>
            <span class="countdown-number">0</span>
          </a>
        </li>
        @if(request()->path() == '/' || Route::currentRouteName() == 'home')
        <li class="nav-item info_badge" style="zoom: 1.23;">
            <a style="padding: 0 1.5vw; font-size: 28px;" class="nav-link" href="javascript:richiediBadge();" role="button">
                <i class="fas fa-info-circle"></i>
            </a>
        </li>
        @endif
        @if(auth()->check())
        <li class="nav-item" style="zoom: 1.23;">
            <a style="padding: 0 1.5vw; font-size: 28px;/*  color: rgba(0,0,0,.5); */" class="nav-link" href="{{ route('logout') }}" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
        @endif
    </ul>
</nav>
