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
        @if(auth()->check())
        <li class="nav-item" style="zoom: 1.23;">
            <a style="padding: 0 1.5vw; font-size: 28px;/*  color: rgba(0,0,0,.5); */" class="nav-link" href="{{ route('logout') }}" role="button">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
        @endif
    </ul>
</nav>
