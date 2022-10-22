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

    .info-perusahaan {
      padding: 0 5px;
    }

    .info-perusahaan p {
      font-weight: normal;
      margin: 0;
      font-size: 0.9rem;
    }

    .info-perusahaan h5 {
      font-size: 1.5rem;
      margin: 0;
    }
  </style>
</head>

<body>
  <div class="container">
    <table class="table table-borderless">
      <tr>
        <td>
          <div class="info-perusahaan">
            <div class="logo">
              <img src="{{ public_path('images/icon-perusahaan.png') }}" width="70" height="70">
            </div>
            <h5>{{ config('app.company_name') }}</h5>
            <p>{{ config('app.company_address') }}</p>
            <p>{{ config('app.company_contact') }}</p>
          </div>
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
