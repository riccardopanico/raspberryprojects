<aside class="main-sidebar sidebar-dark-primary elevation-4" style="zoom: 1.3;">
    <a href="#" class="brand-link">
        <img src="{{ asset('img/niva.png') }}" alt="Niva Logo" class="brand-image">
    </a>
    <div class="sidebar">
        <nav class="mt-3" style="zoom: 1.35;">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
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
                <li class="nav-item">
                    <a href="{{ route('manuale') }}"
                        class="nav-link no-border {{ request()->routeIs('manuale') ? 'active' : '' }}">
                        <i class="fas fa-file-pdf pl-2 pr-1"></i>
                        <p>MANUALE D'USO</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('campionatura') }}" class="nav-link no-border {{ request()->routeIs('campionatura') ? 'active' : '' }}">
                        <i class="fas fa-flask pl-2 pr-1"></i>
                        <p>CAMPIONATURA</p>
                    </a>
                </li>
                <li class="nav-item">
                    <div class="col-sm-4 col-md-2">
                        <div class="color-palette-set mt-3">
                            <button type="button" class="btn btn-block btn-danger btn-lg" style="font-weight: bold;"
                                id="arresta" data-toggle="modal" onclick="openModal('arresta')">
                                <i class="fas fa-power-off pr-2"></i>ARRESTA</button>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</aside>
