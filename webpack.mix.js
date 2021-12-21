const mix = require('laravel-mix')

mix.js('resources/js/app.js', 'public/js')
  .vue()
  .sass('resources/sass/app.scss', 'public/css')
  .webpackConfig(require('./webpack.config'))

if (mix.inProduction()) {
  mix.version()
}
