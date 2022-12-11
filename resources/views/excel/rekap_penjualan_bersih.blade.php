<table>
  <thead>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="3">REKAP PENJUALAN BERSIH</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-m-Y', strtotime($dateStart)) }} s/d {{ date('d-m-Y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">Nama Pelanggan</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Kontak</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Faktur</th>
      <th style="border:1px solid black; font-weight:bold;">Jumlah Retur</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Penjualan</th>
      <th style="border:1px solid black; font-weight:bold;">Harga Pokok</th>
      <th style="border:1px solid black; font-weight:bold;">Laba Kotor</th>
      <th style="border:1px solid black; font-weight:bold;">% LK pada Penj.</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($fakturs as $data)
      <tr>
        <td style="border:1px solid black;">
          {{ $data['nama_customer'] ?? null }}
        </td>

        <td style="border:1px solid black;">
          {{ $data['nama_kontak'] ?? null }}
        </td>

        <td style="border:1px solid black; text-align: right;">
          {{ ($data['nilai_penjualan'] ?? 0) + ($data['jumlah_retur'] ?? 0) }}
        </td>

        <td style="border:1px solid black; text-align: right;">
          {{ $data['jumlah_retur'] == 0 ? '-' : $data['jumlah_retur'] }}
        </td>

        <td style="border:1px solid black; text-align: right;">
          {{ $data['nilai_penjualan'] ?? null }}
        </td>

        <td style="text-align: right; border:1px solid black;">blm</td>
        <td style="text-align: right; border:1px solid black;">blm</td>
        <td style="text-align: right; border:1px solid black;">blm</td>
      </tr>
    @endforeach

    <tr>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;">
        {{ ($total['total_faktur'] ?? 0) + ($total['total_retur'] ?? 0) }}
      </td>
      <td style="border:1px solid black;">
        {{ $total['total_retur'] ?? null }}
      </td>
      <td style="border:1px solid black;">
        {{ $total['total_faktur'] ?? null }}
      </td>
      <td style="border:1px solid black;">
      </td>
      <td style="border:1px solid black;">
      </td>
      <td style="border:1px solid black;">
      </td>
    </tr>
  </tbody>
</table>
