<table>
  <thead>
    <tr>
      <td>{{ date('d-m-Y') }}</td>
    </tr>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="3">LAPORAN AKTIVITAS PENJUALAN</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-m-Y', strtotime($dateStart)) }} s/d {{ date('d-m-Y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">No.PO</th>
      <th style="border:1px solid black; font-weight:bold;">Tgl Faktur</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Pelanggan</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Kontak</th>
      <th style="border:1px solid black; font-weight:bold;">No. Faktur</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Faktur</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Retur</th>
      <th style="border:1px solid black; font-weight:bold;">Hutang(Asing)</th>
      <th style="border:1px solid black; font-weight:bold;">Penjual</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($fakturs as $faktur)
      <tr style="border:1px solid black;">
        @if ($faktur->linkOrder->linkOrderTrack->waktu_order ?? null)
          <td style="text-align: right; border:1px solid black;">
            {{ date('d-m-Y', strtotime($faktur->linkOrder->linkOrderTrack->waktu_order)) }}
          </td>
        @else
          <td style="border:1px solid black;"></td>
        @endif

        @if ($faktur->linkOrder->linkOrderTrack->waktu_berangkat ?? null)
          <td style="text-align: right; border:1px solid black;">
            {{ date('d-m-Y', strtotime($faktur->linkOrder->linkOrderTrack->waktu_berangkat)) }}</td>
        @else
          <td style="border:1px solid black;"></td>
        @endif

        <td style="border:1px solid black;">
          {{ $faktur->linkOrder->linkCustomer->nama ?? null }}</td>

        @if ($faktur->linkOrder->linkCustomer->linkDistrict->kode_wilayah ?? null)
          <td style="border:1px solid black;">
            {{ $faktur->linkOrder->linkCustomer->linkDistrict->kode_wilayah }} -
            {{ $faktur->linkOrder->linkCustomer->linkDistrict->nama ?? null }}</td>
        @else
          <td style="border:1px solid black;">
            {{ $faktur->linkOrder->linkCustomer->linkDistrict->nama ?? null }}</td>
        @endif

        @php
          $returs = $faktur->linkRetur ?? null;
          $pembayarans = $faktur->linkPembayaran ?? null;
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

        <td style="border:1px solid black;">{{ $faktur->nomor_invoice ?? null }}</td>
        <td style="text-align: right; border:1px solid black;">{{ $faktur->harga_total + $totalRetur }}</td>

        @if ($totalRetur ?? null !== 0)
          <td style="text-align: right; border:1px solid black;">{{ $totalRetur }}</td>
        @else
          <td style="text-align: right; border:1px solid black;">-</td>
        @endif
        <td style="border:1px solid black;">{{ ($faktur->harga_total ?? 0) - ($totalPembayaran ?? 0) }}</td>
        <td style="border:1px solid black;">{{ $faktur->linkOrder->linkStaff->nama ?? null }}</td>
      </tr>
    @endforeach
    <tr>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;">{{ $total['total_faktur'] }}</td>
      <td style="border:1px solid black;">{{ $total['total_retur'] }}</td>
      <td style="border:1px solid black;">{{ $total['total_hutang'] }}</td>
      <td style="border:1px solid black;"></td>
    </tr>
  </tbody>
</table>
