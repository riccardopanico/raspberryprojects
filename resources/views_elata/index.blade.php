<!DOCTYPE html>
<html lang="en">
@include('common.head')

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        @include('common.navbar')
        <div class="content-wrapper">
            @yield('breadcrumb')
            <div class="content">
                <div class="container">
                    @yield('main')
                </div>
            </div>
        </div>
        @include('common.script')
        @yield('script')
    </div>
</body>

</html>
