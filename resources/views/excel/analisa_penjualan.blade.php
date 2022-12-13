<table>
  <thead>
    <tr>
      <td>{{ date('d-m-Y') }}</td>
    </tr>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="4">Analisa Penjualan Pelanggan 6 Bulan Terakhir</td>
    </tr>
    <tr>
      <td colspan="2">Per Tgl. {{ date('d-m-Y') }}</td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">Nama Pelanggan</th>
      @for ($i = 5; $i >= 0; $i--)
        <th style="border:1px solid black; font-weight:bold;">
          {{ date('M Y', strtotime('last day of ' . -$i . 'month')) }}
        </th>
      @endfor
      <th style="border:1px solid black; font-weight:bold;">Total</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($customers as $customer)
      <tr>
        <td style="border:1px solid black; text-align: right;">{{ $customer->nama ?? null }}</td>
        {{-- {{ dd($data['bulan-0']) }} --}}

        @php
          $totalInv = 0;
        @endphp

        @for ($i = 5; $i >= 0; $i--)
          @php
            $totalInvBulanan = 0;
          @endphp

          @foreach ($data['bulan-' . $i] as $dt)
            @if ($customer->id == $dt['id'])
              @if ($dt['link_order_report_analisa'] != [])
                @foreach ($dt['link_order_report_analisa'] as $order)
                  @php
                    $totalInvBulanan += $order['link_invoice']['harga_total'];
                    $totalInv += $order['link_invoice']['harga_total'];
                  @endphp
                @endforeach
                <td style="border:1px solid black; text-align: right;">
                  {{ $totalInvBulanan }}
                </td>
              @else
                <td style="border:1px solid black; text-align: right;">
                  0
                </td>
              @endif
            @endif
          @endforeach
        @endfor

        <td style="border:1px solid black;">{{ $totalInv }}</td>
      </tr>
    @endforeach

    <tr>
      <td style="border:1px solid black;"></td>
      @for ($i = 5; $i >= 0; $i--)
        <td style="border:1px solid black;">{{ $total['bulan-' . $i] }}</td>
      @endfor
      <td style="border:1px solid black;">{{ $total['allTotal'] }}</td>
    </tr>
  </tbody>
</table>
