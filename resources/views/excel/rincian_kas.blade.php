<table>
  <thead>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="3">Rincian Pembayaran Lain per Bank</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-M-y', strtotime($dateStart)) }} s/d {{ date('d-M-y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">Tanggal</th>
      <th style="border:1px solid black; font-weight:bold;">No. Bukti</th>
      <th style="border:1px solid black; font-weight:bold;">No. Akun</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Akun</th>
      <th style="border:1px solid black; font-weight:bold;">Catatan</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Bayar</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($kas as $data)
      <tr style="border:1px solid black;">
        <td style="border:1px solid black; text-align: right;">{{ $data->tanggal ?? null }}</td>
        <td style="border:1px solid black; text-align: right;">{{ $data->no_bukti ?? null }}</td>
        <td style="border:1px solid black; text-align: right;">{{ $data->kas ?? null }}</td>
        <td style="border:1px solid black;">{{ $data->linkCashAccount->nama ?? null }}</td>
        <td style="border:1px solid black;">
          {{ $data->keterangan_1 ?? null }} {{ $data->keterangan_2 ?? null }}
        </td>
        <td style="text-align: right; border:1px solid black; text-align: right;">{{ $data->uang ?? null }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
