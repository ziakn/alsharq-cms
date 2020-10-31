const mix = require('laravel-mix');

mix.webpackConfig({
   watchOptions: {
     ignored: /node_modules/
   }
 })

mix.js('resources/js/app.js', 'public/js')
   .extract(['vue','vuetify','vue-moment'])
   .version();
