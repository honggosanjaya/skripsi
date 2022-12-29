<table>
  <tr>
    <td colspan="3" style="font-size: 16px; font-weight: bold;">UD. SURYA</td>
  </tr>
  <tr>
    <td colspan="8" style="font-size: 14px; font-weight: bold;">Laporan Aktivitas Kunjungan ( LAK / DSR)</td>
  </tr>
  <tr></tr>
  <tr>
    <td colspan="3" style="font-size: 12px; font-weight: bold;">Bagian : SALES</td>
    <td colspan="3" style="font-size: 12px; font-weight: bold;">Tanggal : {{ $trip['date'] ?? null }}</td>
    <td colspan="2" style="font-size: 12px; font-weight: bold;">SF/MD : {{ $trip['sales'] ?? null }}</td>
  </tr>

  <tr>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" rowspan="2">
      &nbsp;NO.&nbsp;
    </th>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" rowspan="2">
      &nbsp;NAMA&nbsp;
    </th>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" colspan="2">
      &nbsp;WAKTU&nbsp;</th>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" rowspan="2">
      &nbsp;WILAYAH&nbsp;</th>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" rowspan="2">
      &nbsp;MSK&nbsp;
    </th>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" rowspan="2">
      &nbsp;KLR&nbsp;
    </th>
    <th style="border:1px solid black; font-weight:bold; text-align:center; vertical-align: middle;" rowspan="2">
      &nbsp;KETERANGAN / KASUS&nbsp;</th>
  </tr>
  <tr>
    <th style="border:1px solid black; font-weight:bold; text-align:center;">&nbsp;Di-Toko&nbsp;</th>
    <th style="border:1px solid black; font-weight:bold; text-align:center;">&nbsp;Di-Jalan&nbsp;</th>
  </tr>

  @foreach ($data as $key => $dt)
    <tr style="border:1px solid black;">
      <td style="border:1px solid black; text-align:center;">{{ $loop->iteration }}</td>
      <td style="border:1px solid black;">{{ $dt->linkCustomer->nama ?? null }}</td>

      @php
        if ($dt->waktu_keluar ?? null) {
            $waktu_keluar = $dt->waktu_keluar;
        } else {
            $waktu_keluar = date('Y-m-d H:i:s', strtotime($dt->waktu_masuk . '+ 1 minute'));
        }
        
        $masuk = new DateTime($dt->waktu_masuk);
        $keluar = new DateTime($waktu_keluar);
        
        $interval_ditoko = $masuk->diff($keluar);
        $menit_ditoko = $interval_ditoko->h * 60 + $interval_ditoko->i;
        
        if ($data[$key - 1] ?? null) {
            $keluar_toko_sebelumnya = new DateTime($data[$key - 1]->waktu_keluar ?? date('Y-m-d H:i:s', strtotime($data[$key - 1]->waktu_masuk . '+ 1 minute')));
            $interval_dijalan = $masuk->diff($keluar_toko_sebelumnya);
            $menit_dijalan = $interval_dijalan->h * 60 + $interval_dijalan->i;
        }
      @endphp

      <td style="border:1px solid black; text-align:center;">{{ $menit_ditoko ?? null }}</td>
      <td style="border:1px solid black; text-align:center;">{{ $menit_dijalan ?? null }}</td>
      <td style="border:1px solid black;">{{ $dt->linkCustomer->linkDistrict->nama ?? null }}</td>
      <td style="border:1px solid black;">{{ date('G.i', strtotime($dt->waktu_masuk)) }}</td>
      <td style="border:1px solid black; text-align:right;">{{ date('G.i', strtotime($waktu_keluar)) }}</td>
      <td style="border:1px solid black;">
        @if ($dt->status_enum == '2')
          @foreach ($group_invoice as $invoice)
            @if ($invoice['id_customer'] == $dt->id_customer)
              effective call,
              {{ $dt->linkCustomer->time_to_effective_call == $waktu_keluar ? 'Pelanggan Baru' : 'Pelanggan Lama' }},
              jumlah order Rp
              {{ number_format($invoice['harga_total'] ?? 0, 0, '', '.') }}
            @endif
          @endforeach
        @else
          call, ditolak karena {{ $dt->alasan_penolakan ?? null }}
        @endif
      </td>
    </tr>
  @endforeach
</table>
