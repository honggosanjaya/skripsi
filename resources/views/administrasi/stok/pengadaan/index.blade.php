@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pengadaan</li>
  </ol>
@endsection

@section('main_content')
  @push('CSS')
    <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
  @endpush
  <div id="pengadaan">
    {{-- <h1>count: {{ $counter }} {{ $pageWasRefreshed == 1 ? 'true' : 'false' }}</h1> --}}

    <div class="px-5 pt-4">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="fs-4 fw-bold mb-4">Pengadaan</h1>
        <a href="/administrasi/stok/pengadaan/cart?route=pengadaan" class="btn btn-primary"><span
            class="iconify fs-3 me-2" data-icon="bi:cart-check-fill"></span>Keranjang</a>
      </div>

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
                <td>{{ $product->kode_barang ?? null }}</td>
                <td>{{ $product->nama ?? null }}</td>
                <td>{{ $product->satuan ?? null }}</td>

                @if ($product->max_pengadaan)
                  <td>{{ number_format($product->max_pengadaan, 0, '', '.') }}</td>
                @else
                  <td></td>
                @endif

                <td>
                  <form>
                    <input type="hidden" value="{{ $product->id }}" name="id"
                      class="input-idcart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->kode_barang }}" name="kode_barang"
                      class="input-kodecart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->nama }}" name="nama"
                      class="input-namacart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->satuan }}" name="satuan"
                      class="input-satuancart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->max_pengadaan }}" name="max_pengadaan"
                      class="input-maxpengadaancart-{{ $product->id }}">
                    <input type="hidden" value="{{ $product->harga1_satuan }}" name="harga_satuan"
                      class="input-hargasatuancart-{{ $product->id }}">

                    @if ($cartItem = \Cart::session(auth()->user()->id . 'pengadaan')->get($product->id) ?? null)
                      <div class="d-flex justify-content-between">
                        <div>jumlah</div>
                        <input data-iditem="{{ $product->id }}" type="number"
                          class="form-control input-quantitycart-{{ $product->id }}" style="width: 300px" id="quantity"
                          name="quantity" min="0" value="{{ $cartItem->quantity }}">
                      </div>
                    @else
                      <div class="d-flex justify-content-between">
                        <div>jumlah</div>
                        <input data-iditem="{{ $product->id }}" type="number"
                          class="form-control input-quantitycart-{{ $product->id }}" style="width: 300px" id="quantity"
                          name="quantity" min="0">
                      </div>
                    @endif

                    @if ($cartItem2 = \Cart::session(auth()->user()->id . 'pengadaan')->get($product->id) ?? null)
                      <div class="d-flex justify-content-between">
                        <div>harga total</div>
                        <input data-iditem="{{ $product->id }}" type="number"
                          class="form-control input-totalhargacart-{{ $product->id }}" style="width: 300px"
                          id="total_harga" name="total_harga" value="{{ $cartItem2->attributes->total_harga }}"
                          min='0'>
                      </div>
                    @else
                      <div class="d-flex justify-content-between">
                        <div>harga total</div>
                        <input data-iditem="{{ $product->id }}" type="number"
                          class="form-control input-totalhargacart-{{ $product->id }}" style="width: 300px"
                          id="total_harga" name="total_harga" min='0'>
                      </div>
                    @endif
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @push('JS')
    <script src="{{ mix('js/pengadaan.js') }}"></script>
    {{-- <script>
      var perfEntries = performance.getEntriesByType("navigation");

      if (perfEntries[0].type === "back_forward") {
        location.reload(true);
      }
    </script> --}}
  @endpush
@endsection
