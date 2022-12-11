<table>
  <thead>
    <tr>
      <td>{{ date('d-m-Y') }}</td>
    </tr>
    <tr>
      <td>UD SURYA</td>
    </tr>
    <tr>
      <td colspan="3">REKAP PENERIMAAN PELANGGAN</td>
    </tr>
    <tr>
      <td colspan="3">Dari {{ date('d-m-Y', strtotime($dateStart)) }} s/d {{ date('d-m-Y', strtotime($dateEnd)) }}
      </td>
    </tr>
    <tr>
      <th style="border:1px solid black; font-weight:bold;">No. Form</th>
      <th style="border:1px solid black; font-weight:bold;">Tgl terima</th>
      <th style="border:1px solid black; font-weight:bold;">No. Cek</th>
      <th style="border:1px solid black; font-weight:bold;">Tgl Cek</th>
      <th style="border:1px solid black; font-weight:bold;">No. Pelanggan</th>
      <th style="border:1px solid black; font-weight:bold;">Nama Pelanggan</th>
      <th style="border:1px solid black; font-weight:bold;">Jumlah Cek</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($pembayarans as $data)
      <tr style="border:1px solid black;">
        <td style="border:1px solid black; text-align: right;">{{ $previous_pembayarans_count + $loop->iteration }}</td>
        <td style="border:1px solid black">
          @if ($data->created_at ?? null)
            {{ date('d-m-Y', strtotime($data->created_at)) }}
          @endif
        </td>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;">
          @if ($data->tanggal ?? null)
            {{ date('d-m-Y', strtotime($data->tanggal)) }}
          @endif
        </td>
        <td style="border:1px solid black;"></td>
        <td style="border:1px solid black;">
          {{ $data->linkInvoice->linkOrder->linkCustomer->nama ?? null }}
        </td>
        <td style="border:1px solid black; text-align: right;">
          {{ $data->jumlah_pembayaran }}
        </td>
      </tr>
    @endforeach
    <tr>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;"></td>
      <td style="border:1px solid black;">{{ $total_pembayaran }}</td>
    </tr>
  </tbody>
</table>
