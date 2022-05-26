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
  <link href=" {{ mix('css/customer.css') }}" rel="stylesheet">
  <title>Dashboard Manajemen Sales</title>
</head>

<body class="border" id="testing">
  <!--<header class='header_mobile d-flex justify-content-between align-items-center'>
        <div class='d-flex'>
          <button class='btn btn_goback'>
            <span class="iconify" data-icon="eva:arrow-back-fill"></span>
          </button>
          <h1>Judul</h1>
        </div>
        <span class="iconify" data-icon="clarity:shopping-cart-solid"></span>
    </header>-->
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <a href="/customer" style="color: black"><i class="bi bi-arrow-left fs-4"></i></a>
    <a href="/customer" style="text-decoration: none; color: black">
      <h1 class="page_title">salesMan</h1>
    </a>
    <div class="set-header">

      <span class="iconify" id="set-cart-position" data-icon="clarity:shopping-cart-solid"></span>

      <a class="dropdown-toggle link-style" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="bi bi-person-circle fs-2"></i>
      </a>

      <ul class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-danger d-block w-100">Log Out</button>
        </form>

        {{-- <a href="/dashboard/profil/ubahpasswordlama/{{ auth()->user()->id }}" class="btn btn-primary d-block w-100 mt-3">Ubah Password</a>
            <a href="/dashboard/profil/ubah/{{ auth()->user()->id }}" class="btn btn-warning d-block w-100 mt-3">Ubah Profil</a> --}}
      </ul>

      <div>
  </header>

  <div class="container main_content">
    <table class="table">
      <thead>
        <th scope="col">#</th>
        <th scope="col">Nama</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Price</th>
      </thead>
      <tbody>
        @php
          $total = 0;
          $t_items = 0;
        @endphp
        @foreach ($cartItems as $item)
          <tr>
            @php
              $t_items = $item->quantity * $item->price;
              $total += $t_items;
            @endphp
            <th scope="row">{{ $loop->index + 1 }}</th>
            <td>{{ $item->name }}</td>
            <td>{{ $item->quantity . ' X ' . $item->price }}</td>
            <td>{{ $t_items }}</td>

          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <td colspan="3" class="table-active">Sub-Total</td>
        <td>{{ $total }}</td>
      </tfoot>
    </table>
    <a href="/customer/cart/tambahorder?route=customerOrder" type="button" class="btn btn-success">submit</a>
  </div>

  <footer class='footer_mobile d-flex justify-content-between align-items-center'>
    <div class="container">
      <div class="row">
        <div class="col-3 d-flex flex-column align-items-center">
          <a href="/customer" class="link-style"><i class="bi bi-house-door-fill fs-3"></i></a>
          <p class="fw-bold"><a href="/customer" class="link-style">Beranda</a></p>
        </div>
        <div class="col-3 d-flex flex-column align-items-center">
          <a href="/customer/produk" class="link-style"><i class="bi bi-cart-fill fs-3"></i></a>
          <p class="fw-bold"><a href="/customer/produk" class="link-style">Produk</a></p>
        </div>
        <div class="col-3 d-flex flex-column align-items-center">
          <a href="/customer/event" class="link-style"><i class="bi bi-calendar-check-fill fs-3"></i></a>
          <p class="fw-bold"><a href="/customer/event" class="link-style">Event</a></p>
        </div>
        <div class="col-3 d-flex flex-column align-items-center">
          <a href="/customer/produk" class="link-style"><i class="bi bi-person-fill fs-3"></i></a>
          <p class="fw-bold"><a href="/customer/produk" class="link-style">Profil</a></p>
        </div>
      </div>
    </div>
  </footer>



  <script src="{{ mix('js/bootstrap.js') }}"></script>
  <script src="{{ mix('js/main.js') }}"></script>
  <script src="{{ mix('js/d_customer.js') }}"></script>
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</body>

</html>
