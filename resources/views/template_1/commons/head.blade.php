<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Fixed Sidebar</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('build/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('build/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('build/adminlte/dist/css/adminlte.min.css') }}">

    <style>
        .wrapper {
            overflow: hidden;
        }
        body {
            -webkit-user-select: none; /* Per Chrome, Safari, e Opera */
            -moz-user-select: none;    /* Per Firefox */
            -ms-user-select: none;     /* Per Internet Explorer */
            user-select: none;         /* Standard */
        }
    </style>

    @yield('css')
</head>
