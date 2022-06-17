@extends('layouts/main')

@section('main_content')
  @foreach ($customersPengajuanLimit as $customerPengajuanLimit)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      Pengajuan limit pembelian dari
      <a href="/supervisor/datacustomer/pengajuan/{{ $customerPengajuanLimit->id }}"
        class="alert-link">{{ $customerPengajuanLimit->nama }}
      </a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endforeach

  <h1 class="fs-3">Ini dashboard supervisor</h1>

  <p class="fw-bold">Produk Favorit Bulan Mei</p>

  <table border="1" class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Penjualan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>K001</td>
        <td>Sapu</td>
        <td>5000000</td>
      </tr>
      <tr>
        <td>K002</td>
        <td>Lidi</td>
        <td>6000000</td>
      </tr>
    </tbody>
  </table>
@endsection
