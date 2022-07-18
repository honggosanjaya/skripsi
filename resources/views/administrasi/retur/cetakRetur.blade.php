<html>

<head>
  <title>Retur - {{ $retur->no_retur }}</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
  <style type="text/css">
    table tr td,
    table tr th {
      font-size: 9pt;
    }
  </style>
  <div class="container">
    <div class="row mt-3">
      <h4>Retur - {{ $retur->no_retur }}</h4>
    </div>

    <table class="table table-borderless mt-4">
      <tbody>
        <tr>
          <td style="width: 35%">
            <h5>Tanggal : </h5>
          </td>
          <td>{{ date('d-m-Y', strtotime($retur->created_at)) }}</td>
        </tr>
        <tr>
          <td>
            <h5>Nama Customer : </h5>
          </td>
          <td>{{ $retur->linkCustomer->nama }}</td>
        </tr>
        <tr>
          <td>
            <h5>Alamat : </h5>
          </td>
          <td>{{ $retur->linkCustomer->alamat_utama . ' ' . $retur->linkCustomer->alamat_nomor }}</td>
        </tr>
        <tr>
          <td>
            <h5>Wilayah : </h5>
          </td>
          <td>{{ $wilayah[0] }}</td>
        </tr>
        <tr>
          <td>
            <h5>No Telepon : </h5>
          </td>
          <td>{{ $retur->linkCustomer->telepon }}</td>
        </tr>
        <tr>
          <td>
            <h5>Pengirim : </h5>
          </td>
          <td>{{ $retur->linkStaffPengaju->nama }}</td>
        </tr>
        <tr>
          <td>
            <h5>Admin : </h5>
          </td>
          <td>{{ $retur->linkStaffPengonfirmasi->nama ??null }}</td>
        </tr>
        <tr>
          <td>
            <h5>jenis Retur : </h5>
          </td>
          <td>{{ $retur->linkReturType->nama }}</td>
        </tr>
        @if ($retur->tipe_retur == 1)
          <tr>
            <td>
              <h5>Nomor Invoice : </h5>
            </td>
            <td>{{ $retur->linkInvoice->nomor_invoice ?? null }}</td>
          </tr>
        @endif

      </tbody>
    </table>

    <table class="table table-bordered mt-5">
      <thead>
        <tr>
          <th scope="col">Kode Barang</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Satuan Barang</th>
          <th scope="col">Harga Barang</th>
          <th scope="col">Alasan Retur</th>
          @if ($retur->tipe_retur == 1)
            <th scope="col">Potongan Retur</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td>{{ $item->linkItem->kode_barang }}</td>
            <td>{{ $item->linkItem->nama }}</td>
            <td>{{ $item->kuantitas }}</td>
            <td>{{ $item->linkItem->satuan }}</td>
            <td>{{ $item->alasan }}</td>
            @if ($retur->tipe_retur == 1)
              <td>{{ $item->alasan }}</td>
            @endif
            <td>{{ number_format($item->kuantitas * $item->harga_satuan, 0, '', '.') }}</td>
          </tr>
        @endforeach
        <tr>
          <td colspan="6" class="text-center fw-bold">Total Harga</td>
          <td>{{ number_format($total_harga, 0, '', '.') }}</td>
        </tr>
      </tbody>
    </table>

    <table class="table table-borderless text-center mt-5">
      <thead>
        <tr>
          <th scope="col">Penerima,</th>
          <th scope="col"></th>
          <th scope="col">Pengirim,</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="padding:5%"></td>
          <td style="padding:5%"></td>
          <td style="padding:5%"></td>
        </tr>
        <tr>
          <td>{{ $retur->linkCustomer->nama ?? '' }}</td>
          <td></td>
          <td>{{ $retur->linkStaffPengaju->nama }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

</html>
