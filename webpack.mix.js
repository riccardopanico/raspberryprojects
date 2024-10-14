const mix = require('laravel-mix').setPublicPath("public");

// Copia AdminLTE
mix.copyDirectory('vendor/almasaeed2010/adminlte', 'public/build/adminlte');

// Copia le webfont di FontAwesome
mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/build/webfonts');

// Unisci i file JavaScript e minificali
mix.scripts([
    'node_modules/jquery-ui-dist/jquery-ui.min.js',
    'node_modules/virtual-keyboard/dist/js/jquery.keyboard.min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/socket.io/client-dist/socket.io.min.js',

    'vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js',
    'vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js',
    'vendor/almasaeed2010/adminlte/dist/js/demo.js',

], 'public/build/js/all.js').minify('public/build/js/all.js');

// Unisci i file CSS e minificali
mix.styles([
    'node_modules/virtual-keyboard/dist/css/keyboard.min.css',
    'node_modules/jquery-ui-dist/jquery-ui.min.css',

    'vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css',
    'vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css',

], 'public/build/css/all.css').minify('public/build/css/all.css');
