@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
  <li class="breadcrumb-item active" aria-current="page">Produk</li>
</ol>
@endsection
@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="container">
    <div class="row">
      <div class="col-5">
        <div class="mt-3 search-box">
          <form method="GET" action="/administrasi/stok/produk/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Produk..."
                value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-4 mt-3">
        <a href="/administrasi/stok/produk/create" class="text-decoration-none">
          <i class="bi bi-plus-circle-fill me-2"></i> Tambah Produk
        </a>
      </div>
    </div>
  </div>



  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col">Kode Barang</th>
          <th scope="col">Gambar</th>
          <th scope="col">Satuan</th>
          <th scope="col">Stok</th>
          <th scope="col">Min Stok</th>
          <th scope="col">Max Stok</th>
          {{-- <th scope="col">Max Pengadaan</th> --}}
          <th scope="col">Harga Satuan</th>
          <th scope="col">Volume</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        @php
          $stock25=(($item->max_stok-$item->min_stok)*25/100)+$item->min_stok;
        @endphp
        @if ($item->stok<$item->min_stok)
          <tr class="bg-danger">
        @elseif($item->stok<$stock25)
          <tr class="bg-warning">
        @else
          <tr>
        @endif
            <td class="text-center">{{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}</td>
            <td>{{ $item->kode_barang }}</td>
            <td class="text-center">
              @if ($item->gambar)
                <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid" width="40">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid" width="40">
              @endif
            </td>
            <td>{{ $item->satuan }}</td>
            <td>{{ $item->stok }}</td>
            <td>{{ $item->min_stok }}</td>
            <td>{{ $item->max_stok }}</td>
            {{-- <td>{{ $item->max_pengadaan }}</td> --}}
            <td>{{ $item->harga_satuan }}</td>
            <td>{{ $item->volume }}</td>
            <td>{{ $item->linkStatus->nama }}</td>
            <td class="text-center">
              {{-- <a href="/administrasi/stok/produk/{{ $item->id }}"
                class="badge bg-primary text-decoration-none text-white">
                detail
              </a> --}}
              <a href="/administrasi/stok/produk/{{ $item->id }}/edit"
                class="badge bg-primary text-decoration-none text-white">
                edit
              </a>
              <a href="/administrasi/stok/produk/{{ $item->id }}"
                class="badge bg-primary text-decoration-none text-white">
                nonaktifkan
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $items->links() }}
  </div>
@endsection
