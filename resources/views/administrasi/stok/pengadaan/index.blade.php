@extends('layouts.main')

@section('main_content')
@push('JS')
  <script src="{{ mix('js/administrasi.js') }}"></script>
@endpush
<div id="pengadaan">
    @if (session()->has('pesanSukses'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('pesanSukses') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <h1>Pengadaan</h1>
    <a href="/administrasi/stok/pengadaan/cart?route=pengadaan" class="btn btn-primary">Keranjang</a>

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
                            <form action="{{ route('cart.store') . '?route=pengadaan' }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $product->id }}" name="id">
                                <input type="hidden" value="{{ $product->kode_barang }}" name="kode_barang">
                                <input type="hidden" value="{{ $product->nama }}" name="nama">
                                <input type="hidden" value="{{ $product->satuan }}" name="satuan">
                                <input type="hidden" value="{{ $product->max_pengadaan }}" name="max_pengadaan">
                                <input type="hidden" value="{{ $product->harga_satuan }}" name="harga_satuan">

                                @if ($cartItem = \Cart::session(auth()->user()->id . 'pengadaan')->get($product->id) ?? null)
                                    <div class="d-flex justify-content-between">
                                        <div>jumlah</div>
                                        <input data-iditem="{{ $product->id }}" type="number" class="form-control" style="width: 300px" id="quantity" name="quantity" min="0" value="{{ $cartItem->quantity }}">
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between">
                                        <div>jumlah</div>
                                        <input data-iditem="{{ $product->id }}" type="number" class="form-control" style="width: 300px" id="quantity" name="quantity" min="0">
                                    </div>
                                @endif

                                @if ($cartItem2 = \Cart::session(auth()->user()->id . 'pengadaan')->get($product->id) ?? null)
                                    <div class="d-flex justify-content-between">
                                        <div>harga</div>
                                        <input data-iditem="{{ $product->id }}" type="number" class="form-control" style="width: 300px" id="total_harga" name="total_harga" value="{{ $cartItem2->attributes->total_harga }}">
                                    </div>
                                @else
                                    <div class="d-flex justify-content-between">
                                        <div>harga</div>
                                        <input data-iditem="{{ $product->id }}" type="number" class="form-control" style="width: 300px" id="total_harga" name="total_harga">
                                    </div>
                                @endif
                                @if ($cartItem = \Cart::session(auth()->user()->id . 'pengadaan')->get($product->id) ?? null)
                                  <button class="btn btn-success submit-cart-{{ $product->id }}" disabled type="submit">Submit</button>
                                @else
                                  <button class="btn btn-primary submit-cart-{{ $product->id }}" type="submit">Submit</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
