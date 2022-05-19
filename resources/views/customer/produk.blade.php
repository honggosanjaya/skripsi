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
        <a href="/customer" style="text-decoration: none; color: black"><h1 class="page_title">salesMan</h1></a>
        <div class="set-header">

        <span class="iconify" id="set-cart-position" data-icon="clarity:shopping-cart-solid"></span>
                
        <a class="dropdown-toggle link-style" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

    <div class="container">
        <div class="row">
          <div class="col-8">
            <div class="mt-3 search-box">
              <form method="GET" action="/customer/produk/cari">
                <div class="input-group">
                  <input type="text" class="form-control" name="cari" placeholder="Cari Produk..."
                  value="{{ request('cari') }}">
                  <button type="submit" class="btn btn-primary">Cari</button>   
                  
                </div>
                
              </form>    
            </div>
          </div>
          <div class="col-4 mt-2">
            <i class="bi bi-funnel-fill fs-3"></i>
          </div>
        </div>
        
            
            <div class="row">
                @foreach($items as $item)
                <div class="col-6 my-2">
                    <div class="card" style="width: 10rem;">
                        <img src="..." class="card-img-top" width="100px" height="100px">
                        <div class="card-body">
                          <h5 class="card-title">{{ $item->nama }}</h5>
                          <h5 class="card-title">{{ $item->harga_satuan }}/{{ $item->satuan }}</h5>
                          
                          <button class="btn btn-primary btn-square py-1 px-2">-</button>
                          <input type="number" class="col-4">
                          <button class="btn btn-primary btn-square py-1 px-2">+</button>
                        </div>
                      </div>
                </div>
                @endforeach

                
            </div>
        
    </div>

    <footer class='footer_mobile d-flex justify-content-between align-items-center'>
      <div class="container">
          <div class="row">
              <div class="col-3 d-flex flex-column align-items-center">
                  <a href="/customer/produk" class="link-style"><i class="bi bi-house-door-fill fs-3"></i></a>
                  <p class="fw-bold"><a href="/customer/produk" class="link-style">Beranda</a></p>
              </div>
              <div class="col-3 d-flex flex-column align-items-center">
                  <a href="/customer/produk" class="link-style"><i class="bi bi-cart-fill fs-3"></i></a>
                  <p class="fw-bold"><a href="/customer/produk" class="link-style">Produk</a></p>
              </div>
              <div class="col-3 d-flex flex-column align-items-center">
                  <a href="/customer/produk" class="link-style"><i class="bi bi-calendar-check-fill fs-3"></i></a>
                  <p class="fw-bold"><a href="/customer/produk" class="link-style">Event</a></p>
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
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</body>

</html>

    

