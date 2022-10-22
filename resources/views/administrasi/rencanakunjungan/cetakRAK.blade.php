<html>

<head>
  <title>{{ $document_title }}</title>

  <style type="text/css">
    table {
      border-collapse: collapse;
      width: 100%;
    }

    td,
    th {
      border: 1px solid #000;
      padding: 0 10px;
    }

    thead tr {
      border: 1px solid #000;
      background-color: khaki;
      border-bottom: 1px solid red;
    }

    .logo {
      float: left;
    }

    .table-borderless td,
    .table-borderless th {
      border: 0;
    }

    .info-perusahaan h6 {
      font-weight: normal;
      margin: 0;
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

    .mt-4 {
      margin-top: 1.5rem;
    }

    .text-center {
      text-align: center;
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

  <table class="table mt-4">
    <thead>
      <tr>
        <th class="text-center">No</th>
        <th class="text-center">Nama Customer</th>
        <th class="text-center">Tanggal Direncanakan</th>
        <th class="text-center">Jam Masuk</th>
        <th class="text-center">Jam Keluar</th>
        <th class="text-center">Status</th>
        <th class="text-center">Nama Salesman</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($trip_rak_complete as $key => $data)
        <tr>
          <th scope="row">{{ $key + 1 }}</th>
          <td>{{ $data['trip']->linkCustomer->nama ?? null }}</td>
          @if ($data['rencana_trip']->tanggal ?? null)
            <td>{{ date('d-m-Y', strtotime($data['rencana_trip']->tanggal)) }}</td>
          @else
            <td></td>
          @endif

          @if ($data['trip']->waktu_masuk ?? null)
            <td>{{ date('g:i a', strtotime($data['trip']->waktu_masuk)) }}</td>
          @else
            <td></td>
          @endif

          @if ($data['trip']->waktu_keluar ?? null)
            <td>{{ date('g:i a', strtotime($data['trip']->waktu_keluar)) }}</td>
          @else
            <td></td>
          @endif

          @if ($data['rencana_trip']->tanggal ?? (null && $data['trip']->waktu_masuk ?? null))
            <td>Sudah Dikunjungi</td>
          @else
            <td>Belum Dikunjungi</td>
          @endif
          <td>{{ $data['trip']->linkStaff->nama ?? null }}</td>
        </tr>
      @endforeach

      @foreach ($trip_not_complete as $data)
        <tr>
          <th scope="row">{{ count($trip_rak_complete) + $loop->iteration }}</th>
          <td>{{ $data->linkCustomer->nama ?? null }}</td>
          <td></td>

          @if ($data->waktu_masuk ?? null)
            <td>{{ date('d-m-Y, g:i a', strtotime($data->waktu_masuk)) }}</td>
          @else
            <td></td>
          @endif

          @if ($data->waktu_keluar ?? null)
            <td>{{ date('d-m-Y, g:i a', strtotime($data->waktu_keluar)) }}</td>
          @else
            <td></td>
          @endif

          <td>Dikunjungi tanpa RAK</td>
          <td>{{ $data->linkStaff->nama ?? null }}</td>
        </tr>
      @endforeach

      @foreach ($rak_not_complete as $data)
        <tr>
          <th scope="row">{{ count($trip_rak_complete) + count($trip_not_complete) + $loop->iteration }}</th>
          <td>{{ $data->linkCustomer->nama ?? null }}</td>
          <td>{{ date('d-m-Y', strtotime($data->tanggal)) }}</td>
          <td></td>
          <td></td>
          <td>Belum Dikunjungi</td>
          <td>{{ $data->linkStaff->nama ?? null }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
