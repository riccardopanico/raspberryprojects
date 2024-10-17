<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="{{ asset('img/niva.png') }}" alt="Niva Logo" class="brand-image">
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i><p>HOME</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('impostazioni') }}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i><p>IMPOSTAZIONI</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
