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

  <!-- Custom CSS -->
  <link href=" {{ mix('css/dashboard.css') }}" rel="stylesheet">
  @stack('CSS')
  
  <title>Dashboard Manajemen Sales</title>
</head>

<body>
  @include('partials/sidebar')

  <div class="main-content">
    @include('partials/header')
    <div class="container-fluid">
      <main class="mb-5 pb-5">
        <div class="breadcrumbs-container">
          @yield('breadcrumbs')
        </div>
        @yield('main_content')
      </main>
    </div>
  </div>

  <script src="{{ mix('js/bootstrap.js') }}"></script>
  <script src="{{ mix('js/main.js') }}"></script>
  @stack('JS')
</body>

</html>
