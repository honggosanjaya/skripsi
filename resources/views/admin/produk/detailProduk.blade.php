@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <h3>Detail Produk</h3>
    <p><span class="fw-bold">Nama Produk : </span>{{ $barang->nama_barang }}</p>
    <p><span class="fw-bold">Stok : </span>{{ $barang->stok }}</p>
    <p><span class="fw-bold">Satuan : </span>{{ $barang->satuan }}</p>
    <p><span class="fw-bold">Harga Satuan : </span>{{ $barang->harga_satuan }}</p>
    <p><span class="fw-bold">Status Produk : </span>{{ $barang->status_produk }}</p>

    <div class="action_group">
      <a href="/dashboard/produk/{{ $barang->id }}/edit" class="btn btn-warning">Edit</a>
      <form action="/dashboard/produk/ubahstatus/{{ $barang->id }}" method="POST">
        @csrf
        <button type="submit" class="btn {{ $barang->status_produk === 'aktif' ? 'btn-danger' : 'btn-success' }}">
          {{ $barang->status_produk === 'aktif' ? 'Nonaktifkan' : 'aktifkan' }} Produk
        </button>
      </form>
    </div>
  </div>
@endsection
