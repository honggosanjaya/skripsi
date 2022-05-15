@extends('layouts/main')

@section('main_content')
  <div class="mt-3 search-box">
    <i class="bi bi-search"></i>
    <input type="text" class="form-control " placeholder="Cari Pesanan...">
  </div>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">id pesanan</th>
        <th scope="col">nama toko</th>
        <th scope="col">jenis toko</th>
        <th scope="col">nama sales</th>
        <th scope="col">harga total</th>
        <th scope="col">metode pembayaran</th>
        <th scope="col">status pembayaran</th>
        <th scope="col">tanggal kirim</th>
        <th scope="col">status pengiriman</th>
        <th scope="col">dikonfirmasi oleh</th>
        <th scope="col">aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($invoices as $invoice)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $invoice->linkOrder->id }}</td>
          <td>{{ $invoice->linkOrder->linkToko->nama }}</td>
          <td>{{ $invoice->linkOrder->linkToko->linkJenisToko->nama }}</td>
          @if (isset($invoice->linkOrder->linksales->nama))
            <td>{{ $invoice->linkOrder->linksales->nama }}</td>
          @else
            <td>Ini kosong bos</td>
          @endif
          <td>
            {{ $invoice->harga_total }}
          </td>
          <td>{{ $invoice->linkmetodepembayaran->nama }}</td>

          @if ($invoice->status_pelunasan == '0')
            <td>Belum Lunas</td>
          @elseif ($invoice->status_pelunasan == '1')
            <td>Setengah Lunas</td>
          @else
            <td>Lunas</td>
          @endif

          <td>23-02-2022</td>
          <td>Belum</td>
          <td>Bambang Santoso</td>
          <td>
            <button class="btn btn-success">Terima</button>
            <button class="btn btn-danger">Tolak</button>
            {{-- detail: cetak faktur pesanan , ubah jadwal pengiriman, cek foto pengirman --}}
            <button class="btn btn-info">Detail</button>
          </td>
        </tr>
      @endforeach

    </tbody>
  </table>
@endsection
