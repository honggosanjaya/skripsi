<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Manajemen Sales</title>
  @laravelPWA

  {{-- boostrap --}}
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
  <!-- Custom CSS -->
  <link href=" {{ mix('css/dashboard.css') }}" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/icon-perusahaan.png') }}">

  @stack('CSS')
</head>

<body>
  @php
    $agent = Session::get('agent');
  @endphp

  @if (($agent ?? null) && ($agent->isMobile() ?? null))
    <main class="main-mobile">
      @include('partials/sidebarmobile')
      @include('partials/headermobile')
      @yield('main_content')
    </main>
  @else
    @include('partials/sidebar')
    <div class="main-content">
      @include('partials/header')
      <div class="container-fluid">
        <main class="mb-5 pb-5 position-relative">
          @yield('main_content')
        </main>
      </div>
    </div>
  @endif

  {{-- <script src="{{ mix('js/bootstrap.js') }}"></script> --}}
  <script src="{{ mix('js/main.js') }}"></script>
  @stack('JS')
</body>

</html>
