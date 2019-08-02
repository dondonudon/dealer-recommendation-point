const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .scripts([
        'node_modules/sweetalert2/dist/sweetalert2.all.js',
        'node_modules/datatables.net/js/jquery.dataTables.js',
        'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js',
        'node_modules/datatables.net-fixedcolumns/js/dataTables.fixedColumns.js',
        'node_modules/datatables.net-fixedcolumns-bs4/js/fixedColumns.bootstrap4.js',
        'node_modules/moment/moment.js',
        'node_modules/daterangepicker/daterangepicker.js',
        'node_modules/slim-select/dist/slimselect.js',
    ], 'public/js/app.js')
    .styles([
        'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css',
        'node_modules/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.css',
        'node_modules/daterangepicker/daterangepicker.css',
        'node_modules/slim-select/dist/slimselect.css',
    ], 'public/css/app.css');
