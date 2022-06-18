@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Stok Opname</li>
  </ol>
@endsection
@section('main_content')
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif
  <div id="opname" class="pt-4 px-5">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="fs-4 fw-4">Opname</h1>
      <a href="/administrasi/stok/opname/final?route=opname" class="btn btn-primary"><span class="iconify me-1 fs-4"
          data-icon="bi:cart-check-fill"></span>Opname final</a>
    </div>

    <div class="table-responsive mt-3">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Kode Barang</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Min Stok</th>
            <th scope="col" class="text-center">Max Stok</th>
            <th scope="col" class="text-center">Stok Saat Ini</th>
            <th scope="col" class="text-center">Stok Baru</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $product)
            @php
              $cartItem = \Cart::session(auth()->user()->id . 'opname')->get($product->id);
            @endphp
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>{{ $product->kode_barang }}</td>
              <td>{{ $product->nama }}</td>
              <td>{{ $product->satuan }}</td>
              <td class="text-center">{{ $product->min_stok }}</td>
              <td class="text-center">{{ $product->max_stok }}</td>
              <td class="text-center">{{ $product->stok }}</td>
              <td class="text-center">{{ $product->stok + ($cartItem->attributes->jumlah ?? 0) }}</td>
              <td>
                <form action="{{ '/administrasi/stok/opname/final?route=opname' }}" method="POST"
                  enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" value="{{ $product->id }}" name="id">
                  <input type="hidden" value="{{ $product->nama }}" name="nama">
                  <input type="hidden" name="quantity" value='{{ $product->stok }}'>
                  <input type="hidden" value="{{ $product->harga_satuan }}" name="harga_satuan">
                  <input type="hidden" value="{{ $product->kode_barang }}" name="kode_barang">
                  <div class="d-flex justify-content-between">
                    <div>jumlah</div>
                    <input type="number" class="form-control" id="quantity" name="jumlah" style="width: 180px"
                      data-iditem="{{ $product->id }}" value="{{ $cartItem->attributes->jumlah ?? null }}">
                  </div>
                  <div class="d-flex justify-content-between">
                    <div>keterangan</div>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" style="width: 180px"
                      data-iditem="{{ $product->id }}" value="{{ $cartItem->attributes->keterangan ?? null }}">
                  </div>
                  @if ($cartItem->attributes->keterangan ?? null)
                    <button class="btn btn-success submit-cart-{{ $product->id }}" disabled
                      type="submit">Submit</button>
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
