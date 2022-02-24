@extends('layouts/main')

@section('main_content')
  <div class="mt-3 search-box">
    <i class="bi bi-search"></i>
    <input type="text" class="form-control " placeholder="Cari Produk...">
  </div>

  <div class="d-flex justify-content-between">
    <a href="/dashboard/produk/stok/edit" class="btn btn-warning my-4">Ubah Stok</a>
    <a href="/dashboard/produk" class="btn btn-primary my-4">Lihat Produk</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">id</th>
        <th scope="col">Nama</th>
        <th scope="col">Stok</th>
        <th scope="col">Satuan Stok</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($items as $item)
        <tr>
          <td>{{ $item->id }}</td>
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->stok }}</td>
          <td>{{ $item->satuan }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
