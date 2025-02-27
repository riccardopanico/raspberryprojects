<aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4" style="zoom: 1.3;">
    <a href="#" class="brand-link">
        <img src="{{ asset('img/niva.png') }}" alt="Niva Logo" class="brand-image">
    </a>
    <div class="sidebar">
        <nav class="mt-3" style="zoom: 1.35;">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if($ip = trim(shell_exec('ip addr show eth0 | grep "inet " | cut -d" " -f6 | cut -d"/" -f1')))
                    <li class="nav-header text-center text-muted font-italic pt-0"> {{ $ip }} </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('home') }}"
                        class="nav-link no-border {{ request()->routeIs('home') || request()->path() == '/' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>HOME</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('reports') }}"
                        class="nav-link no-border {{ request()->routeIs('reports') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>REPORTS</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('campionatura') }}"
                        class="nav-link no-border {{ request()->routeIs('campionatura') ? 'active' : '' }}">
                        <i class="fas fa-flask pl-2 pr-1"></i>
                        <p>CAMPIONATURA</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('manuale') }}"
                        class="nav-link no-border {{ request()->routeIs('manuale') ? 'active' : '' }}">
                        <i class="fas fa-file-pdf pl-2 pr-1"></i>
                        <p>MANUALE D'USO</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('parametri') }}"
                        class="nav-link no-border {{ request()->routeIs('parametri') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>PARAMETRI</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('rete') }}"
                        class="nav-link no-border {{ request()->routeIs('rete') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-network-wired"></i>
                        <p>RETE</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="sidebar-custom d-flex justify-content-center align-items-center">
        <a href="#" class="btn btn-block btn-danger btn-lg" style="font-weight: bold;" id="arresta"
            data-toggle="modal" onclick="openModal('arresta')">
            <i class="fas fa-power-off pr-2"></i>ARRESTA
        </a>
    </div>
</aside>
