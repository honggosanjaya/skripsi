<table>
  <thead>
    <tr>
      <td>{{ date('d-m-Y') }}</td>
    </tr>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="4">LAPORAN PIUTANG DAN UMUR PIUTANG</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-m-Y', strtotime($dateStart)) }} s/d {{ date('d-m-Y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Tgl Faktur</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Jatuh Tempo</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Umur</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">No. Faktur</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Nama Pelanggan</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Nama kontak</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Nilai Faktur</th>
      <th style="border:1px solid black; font-weight:bold; text-align:center;">Hutang(Asing)</th>
    </tr>
  </thead>

  <tbody>
    @foreach ($fakturs as $data)
      <tr>
        @if ($data->linkOrder->linkOrderTrack->waktu_berangkat ?? null)
          <td style="border:1px solid black; text-align: right;">
            {{ date('d-m-Y', strtotime($data->linkOrder->linkOrderTrack->waktu_berangkat)) }}
          </td>

          <td style="border:1px solid black;">
            @if ($data->jatuh_tempo ?? 0 > 0)
              {{ date('d-m-Y', strtotime($data->linkOrder->linkOrderTrack->waktu_berangkat . ' + ' . $data->jatuh_tempo . ' days')) }}
            @else
              {{ date('d-m-Y', strtotime($data->linkOrder->linkOrderTrack->waktu_berangkat)) }}
            @endif
          </td>

          <td style="border:1px solid black; text-align:center;">
            @php
              if ($data->jatuh_tempo > 0) {
                  $tgl_jatuh_tempo = date('Y-m-d', strtotime($data->linkOrder->linkOrderTrack->waktu_berangkat . ' + ' . $data->jatuh_tempo . ' days'));
              } else {
                  $tgl_jatuh_tempo = date('Y-m-d', strtotime($data->linkOrder->linkOrderTrack->waktu_berangkat));
              }
              
              $tgl_today = date('Y-m-d');
              $date1 = date_create($tgl_jatuh_tempo);
              $date2 = date_create($tgl_today);
              $diff = date_diff($date1, $date2);
            @endphp
            {{ $diff->format('%R%a') }}
          </td>
        @else
          <td style="border:1px solid black;"></td>
          <td style="border:1px solid black;"></td>
          <td style="border:1px solid black;"></td>
        @endif

        <td style="border:1px solid black;">{{ $data->nomor_invoice ?? null }}</td>
        <td style="border:1px solid black;">{{ $data->linkOrder->linkCustomer->nama ?? null }}</td>
        <td style="border:1px solid black;">
          {{ $data->linkOrder->linkCustomer->linkDistrict->kode_wilayah ?? null }}-{{ $data->linkOrder->linkCustomer->linkDistrict->nama ?? null }}
        </td>

        @php
          $returs = $data->linkRetur ?? null;
          $pembayarans = $data->linkPembayaran ?? null;
          $totalRetur = 0;
          $totalPembayaran = 0;
          
          if ($returs != null) {
              foreach ($returs as $retur) {
                  if ($retur->status_enum == '1' && $retur->tipe_retur == 1) {
                      $totalRetur += $retur->kuantitas * $retur->harga_satuan;
                  }
              }
          }
          
          if ($pembayarans != null) {
              foreach ($pembayarans as $pembayaran) {
                  $totalPembayaran += $pembayaran->jumlah_pembayaran;
              }
          }
        @endphp

        <td style="border:1px solid black;">{{ ($data->harga_total ?? 0) + $totalRetur }}</td>
        <td style="border:1px solid black; text-align:right;">{{ ($data->harga_total ?? 0) - $totalPembayaran }}</td>
      </tr>
    @endforeach
    <tr>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td colspan="3" style="border:1px solid black; text-align:center;"><b>TOTAL PIUTANG</b></td>
      <td style="border:1px solid black;">{{ ($total['total_faktur'] ?? 0) - ($total['total_pembayaran'] ?? 0) }}</td>
    </tr>
  </tbody>
</table>
