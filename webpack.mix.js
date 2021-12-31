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

mix.js('resources/js/jquery.min.js', 'public/js').version();
mix.js('resources/js/bootstrap.bundle.min.js', 'public/js').version();
mix.js('resources/js/adminlte.js', 'public/admin/js/').version();
mix.css('resources/css/adminlte.css', 'public/admin/css/').version();
mix.css('resources/plugins/fontawesome/css/all.min.css', 'public/admin/plugins/fontawesome/').version();
mix.css('resources/plugins/icheck-bootstrap/icheck-bootstrap.min.css', 'public/admin/plugins/icheck-bootstrap/').version();
