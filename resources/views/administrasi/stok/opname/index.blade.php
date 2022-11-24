@extends('layouts.main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Stok Opname</li>
  </ol>
@endsection

@section('main_content')
  {{-- @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif --}}
  <div id="opname" class="pt-4 px-5">
    <div class="loading-indicator d-none">
      <div class="spinner-grow spinner-grow-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="spinner-grow spinner-grow-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="spinner-grow spinner-grow-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
      <h1 class="fs-4 fw-4">Opname</h1>
      <a href="/administrasi/stok/opname/final?route=opname" class="btn btn-primary"><span class="iconify me-1 fs-4"
          data-icon="bi:cart-check-fill"></span>Opname final</a>
    </div>

    {{-- <h1>count: {{ $counter }} {{ $pageWasRefreshed == 1 ? 'true' : 'false' }}</h1> --}}

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
              <td>{{ $product->kode_barang ?? null }}</td>
              <td>{{ $product->nama ?? null }}</td>
              <td>{{ $product->satuan ?? null }}</td>
              <td class="text-center">{{ number_format($product->min_stok ?? 0, 0, '', '.') }}</td>
              <td class="text-center">{{ number_format($product->max_stok ?? 0, 0, '', '.') }}</td>
              <td class="text-center">{{ number_format($product->stok ?? 0, 0, '', '.') }}</td>
              <td class="text-center stok-baru-{{ $product->id }}">
                {{ number_format(($product->stok ?? 0) + ($cartItem->attributes->jumlah ?? 0), 0, '', '.') }}
              </td>

              <td>
                @if ($product->id ?? null)
                  <form>
                    <input type="hidden" value="{{ $product->id }}" name="id"
                      class="input-idcart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->nama }}" name="nama"
                      class="input-namacart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->stok }}" name="quantity"
                      class="input-quantitycart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->harga1_satuan }}" name="harga_satuan"
                      class="input-hargasatuancart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->kode_barang }}" name="kode_barang"
                      class="input-kodecart-{{ $product->id }}">
                    <div class="d-flex justify-content-between">
                      <div>jumlah</div>
                      <input type="number" class="form-control input-jumlahcart-{{ $product->id }}" id="jumlah"
                        name="jumlah" style="width: 180px" data-iditem="{{ $product->id }}"
                        value="{{ $cartItem->attributes->jumlah ?? null }}">
                    </div>
                    <div class="d-flex justify-content-between">
                      <div>keterangan</div>
                      <input type="text" class="form-control input-keterangancart-{{ $product->id }}" id="keterangan"
                        name="keterangan" style="width: 180px" data-iditem="{{ $product->id }}"
                        value="{{ $cartItem->attributes->keterangan ?? null }}">
                    </div>
                  </form>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/opname.js') }}"></script>
  @endpush
@endsection
