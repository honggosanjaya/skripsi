@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Stok Retur</li>
  </ol>
@endsection

@section('main_content')
  @push('CSS')
    <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
  @endpush

  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div id="stokretur">
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

    <div class="px-5 pt-4">
      <div class="d-flex justify-content-between align-items-center">
        <h1 class="fs-4 fw-bold mb-4">Stok Retur</h1>
        <a href="/administrasi/stok/stokretur/cart?route=stokretur" class="btn btn-primary"><span
            class="iconify fs-3 me-2" data-icon="bi:cart-check-fill"></span>Keranjang</a>
      </div>

      <div class="table-responsive mt-3">
        <table class="table table-hover table-sm">
          <thead>
            <tr>
              <th scope="col" class="text-center">No</th>
              <th scope="col" class="text-center">Kode Barang</th>
              <th scope="col" class="text-center">Nama</th>
              <th scope="col" class="text-center">Satuan</th>
              <th scope="col" class="text-center">Stok</th>
              <th scope="col" class="text-center">Stok Retur</th>
              <th scope="col" class="text-center">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($products as $product)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $product->kode_barang ?? null }}</td>
                <td>{{ $product->nama ?? null }}</td>
                <td>{{ $product->satuan ?? null }}</td>
                <td>{{ number_format($product->stok ?? 0, 0, '', '.') }}</td>
                <td>{{ number_format($product->stok_retur ?? 0, 0, '', '.') }}</td>
                @if ($product->id ?? null)
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
                      <input type="hidden" value="{{ $product->stok }}" name="stok"
                        class="input-stokcart-{{ $product->id }}">
                      <input type="hidden" value="{{ $product->stok_retur }}" name="stok_retur"
                        class="input-stokreturcart-{{ $product->id }}">

                      @if ($cartItem = \Cart::session(auth()->user()->id . 'stokretur')->get($product->id))
                        <div class="d-flex justify-content-between">
                          <div>jumlah</div>
                          <input data-iditem="{{ $product->id }}" type="number"
                            class="form-control input-quantitycart-{{ $product->id }}" style="width: 300px"
                            id="quantity" name="quantity" min="0" max="{{ $product->stok_retur }}"
                            value="{{ $cartItem->quantity ?? null }}">
                        </div>
                      @else
                        <div class="d-flex justify-content-between">
                          <div>jumlah</div>
                          <input data-iditem="{{ $product->id }}" type="number"
                            class="form-control input-quantitycart-{{ $product->id }}" style="width: 300px"
                            id="quantity" name="quantity" min="0" max="{{ $product->stok_retur }}">
                        </div>
                      @endif

                      {{-- @if ($cartItem2 = \Cart::session(auth()->user()->id . 'stokretur')->get($product->id))
                        <div class="d-flex justify-content-between">
                          <div>Metode</div>
                          <select data-iditem="{{ $product->id }}"
                            class="form-select select-metodecart-{{ $product->id }}" style="width: 300px" id="metode"
                            name="metode">
                            @if ($cartItem2->attributes->metode == 'potongan')
                              <option value="tukarguling">Tukar Guling</option>
                              <option value="potongan" selected>Potongan</option>
                            @else
                              <option value="tukarguling" selected>Tukar Guling</option>
                              <option value="potongan">Potongan</option>
                            @endif
                          </select>
                        </div>
                      @else
                        <div class="d-flex justify-content-between">
                          <div>Metode</div>
                          <select data-iditem="{{ $product->id }}"
                            class="form-select select-metodecart-{{ $product->id }}" style="width: 300px" id="metode"
                            name="metode">
                            <option value="tukarguling" selected>Tukar Guling</option>
                            <option value="potongan">Potongan</option>
                          </select>
                        </div>
                      @endif --}}
                    </form>
                  </td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @push('JS')
    <script src="{{ mix('js/stokretur.js') }}"></script>
  @endpush
@endsection
