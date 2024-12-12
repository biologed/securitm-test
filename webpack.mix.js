const mix = require('laravel-mix');

mix
    .webpackConfig(webpack => {
        return {
            plugins: []
        }
    })
    .copy(['resources/css/favicon.svg'], 'public/css')
    .js(['resources/js/app.js'], 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles(['resources/css/app.css', 'public/css/app.css'],'public/css/app.css')
    .sourceMaps();
