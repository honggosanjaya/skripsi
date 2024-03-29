<table>
  <thead>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="3">Rincian Kas - {{ $nama_kas }}</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-m-Y', strtotime($dateStart)) }} s/d {{ date('d-m-Y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">Tanggal</th>
      <th style="border:1px solid black; font-weight:bold;">No. Bukti</th>
      <th style="border:1px solid black; font-weight:bold;">No. Akun</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Akun</th>
      <th style="border:1px solid black; font-weight:bold;">Catatan</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Rupiah</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($all_kas as $data)
      <tr>
        @if ($data->tanggal ?? null)
          <td style="border:1px solid black; text-align: right;">{{ date('d-m-Y', strtotime($data->tanggal)) }}</td>
        @else
          <td style="border:1px solid black;"></td>
        @endif
        <td style="border:1px solid black; text-align: right;">{{ $data->no_bukti ?? null }}</td>
        <td style="border:1px solid black; text-align: right;">{{ $data->id_cash_account ?? null }}</td>
        <td style="border:1px solid black;">{{ $data->linkCashAccount->nama ?? null }}</td>
        <td style="border:1px solid black;">
          {{ $data->keterangan_1 ?? null }} {{ $data->keterangan_2 ? ',' . $data->keterangan_2 : null }}
        </td>
        <td style="border:1px solid black; text-align: right;">
          {{ $data->debit_kredit == -1 ? '-' : '' }}{{ $data->uang ?? null }}</td>
      </tr>
    @endforeach

    <tr>
      <td colspan="2" style="border:1px solid black;">Total dari IDR</td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;  text-align:right;">{{ $total_kas ?? null }}</td>
    </tr>
  </tbody>
</table>
