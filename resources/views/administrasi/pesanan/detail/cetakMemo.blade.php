<html>

<head>
  <title>Memo - {{ $order->linkInvoice->nomor_invoice }}</title>
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
              @if (config('app.pdf_asset') == 'development')
                <img src="{{ public_path('images/icon-perusahaan.png') }}" width="70" height="70">
              @elseif(config('app.pdf_asset') == 'production')
                <img src="{{ url('images/icon-perusahaan.png') }}" width="70" height="70">
              @endif
            </div>
            <h5>{{ config('app.company_name') }}</h5>
            <p>{{ config('app.company_address') }}</p>
            <p>{{ config('app.company_contact') }}</p>
          </div>
        </td>
      </tr>
    </table>


    <h4 class="mb-4 text-center">Memo Persiapan Barang {{ config('app.company_name') }}</h4>
    <br>


    <h5>Nomor : <span
        class="font-weight-normal">{{ $order->linkInvoice->nomor_invoice ?? null }}/{{ date('d M Y', strtotime($order->linkOrderTrack->waktu_diteruskan ?? '-')) }}</span>
    </h5>

    <h5>Hal : <span class="font-weight-normal">Persiapan barang dari nomor invoice
        {{ $order->linkInvoice->nomor_invoice ?? null }}</span></h5>
    <br>

    <h5 class="text-right">Malang, {{ date('d M Y', strtotime($date ?? '-')) }}</h5>

    <br>
    <h6>Kepada Tenaga Pembantu,</h6>
    <p class="indent">Sehubungan dengan customer yang telah memesan barang dengan nomor invoice
      {{ $order->linkInvoice->nomor_invoice ?? null }},
      pada tanggal {{ date('d M Y', strtotime($order->linkOrderTrack->waktu_diteruskan ?? '-')) }},kami meminta kepada
      saudara
      untuk mempersiapkan barang-barang yang dipesan untuk dilanjutkan ke bagian pengiriman.
    </p>

    <p class="indent">
      Berikut detail barang yang perlu disiapkan :
    </p>

    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th scope="col">Kode Barang</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Jumlah</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td>{{ $item->linkItem->kode_barang ?? null }}</td>
            <td>{{ $item->linkItem->nama ?? null }}</td>
            <td>{{ number_format($item->kuantitas ?? 0, 0, '', '.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <p class="indent">
      Demikian permintaan kami, atas perhatiannya kami ucapkan terima kasih.
    </p>

    <br><br>
    <p class="text-right mt-4">Mengetahui,</p>
    <br><br><br><br>
    <p class="text-right">{{ $administrasi->nama ?? null }}</p>
  </div>
</body>

</html>
