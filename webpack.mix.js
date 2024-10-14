const mix = require('laravel-mix').setPublicPath("public");

// Copia AdminLTE
mix.copyDirectory('vendor/almasaeed2010/adminlte', 'public/build/adminlte');

// Unisci i file JavaScript e minificali
mix.scripts([
    'node_modules/jquery-ui-dist/jquery-ui.min.js',
    'node_modules/virtual-keyboard/dist/js/jquery.keyboard.min.js',
    'node_modules/moment/min/moment.min.js',
    'node_modules/socket.io/client-dist/socket.io.min.js',
], 'public/build/js/all.js').minify('public/build/js/all.js');

// Unisci i file CSS e minificali
mix.styles([
    'node_modules/virtual-keyboard/dist/css/keyboard.min.css',
    'node_modules/jquery-ui-dist/jquery-ui.min.css'
], 'public/build/css/all.css').minify('public/build/css/all.css');
