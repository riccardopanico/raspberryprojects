<!-- Navbar -->
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand" style="position: absolute;">
            <img src="{{ asset('img/niva.png') }}" alt="Logo Niva" class="brand-image " style="opacity: .8">
            <span class="brand-text font-weight-light"></span>
        </a>
        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="true">
                        <i class="text-primary fa-lg fas fa-cog" style="font-size: 2em;"></i>
                        {{-- <i class="text-primary fa-lg fas fa-network-wired"></i> --}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">

                        <span class="dropdown-header" style="text-align: left; line-height: 0.5;"><b class="text-primary">RETE CONNESSA: </b><i id="network_name"></i></span>
                        <span class="dropdown-header" style="text-align: left; line-height: 0.5;"><b class="text-primary">IP: </b><i id="ip_macchina"></i></span>
                        <span class="dropdown-header" style="text-align: left; line-height: 0.5;"><b class="text-primary">SUBNET: </b><i id="subnet"></i></span>
                        <span class="dropdown-header" style="text-align: left; line-height: 0.5;"><b class="text-primary">GATEWAY: </b><i id="gateway"></i></span>
                        <span class="dropdown-header" style="text-align: left; line-height: 0.5;"><b class="text-primary">DNS: </b><i id="dns_nameservers"></i></span>
                        <span class="dropdown-header" style="text-align: left; line-height: 0.5;"><b class="text-primary">IP SERVER: </b><i id="ip_local_server"></i>:<i id="porta_local_server"></i></span>

                        <div class="dropdown-divider"></div>
                        <a href="#" onclick="settingsSave('ip_macchina')" class="dropdown-item">CAMBIA IP</a>
                        <a href="#" onclick="settingsSave('subnet')" class="dropdown-item">CAMBIA SUBNET</a>
                        <a href="#" onclick="settingsSave('gateway')" class="dropdown-item">CAMBIA GATEWAY</a>
                        <a href="#" onclick="settingsSave('dns_nameservers')" class="dropdown-item">CAMBIA DNS</a>
                        <a href="#" onclick="settingsSave('ip_local_server')" class="dropdown-item">CAMBIA IP SERVER</a>
                        <a href="#" onclick="settingsSave('id_macchina')" class="dropdown-item">CAMBIA ID MACCHINA</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" onclick="shutdown()" class="dropdown-item">SPEGNI</a>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#" onclick="retiDisponibili()" aria-expanded="true">
                        <i class="text-primary fa-lg fas fa-wifi" style="font-size: 2em;"></i>
                    </a>
                    <div id="retiDisponibili" class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                        <span class="dropdown-header">RETI DISPONIBILI</span>
                        <div class="dropdown-divider"></div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" style=" padding: 0px; padding-left: 1rem; padding-right: 0; font-size: 24px; ">
                        <b id="id_macchina" class="text-bold badge badge-info"></b>
                    </a>
                </li>
                {{-- <button class="btn btn-block btn-outline-primary" onclick="logout()" role="button">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button> --}}
            </li>
        </ul>

    </div>
</nav>

<!-- /.navbar -->
