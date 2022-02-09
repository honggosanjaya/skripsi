@extends('layouts/main')

@section('main_content')
  <div class="mt-3 search-box">
    <i class="bi bi-search"></i>
    <input type="text" class="form-control " placeholder="Cari Produk...">
  </div>

  <a href="/dashboard/produk/tambah" class="btn btn-primary my-4">Tambah Produk</a>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">id</th>
        <th scope="col">Nama</th>
        <th scope="col">Stok</th>
        <th scope="col">Satuan Stok</th>
        <th scope="col">Status Produk</th>
        <th scope="col">Harga</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>1</td>
        <td>Sapu Apik</td>
        <td>100</td>
        <td>Buah</td>
        <td>Dinonaktifkan</td>
        <td>30.000</td>
        <td>
          <a href="/dashboard/produk/ubah" class="btn btn-warning">Ubah Produk</a>
          <button class="btn btn-success">Aktifkan Produk</button>
        </td>
      </tr>
    </tbody>
  </table>
@endsection
