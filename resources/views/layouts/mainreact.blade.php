<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>

  <link href=" {{ mix('css/sales.css') }}" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/icon-perusahaan.png') }}">

  @stack('CSS')

  <title>salesMan</title>
</head>

<body>
  <main class="page_main">
    @include('partials/headerreact')
    @yield('main_content')
  </main>

  {{-- <script src="{{ mix('js/bootstrap.js') }}"></script> --}}
  <script src="{{ mix('js/main.js') }}"></script>
  @stack('JS')
</body>

</html>
