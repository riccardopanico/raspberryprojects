<aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4" style="zoom: 1.3;">
    <a href="#" class="brand-link">
        <img src="{{ asset('img/niva.png') }}" alt="Niva Logo" class="brand-image">
    </a>
    <div class="sidebar">
        <nav class="mt-3" style="zoom: 1.35;">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if(auth()->check())
                    <li class="nav-header text-center text-muted font-italic pt-0">
                        @php $ip = trim(shell_exec('ip addr show | grep "inet " | grep -v "127.0.0.1" | cut -d" " -f6 | cut -d"/" -f1')) @endphp
                        {{ $ip }}
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('home') }}"
                        class="nav-link no-border {{ request()->routeIs('home') || request()->path() == '/' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>HOME</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('impostazioni') }}"
                        class="nav-link no-border {{ request()->routeIs('impostazioni') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>IMPOSTAZIONI</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    @if(auth()->check())
    <div class="sidebar-custom d-flex justify-content-center align-items-center">
        <a href="#" class="btn btn-block btn-danger btn-lg" style="font-weight: bold;" id="arresta"
            data-toggle="modal" onclick="openModal('arresta')">
            <i class="fas fa-power-off pr-2"></i>ARRESTA
        </a>
    </div>
    @endif
</aside>
