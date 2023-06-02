<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>salesMan</title>
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
  <link href=" {{ mix('css/sales.css') }}" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/icon-perusahaan.png') }}">
  <!-- PWA  -->
  <meta name="theme-color" content="#007bff" />
  <link rel="apple-touch-icon" href="{{ asset('images/icon-perusahaan.png') }}">
  <link rel="manifest" href="{{ asset('/manifest.json') }}">
  {{-- icon --}}
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</head>

<body>

  <div id="app">
    @yield('main_content')
  </div>

  <script src="{{ mix('js/bootstrap.js') }}"></script>
  <script src="{{ mix('js/react.js') }}"></script>
  <script src="{{ asset('/sw.js') }}"></script>
  <script>
    if (!navigator.serviceWorker.controller) {
      navigator.serviceWorker.register("/sw.js").then(function(reg) {
        console.log("Service worker has been registered for scope: " + reg.scope);
      });
    }
  </script>
</body>

</html>
