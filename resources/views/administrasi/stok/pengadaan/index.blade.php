@extends('layouts.main')

@section('main_content')
  @if ($message = Session::get('sukses'))
    <p class="text-success">{{ $message }}</p>
  @endif

  <h1>Pengadaan</h1>
  <a href="/administrasi/stok/pengadaan/cart" class="btn btn-primary">Keranjang</a>

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          {{-- <th scope="col">Kode Barang</th> --}}
          <th scope="col">Nama</th>
          {{-- <th scope="col">Satuan</th> --}}
          {{-- <th scope="col">Max Pengadaan</th> --}}
          {{-- <th scope="col">Pengadaan</th> --}}
          {{-- <th scope="col">Total Harga</th> --}}
        </tr>
      </thead>
      <tbody>
        @foreach ($products as $product)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            {{-- <td>{{ $item->kode_barang }}</td> --}}
            <td>{{ $product->nama }}</td>
            {{-- <td>{{ $item->satuan }}</td> --}}
            {{-- <td>{{ $item->max_pengadaan }}</td> --}}
            <td>
              <form action="{{ route('cart.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" value="{{ $product->id }}" name="id">
                <input type="hidden" value="{{ $product->nama }}" name="nama">
                <input type="hidden" value="{{ $product->harga_satuan }}" name="harga_satuan">
                <input type="hidden" value="{{ $product->gambar }}" name="gambar">
                <input type="hidden" value="1" name="quantity">
                <button class="btn btn-primary">Add To Cart</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
