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
        <h1 class='page_title'>salesMan</h1>
        <div class="set-header">
        <span class="iconify" id="set-position" data-icon="clarity:shopping-cart-solid"></span>
        <i class="bi bi-person-circle fs-2"></i>     
        <div>  
    </header>

    <div class="container">
        <h1>Ini dashboard customer</h1>

        <p class="fw-bold">Produk Favorit Bulan Mei</p>
    
        <table border="1" class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Kode Barang</th>
                    <th scope="col">Nama Barang</th>
                    <th scope="col">Penjualan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>K001</td>
                    <td>Sapu</td>
                    <td>5000000</td>
                </tr>
                <tr>
                    <td>K002</td>
                    <td>Lidi</td>
                    <td>6000000</td>
                </tr>
            </tbody>
        </table>
    
    </div>

    

  <script src="{{ mix('js/bootstrap.js') }}"></script>
  <script src="{{ mix('js/main.js') }}"></script>
  <script src="https://code.iconify.design/2/2.0.3/iconify.min.js"></script>
</body>

</html>

    

