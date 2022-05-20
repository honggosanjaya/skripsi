@extends('layouts/main')

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <a href="/administrasi/stok/produk/create" class="text-decoration-none">
    <i class="bi bi-plus-circle-fill me-2"></i> Tambah Produk
  </a>

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
          <th scope="col">Max Pengadaan</th>
          <th scope="col">Harga Satuan</th>
          <th scope="col">Status</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
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
            <td>{{ $item->max_pengadaan }}</td>
            <td>{{ $item->harga_satuan }}</td>
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
  </div>
@endsection
