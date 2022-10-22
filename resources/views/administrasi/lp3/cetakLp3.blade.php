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

    .text-right {
      text-align: right;
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
        <th class="text-center" style="width: 10%">Tanggal</th>
        <th class="text-center">Invoice</th>
        <th class="text-center">Nama Penagih</th>
        <th class="text-center" style="width: 13%">Status</th>
        <th class="text-center">Jumlah Inv Dibayarkan</th>
        <th class="text-center">Total Pembayaran</th>
        <th class="text-center">Total Invoice</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($lp3s as $lp3)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $lp3->linkInvoice->linkOrder->linkCustomer->nama ?? null }}</td>
          <td>{{ date('d-m-Y', strtotime($lp3->tanggal)) }}</td>
          <td>{{ $lp3->linkInvoice->nomor_invoice }}</td>
          <td>{{ $lp3->linkStaffPenagih->nama }}</td>
          @if ($lp3->status_enum ?? null)
            <td>{{ $lp3->status_enum == '1' ? 'Sudah Ditagih' : 'Belum Ditagih' }}</td>
          @else
            <td></td>
          @endif

          @php
            $isHasPembayaranInDate = false;
            $isHasPembayaranInInvoice = false;
          @endphp

          @foreach ($pembayarans as $pembayaran)
            @if ($pembayaran->id == $lp3->linkInvoice->id &&
                $pembayaran->tanggal == $lp3->tanggal &&
                $pembayaran->id_staff_penagih == $lp3->linkStaffPenagih->id)
              <td>
                <p class="text-right">{{ $pembayaran->jml_pembayaran }}</p>
              </td>
              @php
                $isHasPembayaranInDate = true;
              @endphp
            @endif
          @endforeach

          @if ($isHasPembayaranInDate == false)
            <td></td>
          @endif


          @foreach ($invoices as $invoice)
            @if ($invoice->id == $lp3->linkInvoice->id)
              <td>
                <p class="text-right">{{ $invoice->total_bayar }}</p>
              </td>
              @php
                $isHasPembayaranInInvoice = true;
              @endphp
            @endif
          @endforeach

          @if ($isHasPembayaranInInvoice == false)
            <td>
              <p class="text-right">0</p>
            </td>
          @endif

          <td>
            <p class="text-right">{{ $lp3->linkInvoice->harga_total }}</p>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
