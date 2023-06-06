<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
  <!-- Custom CSS -->
  <link href="{{ mix('css/dashboard.css') }}" rel="stylesheet">
  <link href="{{ mix('css/mobile.css') }}" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/icon-perusahaan.png') }}">
  <!-- PWA  -->
  <meta name="theme-color" content="#007bff" />
  <link rel="apple-touch-icon" href="{{ asset('images/icon-perusahaan.png') }}">
  <link rel="manifest" href="{{ asset('/manifest.json') }}">
  @stack('CSS')

  <title>Dashboard Manajemen Sales</title>
</head>

<body>
  <main class="main-mobile">
    @include('partials/sidebarmobile')
    @include('partials/headermobile')
    @yield('main_content')
  </main>

  {{-- <script src="{{ mix('js/bootstrap.js') }}"></script> --}}
  <script src="{{ mix('js/main.js') }}"></script>
  @stack('JS')
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
