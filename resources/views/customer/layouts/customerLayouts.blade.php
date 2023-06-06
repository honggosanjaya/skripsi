<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href=" {{ mix('css/bootstrap.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="icon" href="{{ asset('images/icon-perusahaan.png') }}">
  <link href=" {{ mix('css/customer.css') }}" rel="stylesheet">
  <title>Customer</title>
</head>

<body>
  <main class="page_main">
    @yield('header')

    <div class="container main_content">
      @yield('content')
    </div>

    <div class='nav_bottom fixed-bottom row no-margin'>
      <div class="col d-flex justify-content-center align-items-center">
        <a href="/customer" class="nav_link {{ Request::is('customer') ? 'active' : '' }}">
          <span class="iconify" data-icon="charm:home"></span>
          <p class="mb-0">Beranda</p>
        </a>
      </div>
      <div class="col d-flex justify-content-center align-items-center">
        <a href="/customer/produk" class="nav_link {{ Request::is('customer/produk*') ? 'active' : '' }}">
          <span class="iconify" data-icon="icon-park-outline:ad-product"></span>
          <p class="mb-0">Produk</p>
        </a>
      </div>
      <div class="col d-flex justify-content-center align-items-center">
        <a href="/customer/event" class="nav_link {{ Request::is('customer/event*') ? 'active' : '' }}">
          <span class="iconify" data-icon="bxs:offer"></span>
          <p class="mb-0">Event</p>
        </a>
      </div>
      <div class="col d-flex justify-content-center align-items-center">
        <a href="/customer/profil" class="nav_link {{ Request::is('customer/profil*') ? 'active' : '' }}">
          <span class="iconify" data-icon="carbon:user-profile"></span>
          <p class="mb-0">Profil</p>
        </a>
      </div>
    </div>
  </main>



  {{-- <script src="{{ mix('js/bootstrap.js') }}"></script> --}}
  <script src="{{ mix('js/main.js') }}"></script>
  <script src="{{ mix('js/d_customer.js') }}"></script>
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</body>

</html>
