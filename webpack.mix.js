const mix = require('laravel-mix');

require('dotenv').config();

mix.setResourceRoot('../');
mix.setPublicPath('public');

mix.js('resources/assets/scripts/app.js', 'scripts');
mix.sass('resources/assets/styles/app.scss', 'styles');
mix.sass('resources/assets/styles/admin/admin.scss', 'styles');
mix.styles([
    'node_modules/alertifyjs/build/css/alertify.min.css',
    'public/styles/app.css'
], 'public/styles/main.css');

mix.version();
