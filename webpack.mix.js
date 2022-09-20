const mix = require('laravel-mix');


// package
mix
  .js('resources/js/bootstrap.js', 'public/js/bootstrap.js')
  .sass('resources/sass/app.scss', 'public/css/bootstrap.css')
  .sass('resources/sass/dashboard.scss', 'public/css/dashboard.css')
  .sass('resources/sass/sales.scss', 'public/css/sales.css')
  .sass('resources/sass/customer.scss', 'public/css/customer.css')
  .sass('resources/sass/supervisor.scss', 'public/css/supervisor.css')
  .sass('resources/sass/administrasi.scss', 'public/css/administrasi.css')
  .sass('resources/sass/owner.scss', 'public/css/owner.css');
// react
mix
  .js('resources/js/app.js', 'public/js/react.js')
  .js('resources/js/reactView.js', 'public/js/react.js')
  .js('resources/js/report.js', 'public/js/report.js')
  .react()

mix.copy('node_modules/chart.js/dist/chart.js', 'public/js/chart.js');


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
    ]
    , 'public/js/main.js'
  );

mix
  .js(
    [
      'resources/js/d_customer.js',
    ]
    , 'public/js/d_customer.js'
  );
mix
  .js(
    [
      'resources/js/administrasi.js',
    ]
    , 'public/js/administrasi.js'
  );
mix
  .js(
    [
      'resources/js/supervisor.js',
    ]
    , 'public/js/supervisor.js'
  );
mix
  .js(
    [
      'resources/js/pengadaan.js',
    ]
    , 'public/js/pengadaan.js'
  );
mix
  .js(
    [
      'resources/js/opname.js',
    ]
    , 'public/js/opname.js'
  );

mix.js('resources/js/app.js', 'public/js/app.js')
  .postCss('resources/css/app.css', 'public/css/app.css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ]);

