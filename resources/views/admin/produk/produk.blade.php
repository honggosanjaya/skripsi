@extends('layouts/main')

@section('main_content')
  @if (session()->has('successMessage'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('successMessage') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="mt-3 search-box">
    <i class="bi bi-search"></i>
    <input type="text" class="form-control " placeholder="Cari Produk...">
  </div>

  <a href="/dashboard/produk/create" class="btn btn-primary my-4">Tambah Produk</a>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">id</th>
        <th scope="col">Nama</th>
        <th scope="col">Stok</th>
        <th scope="col">Satuan Stok</th>
        <th scope="col">Harga</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($items as $item)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $item->id }}</td>
          <td>{{ $item->nama_barang }}</td>
          <td>{{ $item->stok }}</td>
          <td>{{ $item->satuan }}</td>
          <td>{{ $item->harga_satuan }}</td>
          <td>
            <a href="/dashboard/produk/{{ $item->id }}" class="btn btn-primary">Detail</a>
            <a href="/dashboard/produk/{{ $item->id }}/edit" class="btn btn-warning">Ubah</a>
            {{-- <button class="btn btn-success">Aktifkan</button> --}}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
