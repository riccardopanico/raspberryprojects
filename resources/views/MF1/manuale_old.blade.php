<!DOCTYPE html>
<html lang="en" {{-- style="height: auto;zoom: 1.25;" --}} style="height: 800px; overflow: hidden;">
@include('MF1.commons.head')

<body class="layout-fixed layout-footer-fixed layout-navbar-fixed sidebar-closed sidebar-collapse">
    <div class="wrapper">
        @include('MF1.commons.navbar')
        @include('MF1.commons.sidebar')
        <div class="content-wrapper p-0" style="zoom: 1.38;">
            <section class="content p-0">
                <div class="container-fluid p-0">
                    <iframe src="{{ asset('pdf/manuale_uso.pdf') }}#toolbar=0" class="full-screen-iframe"></iframe>
                </div>
            </section>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    @include('MF1.commons.modal')
    @include('MF1.commons.script')
    @yield('script')
</body>

</html>
