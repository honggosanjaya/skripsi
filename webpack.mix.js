const mix = require('laravel-mix');


// package
mix
  .js('resources/js/bootstrap.js', 'public/js/bootstrap.js')
  .sass('resources/sass/app.scss', 'public/css/bootstrap.css')
  .sass('resources/sass/dashboard.scss', 'public/css/dashboard.css')
  .sass('resources/sass/sales.scss', 'public/css/sales.css')
  .sass('resources/sass/customer.scss', 'public/css/customer.css')
  .sass('resources/sass/supervisor.scss', 'public/css/supervisor.css');
// react
mix
  .js('resources/js/app.js', 'public/js/react.js')
  .js('resources/js/reactView.js', 'public/js/react.js')
  .react()


//custom-example
mix
  .styles(
    [

    ],
    "public/custom/css/main.css"
  )
  .js(
    [
      'resources/js/main.js',
      'resources/js/dashboard.js',
      'resources/js/eventHandle.js',
      'resources/js/profile.js',
      'resources/js/product.js',
      'resources/js/retur.js',
    ]
    , 'public/js/main.js'
  );


mix.js('resources/js/app.js', 'public/js/app.js')
  .postCss('resources/css/app.css', 'public/css/app.css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ]);

