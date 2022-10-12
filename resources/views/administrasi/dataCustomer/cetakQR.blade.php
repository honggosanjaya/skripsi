<html>

<head>
  <title>QR-{{ $customer->id }}-{{ $customer->nama }}</title>
  <style type="text/css">
    h1 {
      font-size: 1.3rem;
      margin-bottom: 2rem;
    }

    .text-center {
      text-align: center;
    }

    .qr-code {
      position: relative;
      left: 50%;
      transform: translateX(-50%)''
    }
  </style>
</head>

<body>
  <div class="container">
    <h1 class="text-center"><b>Kode QR untuk {{ $customer->nama }}</b></h1>

    <img src="{{ public_path('/storage/customer/QR-CUST-' . $nama_customer . '.svg') }}" class="qr-code">
    <p>Kode ini diterbitkan oleh {{ config('app.company_name') }} <br> Scan kode ini untuk mencatat kunjungan</p>
  </div>
</body>

</html>
