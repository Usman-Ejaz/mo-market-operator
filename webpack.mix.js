const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */
// Javascript
mix.js('resources/js/bootstrap.bundle.min.js', 'public/js');
mix.js('resources/js/adminlte.js', 'public/admin/js/');
mix.js('resources/js/dashboard.js', 'public/admin/js');
mix.js('resources/plugins/chart.js/Chart.min.js', 'public/admin/plugins/chartjs/chart.min.js');
mix.js('resources/js/demo.js', 'public/admin/demo.min.js');

// Css
mix.css('resources/css/adminlte.css', 'public/admin/css/');
mix.css('resources/plugins/fontawesome/css/all.min.css', 'public/admin/plugins/fontawesome/');
mix.css('resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css', 'public/admin/plugins/icheck-bootstrap/');


if (mix.inProduction()) {
    mix.version();
}