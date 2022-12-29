<table>
  <thead>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="3">Laba/Rugi (Standar)</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-m-Y', strtotime($dateStart)) }} s/d {{ date('d-m-Y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">No. Akun</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Akun</th>
      <th style="border:1px solid black; font-weight:bold;">Tanggal</th>
      <th style="border:1px solid black; font-weight:bold;">Catatan</th>
      <th style="border:1px solid black; font-weight:bold;">Nilai Rupiah</th>
    </tr>
  </thead>
  <tbody>
    {{-- {{ dd($total) }} --}}
    @foreach ($all_kas as $kas)
      @foreach ($kas as $data)
        <tr>
          <td style="border:1px solid black; text-align: right;">{{ $data->id_cash_account ?? null }}</td>
          <td style="border:1px solid black;">{{ $data->linkCashAccount->nama ?? null }}</td>
          @if ($data->tanggal ?? null)
            <td style="border:1px solid black;">{{ date('d-m-Y', strtotime($data->tanggal)) }}</td>
          @else
            <td style="border:1px solid black;"></td>
          @endif
          <td style="border:1px solid black;">
            {{ $data->keterangan_1 ?? null }} {{ $data->keterangan_2 ? ',' . $data->keterangan_2 : null }}
          </td>
          <td style="border:1px solid black; text-align: right;">
            {{ $data->debit_kredit == -1 ? '-' : '' }}{{ $data->uang ?? null }}
          </td>
        </tr>
      @endforeach

      <tr>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;">Total dari IDR</td>
        <td style="border:1px solid black; text-align:right;">
          {{ ($total['total_debit_perkas'][$data->id_cash_account ?? null][0]['total_debit_perkas'] ?? 0) - ($total['total_kredit_perkas'][$data->id_cash_account ?? null][0]['total_kredit_perkas'] ?? 0) }}
        </td>
      </tr>
    @endforeach

    <tr>
      <td colspan="2" style="border:1px solid black;">Total dari IDR</td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black; text-align:right;">{{ $total['total_allkas'] ?? null }}</td>
    </tr>
  </tbody>
</table>
