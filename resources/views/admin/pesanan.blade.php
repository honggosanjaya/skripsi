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
        <th scope="col">nama sales</th>
        <th scope="col">metode bayar</th>
        <th scope="col">status pembayaran</th>
        <th scope="col">tanggal kirim</th>
        <th scope="col">status pengiriman</th>
        <th scope="col">dikonfirmasi oleh</th>
        <th scope="col">aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>1</td>
        <td>Toko ABC</td>
        <td>Hendro Hanjaya</td>
        <td>Tunai</td>
        <td>Lunas</td>
        <td>23-02-2022</td>
        <td>Belum kirim</td>
        <td>Bambang Santoso</td>
        <td>
          <button class="btn btn-success">Terima</button>
          <button class="btn btn-danger">Tolak</button>
          {{-- detail: cetak faktur pesanan , ubah jadwal pengiriman, cek foto pengirman --}}
          <button class="btn btn-info">Detail</button>
        </td>
      </tr>
    </tbody>
  </table>
@endsection
