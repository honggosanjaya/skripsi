@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk/pricelist">Price list</a></li>
    <li class="breadcrumb-item active" aria-current="page">Perubahan Final</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div id="pricelist" class="px-5 pt-4">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama Barang</th>
          <th scope="col" class="text-center">Tipe Harga</th>
          <th scope="col" class="text-center">Harga Sekarang</th>
          <th scope="col" class="text-center">Harga Setelah<br>Perubahan</th>
          <th scope="col" class="text-center">Perubahan</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($cartItems as $item)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $item->name ?? null }}</td>
            <td class="text-center">{{ $item->attributes->tipe_harga ?? null }}</td>
            <td class="text-end">{{ $item->attributes->price ?? null }}</td>
            <td class="text-end">{{ $item->attributes->perubahan_harga ?? null }}</td>
            <td class="text-end">{{ ($item->attributes->perubahan_harga ?? 0) - ($item->attributes->price ?? 0) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="row justify-content-end mt-4">
      <div class="col d-flex justify-content-end">
        <a type="button" class="btn btn-danger me-3"
          href="/administrasi/stok/produk/pricelist/clearcart?route=pricelist">
          <span class="iconify fs-3 me-1" data-icon="bxs:trash"></span>Remove All Cart
        </a>
        <a type="button" class="btn btn-success" href="/administrasi/stok/produk/pricelist/submit">
          <span class="iconify fs-3 me-1" data-icon="akar-icons:double-check"></span>Submit
        </a>
      </div>
    </div>
  </div>
@endsection
