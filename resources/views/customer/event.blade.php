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

      <a href="/customer/cart?route=customerOrder"><span class="iconify" id="set-cart-position"
          data-icon="clarity:shopping-cart-solid"></span></a>

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
    <div class="row">
      <div class="col-8">
        <div class="mt-3 search-box">
          <form method="GET" action="/customer/event/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Event..."
                value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>

            </div>

          </form>
        </div>
      </div>
      
    </div>
    
    <div class="container">
        @foreach($events as $event)
        <div class="row border border-2 my-3">
            <h5>{{ $event->nama }}</h5>
            @if($event->diskon != null)
            <h6>Promo Diskon {{ $event->diskon }} %</h6>
            @else
            <h6>Promo Potongan Rp {{ $event->potongan }}</h6>
            @endif
            <h6>{{ date('d F Y', strtotime($event->date_end)) }}</h6>
            <p class="text-primary fw-bold"><a data-bs-toggle="modal" data-bs-target="#testing">Lihat Detail >>></a></p>            
            
        </div>
        @endforeach
    </div>

    

    <div class="col-4 mt-2">
        
        @foreach($events as $event)
        <!-- Modal -->
        <div class="modal fade" id="testing" tabindex="-1" aria-labelledby="testingLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="testingLabel">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <h5>Nama : {{ $event->nama }}</h5>
                  <h5>ID : {{ $event->id }}</h5>
                  
                  
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-filter-produk"
                  data-bs-dismiss="modal">Close</button>                
              </div>
            </div>
          </div>
        </div>
        @endforeach
       

    </div>
    

    {{ $events->links() }}
    
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
