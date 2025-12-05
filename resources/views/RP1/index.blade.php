<!DOCTYPE html>
<html lang="en" {{-- style="height: auto;zoom: 1.25;" --}} style="overflow: hidden;">
@include(env('APP_NAME') . '.commons.head')

<body class="dark-mode layout-fixed layout-footer-fixed @if(Route::currentRouteName() != 'login') layout-navbar-fixed @endif sidebar-closed sidebar-collapse">
    <div class="wrapper">
        @if (Route::currentRouteName() == 'login')
            @yield('navbar')
        @else
            @include(env('APP_NAME') . '.commons.navbar')
        @endif
        @include(env('APP_NAME') . '.commons.sidebar')
        <div class="content-wrapper" style="zoom: 1.38;">
            @yield('breadcrumb')
            <section class="content @if(Route::currentRouteName() != 'login') pt-2 @else  p-3 @endif">
                <div class="container-fluid">
                    @yield('main')
                </div>
            </section>
        </div>
        {{-- @include(env('APP_NAME') . '.commons.footer') --}}
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    @include(env('APP_NAME') . '.commons.script')
    @if(Route::currentRouteName() != 'login')
        @include(env('APP_NAME') . '.commons.websocket')
        @include(env('APP_NAME') . '.commons.modal')
    @endif
    @yield('script')
</body>

</html>
