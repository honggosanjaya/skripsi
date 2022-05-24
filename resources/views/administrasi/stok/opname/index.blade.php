@extends('layouts.main')

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <h1>Opname</h1>
  <a href="/administrasi/stok/opname/final?route=opname" class="btn btn-primary">Opname final</a>

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col">Kode Barang</th>
          <th scope="col">Nama</th>
          <th scope="col">Satuan</th>
          <th scope="col">Min Stok</th>
          <th scope="col">Max Stok</th>
          <th scope="col">Stock Saat Ini</th>
          <th scope="col">Stock Baru</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
        @php
          $cartItem = \Cart::session(auth()->user()->id.'opname')->get($product->id)
        @endphp
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $product->kode_barang }}</td>
            <td>{{ $product->nama }}</td>
            <td>{{ $product->satuan }}</td>
            <td>{{ $product->min_stok }}</td>
            <td>{{ $product->max_stok }}</td>
            <td>{{ $product->stok }}</td>
            <td>{{ $product->stok + (($cartItem->attributes->jumlah??0))}}</td>
            <td>
              <form action="{{ '/administrasi/stok/opname/final?route=opname' }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $product->id }}" name="id">
                <input type="hidden" value="{{ $product->nama }}" name="nama">
                <input type="hidden" name="quantity" value='{{ $product->stok }}'>
                <input type="hidden" value="{{ $product->harga_satuan }}" name="harga_satuan">
                <input type="hidden" value="{{$product->kode_barang }}" name="kode_barang">

                  <input type="number" class="form-control" id="quantity" name="jumlah" 
                    value="{{ $cartItem->attributes->jumlah??null }}">
                  <input type="text" class="form-control" id="keterangan" name="keterangan"
                    value="{{ $cartItem->attributes->keterangan??null }}">

                <button type="submit">Submit</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
