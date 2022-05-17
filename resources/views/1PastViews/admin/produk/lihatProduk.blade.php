@extends('layouts/main')

@section('main_content')
  <div class="produk-tbl">
    <div class="mt-3 search-box">
      <i class="bi bi-search"></i>
      <input type="text" class="form-control" placeholder="Cari Produk...">
    </div>

    <a href="/dashboard/produk/create" class="btn btn-primary my-4">Tambah Produk</a>

    <table class="table table-produk">
      <thead>
        <tr>
          <th scope="col">id</th>
          <th scope="col">Nama</th>
          <th scope="col">Stok</th>
          <th scope="col">Satuan Stok</th>
          <th scope="col">Harga</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td>{{ $item->id ?? null }}</td>
            <td>{{ $item->nama_barang ?? null }}</td>
            <td>{{ $item->stok ?? null }}</td>
            <td>{{ $item->satuan ?? null }}</td>
            <td>{{ $item->harga_satuan ?? null }}</td>
            <td class="status-prd">{{ $item->status_produk ?? null }}</td>
            <td>
              <a href="/dashboard/produk/{{ $item->id }}" class="btn btn-primary">Detail</a>
              <a href="/dashboard/produk/{{ $item->id }}/edit" class="btn btn-warning">Ubah</a>
              <button data-id="{{ $item->id }}"
                class="btn status-btn {{ $item->status_produk === 'aktif' ? 'btn-danger' : 'btn-success' }}">
                {{ $item->status_produk === 'aktif' ? 'Nonaktifkan' : 'aktifkan' }} Produk
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
