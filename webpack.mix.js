const mix = require('laravel-mix');

// package
mix
  .js('resources/js/bootstrap.js', 'public/js/bootstrap.js')
  .sass('resources/sass/app.scss', 'public/css/bootstrap.css');

// react
mix
  .js('resources/js/app.js', 'public/js/react.js')
  .react()


//custom-example
mix
  .styles(
    [

    ],
    "public/css/main.css"
  )
  .js(
    [
      'resources/js/main.js'
    ]
    , 'public/js/main.js'
  );


mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
  require('postcss-import'),
  require('tailwindcss'),
  require('autoprefixer'),
]);

