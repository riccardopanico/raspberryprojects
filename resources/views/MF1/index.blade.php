<!DOCTYPE html>
<html lang="en" {{-- style="height: auto;zoom: 1.25;" --}} style="height: 800px; overflow: hidden;">
@include('MF1.commons.head')

<body class="layout-fixed layout-footer-fixed layout-navbar-fixed sidebar-closed sidebar-collapse">
    <div class="wrapper">
        @if (Route::currentRouteName() == 'login')
            @yield('navbar')
        @else
            @include('MF1.commons.navbar')
        @endif
        @include('MF1.commons.sidebar')
        <div class="content-wrapper" style="zoom: 1.38;">
            @yield('breadcrumb')
            <section class="content pt-2">
                <div class="container-fluid p-0">
                    @yield('main')
                </div>
            </section>
        </div>
        {{-- @include('MF1.commons.footer') --}}
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    @include('MF1.commons.script')
    @if(Route::currentRouteName() != 'login')
        @include('MF1.commons.websocket')
        @include('MF1.commons.modal')
    @endif
    @yield('script')
</body>

</html>
