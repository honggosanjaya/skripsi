<html>

<head>
  <title>Surat Jalan - {{ $date ?? '-' }}</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <style type="text/css">
    table tr td,
    table tr th {
      font-size: 9pt;
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
        <td style="width: 300px">
          <table>
            <tr>
              <td>
                @if (config('app.pdf_asset') == 'development')
                  <img src="{{ public_path('images/icon-perusahaan.png') }}" width="70" height="70">
                @elseif(config('app.pdf_asset') == 'production')
                  <img src="{{ url('images/icon-perusahaan.png') }}" width="70" height="70">
                @endif
              </td>
              <td>
                <div class="info-perusahaan">
                  <h5>{{ config('app.company_name') }}</h5>
                  <p>{{ config('app.company_address') }}</p>
                  <p>{{ config('app.company_contact') }}</p>
                </div>
              </td>
            </tr>
          </table>
        </td>
        <td>
          <h6>Kepada Yth
            ............................................
            ............................................
            ............................................
          </h6>
        </td>
      </tr>
    </table>
    <h5 class="text-center">Surat Jalan {{ config('app.company_name') }}</h5>

    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th scope="col">
            <p class="mb-0">Kode Barang</p>
          </th>
          <th scope="col">
            <p class="mb-0">Nama Barang</p>
          </th>
          <th scope="col">
            <p class="mb-0">Kuantitas</p>
          </th>
          <th scope="col">
            <p class="mb-0">Keterangan</p>
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td>
              <p class="mb-0">{{ $item->linkItem->kode_barang ?? null }}</p>
            </td>
            <td>
              <p class="mb-0 text-capitalize">{{ $item->linkItem->nama ?? null }}</p>
            </td>
            <td>
              <p class="mb-0">{{ number_format($item->kuantitas ?? 0, 0, '', '.') }}</p>
            </td>
            <td style="border:none"></td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <br>
    <h6 class=" text-right">Malang, {{ date('d M Y', strtotime($date ?? '-')) }}</h6>

    <table class="table table-borderless text-center mt-5">
      <thead>
        <tr>
          <th scope="col">Penerima,</th>
          <th scope="col">Pengirim,</th>
          <th scope="col">Mengetahui,</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="padding:3%"></td>
          <td style="padding:3%"></td>
          <td style="padding:3%"></td>
        </tr>
        <tr>
          <td>{{ $order->linkCustomer->nama ?? null }}</td>
          <td>{{ $pengirim->nama ?? '' }}</td>
          <td>{{ $mengetahui->nama ?? '' }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

</html>
