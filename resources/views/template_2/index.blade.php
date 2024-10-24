<!DOCTYPE html>
<html lang="en" style="height: auto;zoom: 1.25; overflow: hidden;">
@include('template_2.commons.head')

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        @include('template_2.commons.navbar')
        <div class="content-wrapper">
            @yield('breadcrumb')
            <div class="content">
                <div class="container">
                    @yield('main')
                </div>
            </div>
        </div>
        @include('template_2.commons.script')
        @yield('script')
    </div>
</body>

</html>
