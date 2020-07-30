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

mix.js('resources/js/app.js', 'public/js');

mix.webpackConfig({
    resolve: {
        alias: {
            '@' :  path.resolve(__dirname, 'resources'),
            '@components' :  path.resolve(__dirname, 'resources/js/components'),
            '@store' :  path.resolve(__dirname, 'resources/js/store'),
            '@views' :  path.resolve(__dirname, 'resources/js/views'),
            '@images' :  path.resolve(__dirname, 'resources/img'),
            '@styles' :  path.resolve(__dirname, 'resources/css'),
        },
    }
});
