@extends('layouts.main')

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <h1>Pengadaan</h1>
  <a href="/administrasi/stok/pengadaan/cart" class="btn btn-primary">Keranjang</a>

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col">Kode Barang</th>
          <th scope="col">Nama</th>
          <th scope="col">Satuan</th>
          <th scope="col">Max Pengadaan</th>
          <th scope="col">Pengadaan</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $product->kode_barang }}</td>
            <td>{{ $product->nama }}</td>
            <td>{{ $product->satuan }}</td>
            <td>{{ $product->max_pengadaan }}</td>
            <td>
              <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $product->id }}" name="id">
                <input type="hidden" value="{{ $product->kode_barang }}" name="kode_barang">
                <input type="hidden" value="{{ $product->nama }}" name="nama">
                <input type="hidden" value="{{ $product->satuan }}" name="satuan">
                <input type="hidden" value="{{ $product->max_pengadaan }}" name="max_pengadaan">
                <input type="hidden" value="{{ $product->harga_satuan }}" name="harga_satuan">
                @if ($cartItem = \Cart::get($product->id) ?? null)
                  <input type="number" class="form-control" id="quantity" name="quantity" min="0"
                    value="{{ $cartItem->quantity }}">
                @else
                  <input type="number" class="form-control" id="quantity" name="quantity" min="0">
                @endif

                @if ($cartItem2 = \Cart::get($product->id) ?? null)
                  <input type="text" class="form-control" id="total_harga" name="total_harga"
                    value="{{ $cartItem2->attributes->total_harga }}">
                @else
                  <input type="text" class="form-control" id="total_harga" name="total_harga">
                @endif

                <button type="submit">Submit</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
