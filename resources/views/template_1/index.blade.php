<!DOCTYPE html>
<html lang="en" {{-- style="height: auto;zoom: 1.25;" --}} style="height: 800px; overflow: hidden;">
@include('template_1.commons.head')

<body class="layout-fixed layout-footer-fixed layout-navbar-fixed sidebar-closed sidebar-collapse">
    <div class="wrapper">
        @include('template_1.commons.navbar')
        @include('template_1.commons.sidebar')
        <div class="content-wrapper" style="zoom: 1.38;">
            {{-- @include('template_1.commons.breadcrumb') --}}
            @yield('breadcrumb')
            <section class="content pt-2">
                <div class="container-fluid">
                    @yield('main')
                </div>
            </section>
        </div>
        {{-- @include('template_1.commons.footer') --}}
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    @include('template_1.commons.modal')
    @include('template_1.commons.script')
    @include('template_1.commons.websocket')
    @yield('script')
</body>

</html>
