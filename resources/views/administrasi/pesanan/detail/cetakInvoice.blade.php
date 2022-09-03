<html>

<head>
  <title>Invoice - {{ $order->linkInvoice->nomor_invoice ?? null }}</title>

  <style type="text/css">
    table {
      border-collapse: collapse;
      width: 100%;
    }

    td,
    th {
      border: 1px solid #000;
      width: calc(100% / 3);
    }

    .logo {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      background-color: orangered;
      float: left;
    }

    .info-perusahaan p {
      font-weight: normal;
      margin: 0;
    }

    .info-perusahaan h5 {
      font-size: 1.9rem;
      margin: 0;
    }

    h6 {
      font-size: 1.25rem;
    }

    p {
      font-size: 1rem;
    }

    .document-title {
      font-size: 2.2rem;
      margin: 0;
      font-weight: normal;
      text-align: center;
    }

    .center-text {
      text-align: center;
    }

    .margin0 {
      margin: 0;
    }

    .uppercase-text {
      text-transform: uppercase;
    }

    .td-small {
      /* width: calc(100% / 9); */
      width: 10%;
      max-width: 10%;
    }

    .td-medium {
      /* width: calc(100% / 6); */
      width: 16.7%;
      max-width: 16.7%;
    }

    .td-large {
      /* width: calc(100% / 3); */
      width: 33.33%;
      max-width: 33.33%;
    }

    .margintop {
      margin-top: 0.5rem;
    }

    .order-item td {
      border-top: 0;
      border-bottom: 0;
    }

    .order-item-big td {
      border-top: 0;
      border-bottom: 0;
      padding-top: 2rem;
      padding-bottom: 2rem;
    }

    .font-bold {
      font-weight: bold;
    }

    .absolute-top-left {
      position: absolute;
      top: 0;
      left: 0;
    }

    .relative-position {
      position: relative;
    }
  </style>
</head>

<body>
  <table>
    <tr>
      <td colspan="3" rowspan="4" class="td-small" align="right">
        <div class="info-perusahaan">
          <div class="logo">
          </div>
          <h5>UD. SURYA&nbsp;</h5>
          <p>TOBA E5 / 15 SWJJ - MLG&nbsp;</p>
          <p>TLP 0341-718732, 726025&nbsp;</p>
          <p>FAX. 0341-720035&nbsp;</p>
        </div>
      </td>
      <td colspan="2" rowspan="2" class="td-medium">
        <h5 class="document-title">Faktur Penjualan</h5>
      </td>
      <td class="td-large">
        <p class="center-text margin0">Kepada YTH..</p>
      </td>
    </tr>

    <tr>
      <td class="td-large">
        <h6 class="uppercase-text margin0">{{ $order->linkCustomer->nama ?? null }}</h6>
      </td>
    </tr>

    <tr>
      <td class="td-medium">
        <p class="margin0 center-text">Tgl. Order</p>
      </td>
      <td class="td-medium">
        <p class="margin0 center-text">Tgl. Faktur</p>
      </td>
      <td rowspan="4" class="td-large">
        <p class="margin0">{{ $order->linkCustomer->full_alamat ?? null }}</p>
        <p class="margin0">{{ $order->linkCustomer->linkDistrict->nama }}</p>
        <p class="margin0">{{ $order->linkCustomer->telepon }}</p>
      </td>
    </tr>

    <tr>
      <td class="td-medium">
        <p class="margin0 center-text">{{ date('d/m/Y', strtotime($order->created_at ?? '-')) }}</p>
      </td>
      <td class="td-medium">
        <p class="margin0 center-text">{{ date('d M Y', strtotime($order->linkInvoice->updated_at ?? '-')) }}</p>
      </td>
    </tr>

    <tr>
      <td class="td-small">
        <p class="margin0 center-text">Pengirim</p>
      </td>
      <td class="td-small">
        <p class="margin0 center-text">Salesman</p>
      </td>
      <td class="td-small">
        <p class="margin0 center-text">Kredit/TOP</p>
      </td>
      <td class="td-medium">
        <p class="margin0 center-text">Tgl. JATUH TEMPO</p>
      </td>
      <td class="td-medium">
        <p class="margin0 center-text">No. Faktur</p>
      </td>
    </tr>

    <tr>
      <td class="td-small">
        <p class="margin0 center-text">Adit</p>
      </td>
      <td class="td-small">
        <p class="margin0 center-text">Adit</p>
      </td>
      <td class="td-small">
        <p class="margin0 center-text">NET 14</p>
      </td>
      <td class="td-medium">
        <p class="margin0 center-text">26 Agu 2022</p>
      </td>
      <td class="td-medium">
        <p class="margin0 center-text">PJ22080105</p>
      </td>
    </tr>
  </table>


  <table class="margintop">
    <tr>
      <th>No.</th>
      <th>Barang</th>
      <th>Deskripsi Barang</th>
      <th>Qyt</th>
      <th>Sat</th>
      <th>Harga</th>
      <th>%</th>
      <th>Pokok</th>
      <th>Jumlah</th>
    </tr>

    @foreach ($orderitems as $key => $item)
      <tr class="order-item">
        <td class="center-text">{{ $key + 1 }}</td>
        <td class="center-text">{{ $item->linkItem->kode_barang ?? null }}</td>
        <td class="uppercase-text">{{ $item->linkItem->nama ?? null }}</td>
        <td class="center-text">{{ $item->kuantitas ?? null }}</td>
        <td class="uppercase-text center-text">{{ $item->linkItem->satuan ?? null }}</td>
        <td align="right">{{ number_format($item->harga_satuan ?? 0, 0, '', '.') }}&nbsp;</td>
        <td class="center-text">20</td>
        <td align="right">{{ number_format($item->harga_satuan ?? 0, 0, '', '.') }}&nbsp;</td>
        <td align="right">{{ number_format(($item->harga_satuan ?? 0) * ($item->kuantitas ?? 0), 0, '', '.') }}&nbsp;
        </td>
      </tr>
    @endforeach

    @php
      $subtotal = 0;
      foreach ($orderitems as $item) {
          $subtotal = $subtotal + ($item->kuantitas ?? 0) * ($item->harga_satuan ?? 0);
      }
    @endphp

    <tr class="order-item-big">
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td rowspan="3" colspan="3" class="relative-position">
        <p class="margin0 absolute-top-left">Keterangan :</p>
      </td>
      <td rowspan="3" colspan="3">
        <p class="margin0 center-text">Diterima Oleh</p>
        <br>
        <hr width="50%">
        <p class="margin0 center-text border-top">Tgl:</p>
      </td>
      <td colspan="2" align="right">
        <p class="margin0 font-bold">Total Sub : </p>
      </td>
      <td align="right" class="font-bold">{{ number_format($subtotal ?? 0, 0, '', '.') }}&nbsp;</td>
    </tr>

    @php
      $diskon = $order->linkInvoice->linkEvent->diskon ?? null;
      $potongan = $order->linkInvoice->linkEvent->potongan ?? null;
    @endphp

    <tr>
      <td colspan="2" align="right">
        <p class="margin0 font-bold">Diskon % : </p>
      </td>
      <td align="right" class="font-bold">
        {{ $diskon == null ? number_format($potongan ?? 0, 0, '', '.') : $diskon }}&nbsp;
      </td>
    </tr>

    <tr>
      <td colspan="2" align="right">
        <p class="margin0 font-bold">Total Faktur : </p>
      </td>
      <td align="right" class="font-bold">{{ number_format($order->linkInvoice->harga_total ?? 0, 0, '', '.') }}&nbsp;
      </td>
    </tr>
  </table>

</body>

</html>
