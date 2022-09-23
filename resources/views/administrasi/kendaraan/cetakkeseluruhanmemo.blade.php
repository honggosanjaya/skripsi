<html>

<head>
  <title>Memo Persiapan Barang</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <style type="text/css">
    table tr td,
    table tr th {
      font-size: 9pt;
    }

    .indent {
      text-indent: 35px;
      text-align: justify;
    }

    h6 {
      font-weight: normal;
    }

    .logo {
      float: left;
    }
  </style>
</head>

<body>
  <div class="container">
    <table class="table table-borderless">
      <tr>
        <td style="width: 300px">
          <img class="logo" src="{{ public_path('images/icon-perusahaan.png') }}" width="70" height="70"
            alt="UD">
          <h5>UD. Mandiri</h5>
          <h6 class="font-weight-normal">Jalan santoso pojok no 2</h6>
          <h6 class="font-weight-normal">(0341) - 726025</h6>
        </td>
      </tr>
    </table>


    <h4 class="mb-4 text-center">Memo Persiapan Kendaraan {{ $vehicle->nama ?? null }}</h4>
    <br>

    <p class="text-right">Malang, {{ date('d M Y', strtotime($date ?? '-')) }}</p>
    <h6>Kepada Tenaga Pembantu,</h6>
    <p class="indent">
      Sehubungan dengan adanya order yang menggunakan kendaraan <b>{{ $vehicle->nama ?? null }}</b> dengan nomor
      kendaraan
      <b>{{ $vehicle->kode_kendaraan ?? null }}</b>, kami meminta kepada saudara untuk mempersiapkan barang-barang yang
      dipesan untuk dilanjutkan ke bagian pengiriman.
    </p>

    @foreach ($invoices as $invoice)
      <p><b>Tabel {{ $loop->iteration }} - {{ $invoice->nomor_invoice ?? null }}</b></p>
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th scope="col" class="text-center">Kode Barang</th>
              <th scope="col" class="text-center">Nama Item</th>
              <th scope="col" class="text-center">Kuantitas</th>
            </tr>
          </thead>
          <tbody>
            @if ($invoice->linkOrder->linkOrderItem ?? null)
              @foreach ($invoice->linkOrder->linkOrderItem as $item)
                <tr>
                  <td>{{ $item->linkItem->kode_barang ?? null }}</td>
                  <td>{{ $item->linkItem->nama ?? null }}</td>
                  <td class="text-center">{{ $item->kuantitas ?? null }}</td>
                </tr>
              @endforeach
            @endif
          </tbody>
        </table>
      </div>
      <hr class="my-4">
    @endforeach

    <br><br>
    <p class="text-right mt-4">Mengetahui,</p>
    <br><br><br><br>
    <p class="text-right">{{ $administrasi->nama ?? null }}</p>
  </div>
</body>

</html>
