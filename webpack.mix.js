const mix = require('laravel-mix').setPublicPath("public");

// Copia AdminLTE
mix.copyDirectory('vendor/almasaeed2010/adminlte', 'public/build/adminlte');

// Copia le webfont di FontAwesome
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/build/webfonts');
mix.copyDirectory('node_modules/kioskboard/dist', 'public/build/kioskboard/dist');

// Unisci i file JavaScript e minificali
mix.scripts([
    'vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
    'vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'vendor/almasaeed2010/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    'vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js',
    'node_modules/jquery-ui-dist/jquery-ui.min.js',
    'node_modules/virtual-keyboard/dist/js/jquery.keyboard.min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/socket.io/client-dist/socket.io.min.js',
    'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.js',
    'node_modules/kioskboard/dist/kioskboard-2.3.0.min.js',

    'resources/js/custom.js',

], 'public/build/js/all.js');

// Unisci i file CSS e minificali
mix.styles([
    'node_modules/virtual-keyboard/dist/css/keyboard.min.css',
    'node_modules/jquery-ui-dist/jquery-ui.min.css',

    'https://cdn.jsdelivr.net/npm/sweetalert2@11',
    'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback',
    'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
    'vendor/almasaeed2010/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
    'vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css',
    'vendor/almasaeed2010/adminlte/plugins/sweetalert2/sweetalert2.min.css',
    'node_modules/kioskboard/dist/kioskboard-2.3.0.min.css',

    'resources/css/custom.css',

], 'public/build/css/all.css');

mix.version();