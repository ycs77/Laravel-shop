const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/js/admin/products-form.js', 'public/js/admin')
   .sass('resources/sass/admin.scss', 'public/css');

mix.version();
