<!DOCTYPE html>
<html lang="en">
@include('commons.head')

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        @include('commons.navbar')
        <div class="content-wrapper">
            @yield('breadcrumb')
            <div class="content">
                <div class="container">
                    @yield('main')
                </div>
            </div>
        </div>
        @include('commons.script')
        @yield('script')
    </div>
</body>

</html>
