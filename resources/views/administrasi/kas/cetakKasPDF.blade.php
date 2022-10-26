<html>

<head>
  <title>{{ $title }}</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <style type="text/css">
    table tr td,
    table tr th {
      font-size: 10pt;
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

  <h4 class="mb-4 text-center">{{ $title }} </h4>
  <br>

  <table class="table table-bordered mt-4">
    <thead>
      <tr>
        <th scope="col" class="text-center">No</th>
        <th scope="col" class="text-center">Tgl</th>
        <th scope="col" class="text-center">Cash Account</th>
        <th scope="col" class="text-center">Nama</th>
        <th scope="col" class="text-center">Kontak</th>
        <th scope="col" class="text-center">Keterangan1</th>
        <th scope="col" class="text-center">Keterangan2</th>
        <th scope="col" class="text-center">Debet</th>
        <th scope="col" class="text-center">Kredit</th>
        <th scope="col" class="text-center" style="width: 8%">Saldo</th>
        <th scope="col" class="text-center">No. Bukti</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($manykas as $kas)
        <tr>
          <td>{{ $loop->iteration ?? null }}</td>
          <td>{{ $kas['original']->tanggal ?? null }}</td>
          <td>{{ $kas['original']->linkCashAccount->nama ?? null }}</td>
          <td>{{ $kas['original']->linkStaff->nama ?? null }}</td>
          <td>{{ $kas['original']->kontak ?? null }}</td>
          <td>{{ $kas['original']->keterangan_1 ?? null }}</td>
          <td>{{ $kas['original']->keterangan_2 ?? null }}</td>
          @if ($kas['original']->debit_kredit != null)
            @if ($kas['original']->debit_kredit == '1')
              <td class="text-end">{{ $kas['original']->uang ?? null }}</td>
              <td class="text-end"></td>
            @elseif ($kas['original']->debit_kredit == '-1')
              <td class="text-end"></td>
              <td class="text-end">{{ $kas['original']->uang ?? null }}</td>
            @endif
          @else
            <td class="text-end"></td>
            <td class="text-end"></td>
          @endif

          <td class="text-end">{{ $kas['totalKas'] }}</td>
          <td>{{ $kas['original']->no_bukti ?? null }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
