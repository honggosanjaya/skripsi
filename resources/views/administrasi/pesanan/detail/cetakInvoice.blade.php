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
      padding: 0 5px;
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

    h6 {
      font-size: 1rem;
    }

    p {
      font-size: 1rem;
    }

    .document-title {
      font-size: 1.5rem;
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
      width: 12%;
      max-width: 12%;
    }

    .td-medium {
      /* width: calc(100% / 6); */
      width: 18%;
      max-width: 18%;
    }

    .td-large {
      /* width: calc(100% / 3); */
      width: 28%;
      max-width: 28%;
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

    .v-align-top {
      vertical-align: top
    }

    .page-break {
      page-break-after: always;
    }
  </style>
</head>

<body>
  @foreach ($orderitems as $key => $item)
    <table>
      <tr>
        <td colspan="3" rowspan="4" class="td-small" align="right">
          <div class="info-perusahaan">
            <div class="logo">
              @if (config('app.pdf_asset') == 'development')
                <img src="{{ public_path('images/icon-perusahaan.png') }}" width="70" height="70">
              @elseif(config('app.pdf_asset') == 'production')
                <img src="{{ url('images/icon-perusahaan.png') }}" width="70" height="70">
              @endif
            </div>
            <h5>{{ config('app.company_name') ?? null }}</h5>
            <p>{{ config('app.company_address') ?? null }}</p>
            <p>{{ config('app.company_contact') ?? null }}</p>
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
        <td rowspan="4" class="td-large v-align-top">
          <p class="margin0">{{ $order->linkCustomer->full_alamat ?? null }}</p>
          <p class="margin0">{{ $order->linkCustomer->linkDistrict->nama ?? null }}</p>
          <p class="margin0">{{ $order->linkCustomer->telepon ?? null }}</p>
        </td>
      </tr>

      <tr>
        <td class="td-medium">
          @if ($order->linkOrderTrack->waktu_order ?? null)
            <p class="margin0 center-text">{{ date('d M Y', strtotime($order->linkOrderTrack->waktu_order)) }}
            </p>
          @endif
        </td>
        <td class="td-medium">
          @if ($order->linkOrderTrack->waktu_berangkat ?? null)
            <p class="margin0 center-text">{{ date('d M Y', strtotime($order->linkOrderTrack->waktu_berangkat)) }}
            </p>
          @endif
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
          <p class="margin0 center-text">TOP</p>
        </td>
        <td class="td-medium">
          <p class="margin0 center-text">Tgl. JATUH TEMPO</p>
        </td>
        <td class="td-medium">
          <p class="margin0 center-text">No. Faktur ({{ $key + 1 }}/{{ count($orderitems) }})</p>
        </td>
      </tr>

      <tr>
        <td class="td-small">
          <p class="margin0 center-text">{{ $order->linkOrderTrack->linkStaffPengirim->nama ?? null }}</p>
        </td>
        <td class="td-small">
          <p class="margin0 center-text">{{ $order->linkStaff->nama ?? null }}</p>
        </td>
        <td class="td-small">
          <p class="margin0 center-text">NET {{ $order->linkInvoice->jatuh_tempo ?? null }}</p>
        </td>
        <td class="td-medium">
          @if (($order->linkOrderTrack->waktu_berangkat ?? null) && ($order->linkInvoice->jatuh_tempo ?? null))
            <p class="margin0 center-text">
              {{ date('d M Y', strtotime($order->linkOrderTrack->waktu_berangkat . ' + ' . $order->linkInvoice->jatuh_tempo . ' days')) }}
            </p>
          @endif
        </td>
        <td class="td-medium">
          <p class="margin0 center-text">{{ $order->linkInvoice->nomor_invoice ?? null }}</p>
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

      @foreach ($orderitems[$key]['data'] as $key2 => $item)
        <tr class="order-item">
          <td class="center-text">{{ $key2 + 1 + $key * 10 }}</td>
          <td class="center-text">{{ $item->linkItem->kode_barang ?? null }}</td>
          <td class="uppercase-text">{{ $item->linkItem->nama ?? null }}</td>
          <td class="center-text">{{ $item->kuantitas ?? null }}</td>
          <td class="uppercase-text center-text">{{ $item->linkItem->satuan ?? null }}</td>

          @if (($order->linkCustomer->linkCustomerType->diskon ?? null) && ($item->harga_satuan ?? null))
            <td align="right">
              {{ number_format(($item->harga_satuan / (100 - $order->linkCustomer->linkCustomerType->diskon)) * 100, 0, '', '.') }}
            </td>
            <td class="center-text">{{ $order->linkCustomer->linkCustomerType->diskon }}</td>
          @else
            <td></td>
            <td></td>
          @endif

          <td align="right">{{ number_format($item->harga_satuan ?? 0, 0, '', '.') }}</td>
          <td align="right">{{ number_format(($item->harga_satuan ?? 0) * ($item->kuantitas ?? 0), 0, '', '.') }}
          </td>
        </tr>
      @endforeach

      @php
        $diskon = $order->linkInvoice->linkEvent->diskon ?? null;
        $potongan = $order->linkInvoice->linkEvent->potongan ?? null;
        
        if ($diskon ?? null) {
            $totaldiskon = (($orderitems[$key]['total_sub'] ?? 0) / 100) * $diskon;
        } else {
            if ($key == 0) {
                $totaldiskon = $potongan;
            } else {
                $totaldiskon = 0;
            }
        }
        
        $total_faktur = ($orderitems[$key]['total_sub'] ?? 0) - ($totaldiskon ?? 0);
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
          <p class="margin0 absolute-top-left">&nbsp;Keterangan :</p>
        </td>
        <td rowspan="3" colspan="3">
          <p class="margin0 center-text">Diterima Oleh</p>
          <br>
          <hr width="50%">
          <p class="margin0 center-text border-top">Tgl:</p>
        </td>
        <td colspan="2" align="right">
          <p class="margin0 font-bold">Total Sub :</p>
        </td>
        <td align="right" class="font-bold">{{ number_format($orderitems[$key]['total_sub'], 0, '', '.') }}</td>
      </tr>

      <tr>
        <td colspan="2" align="right">
          <p class="margin0 font-bold">Diskon {{ $potongan != null ? '' : '%' }} :</p>
        </td>
        <td align="right" class="font-bold">
          {{ number_format($totaldiskon ?? 0, 0, '', '.') }}
        </td>
      </tr>

      <tr>
        <td colspan="2" align="right">
          <p class="margin0 font-bold">Total Faktur :</p>
        </td>
        <td align="right" class="font-bold">{{ number_format($total_faktur ?? 0, 0, '', '.') }}</td>
      </tr>
    </table>
    @if ($key + 1 != count($orderitems))
      <div class="page-break"></div>
    @endif
  @endforeach
</body>

</html>
