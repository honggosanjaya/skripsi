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
      @foreach ($tokos as $toko)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $toko->linkOrder[0]->id }}</td>
          <td>{{ $toko->nama }}</td>
          <td>{{ $toko->linkJenisToko->nama }}</td>
          @if (isset($toko->linkTrip[0]))
            <td>{{ $toko->linkTrip[0]->linkSales->nama }}</td>
          @else
            <td>Ini kosong bos</td>
          @endif
          <td>
            {{ $toko->linkOrder[0]->linkInvoice->harga_total }}
          </td>
          <td>{{ $toko->linkOrder[0]->linkInvoice->linkmetodepembayaran->nama }}</td>

          @if ($toko->linkOrder[0]->linkInvoice->status_pelunasan == '0')
            <td>Belum Lunas</td>
          @elseif ($toko->linkOrder[0]->linkInvoice->status_pelunasan == '1')
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
