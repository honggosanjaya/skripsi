<table>
  <tr>
    <td colspan="5">{{ date('d-m-Y') }}</td>
  </tr>
  <tr>
    <td colspan="11" style="font-size: 15px; text-align:center;">UD SURYA</td>
  </tr>
  <tr>
    <td colspan="11" style="font-size: 15px; color:#800000; text-align:center;">Laba/Rugi (Standar)</td>
  </tr>
  <tr>
    <td colspan="11" style="font-size: 13px; font-weight: bold; text-align:center;">Dari
      {{ date('d-m-Y', strtotime($dateStart)) }} s/d
      {{ date('d-m-Y', strtotime($dateEnd)) }}
    </td>
  </tr>
  <tr>
    <td colspan="11" style="text-align:right;">Filtered by: Dari Tanggal, s/d Tanggal</td>
  </tr>
  <tr>
    <td colspan="6" style="font-weight:bold; color:#151361;">Keterangan</td>
    <td></td>
    <td style="font-weight:bold; text-align:right; color:#151361;">Saldo</td>
  </tr>

  <tr>
    <td colspan="6" style="font-weight:bold;">Pendapatan</td>
  </tr>

  <tr>
    <td></td>
    <td colspan="5" style="font-weight:bold;">Omset Penjualan Bersih</td>
    <td></td>
    <td style="text-align: right;">0</td>
  </tr>

  <tr>
    <td></td>
    <td></td>
    <td colspan="4">Penjualan</td>
    <td></td>
    <td style="text-align: right;">0</td>
  </tr>

  <tr>
    <td></td>
    <td></td>
    <td colspan="4">Retur Penjualan</td>
    <td></td>
    <td style="text-align: right;">0</td>
  </tr>

  <tr>
    <td></td>
    <td></td>
    <td colspan="4">Potongan Reguler</td>
    <td></td>
    <td style="text-align: right;">0</td>
  </tr>

  <tr>
    <td></td>
    <td></td>
    <td colspan="4">Potongan Faktur</td>
    <td></td>
    <td style="text-align: right;">0</td>
  </tr>

  <tr>
    <td colspan="6" style="font-weight:bold;">Jumlah Pendapatan</td>
    <td></td>
    <td></td>
    <td colspan="2" style="text-align: right; font-weight: bold;">0</td>
  </tr>

  <tr>
    <td colspan="6" style="font-weight:bold;">Beban Pokok Penjualan</td>
  </tr>

  <tr>
    <td></td>
    <td colspan="5">COGS</td>
    <td></td>
    <td style="text-align: right;">0</td>
  </tr>

  <tr>
    <td colspan="6" style="font-weight: bold;">Jumlah Beban Pokok Penjualan</td>
    <td></td>
    <td></td>
    <td colspan="2" style="text-align: right; font-weight: bold;">0</td>
  </tr>

  <tr>
    <td colspan="6" style="font-weight: bold;">LABA KOTOR</td>
    <td></td>
    <td></td>
    <td colspan="2" style="text-align: right; font-weight: bold;">0</td>
  </tr>

  <tr>
    <td colspan="6" style="font-weight: bold;">Beban Operasi</td>
  </tr>

  <tr>
    <td></td>
    <td colspan="5" style="font-weight: bold;">TOTAL BIAYA</td>
    <td></td>
    <td style="text-align: right; font-weight: bold;">0</td>
  </tr>

  <tr>
    <td></td>
    <td></td>
    <td colspan="4" style="font-weight: bold;">BIAYA PEMASARAN</td>
    <td></td>
    <td style="text-align: right; font-weight: bold;">0</td>
  </tr>

  @foreach ($grouped_data as $data)
    @php
      $total_pengeluaran = 0;
      foreach ($data as $dt) {
          $total_pengeluaran += $dt['pengeluaran'];
      }
    @endphp

    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td colspan="3" style="font-weight:bold;">{{ $data[0]['nama_account_parent'] ?? null }}</td>
      <td></td>
      <td style="text-align: right; font-weight:bold;">{{ $total_pengeluaran ?? null }}</td>
    </tr>
    @foreach ($data as $dt)
      <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2">{{ $dt['nama_account'] ?? null }}</td>
        <td></td>
        <td style="text-align: right;">{{ $dt['pengeluaran'] ?? null }}</td>
      </tr>
    @endforeach
  @endforeach
</table>
