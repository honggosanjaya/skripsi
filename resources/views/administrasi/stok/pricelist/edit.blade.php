@extends('layouts/main')
@push('CSS')
  <style>
    .pricelist-cart-btn {
      position: relative;
      top: -40px;
    }
  </style>
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk/pricelist">Price list</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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

  <div class="px-5 pt-4">
    <div class="row">
      <div class="col">
        <h1 class="fs-4 mb-4">Edit Price List</h1>
      </div>
    </div>

    <form action="/administrasi/stok/produk/pricelist/edit" method="get">
      <div class="row">
        <div class="col-4">
          <select class="form-select" name="tipe_harga">
            <option value="1" {{ $input['tipe_harga'] == '1' ? 'selected' : '' }}>Harga 1</option>
            <option value="2" {{ $input['tipe_harga'] == '2' ? 'selected' : '' }}>Harga 2</option>
            <option value="3" {{ $input['tipe_harga'] == '3' ? 'selected' : '' }}>Harga 3</option>
            <option value="hpp" {{ $input['tipe_harga'] == 'hpp' ? 'selected' : '' }}>HPP</option>
          </select>
        </div>
        <div class="col-8">
          <button type="submit" class="btn btn-primary"><span class="iconify fs-4 me-1"
              data-icon="ic:baseline-filter-alt"></span>Filter</button>
        </div>
      </div>
    </form>

    <form action="/administrasi/stok/produk/pricelist/cart?route=pricelist" method="POST">
      @csrf
      <div class="row">
        <div class="col">
          <div class="float-end">
            <button type="submit" class="btn btn-primary pricelist-cart-btn">
              <span class="iconify me-1 fs-4" data-icon="bi:cart-check-fill"></span>Perubahan Final
            </button>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-sm" id="table">
          <thead>
            <tr>
              <th scope="col" class="text-center">No</th>
              <th scope="col" class="text-center">Nama Barang</th>
              <th scope="col" class="text-center">Now</th>
              <th scope="col" class="text-center">Perubahan Harga</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($items as $item)
              @php
                $cartItem = \Cart::session(auth()->user()->id . 'pricelist')->get($item->id);
              @endphp
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="{{ $item->status_enum == '1' ? 'text-success' : 'text-danger' }}">{{ $item->nama ?? null }}
                </td>

                @if ($input['tipe_harga'] ?? null)
                  @if ($input['tipe_harga'] == '1' && $item->harga1_satuan ?? null)
                    <td class="text-end">{{ number_format($item->harga1_satuan, 0, '', '.') }}</td>
                  @elseif ($input['tipe_harga'] == '2' && $item->harga2_satuan ?? null)
                    <td class="text-end">{{ number_format($item->harga2_satuan, 0, '', '.') }}</td>
                  @elseif ($input['tipe_harga'] == '3' && $item->harga3_satuan ?? null)
                    <td class="text-end">{{ number_format($item->harga3_satuan, 0, '', '.') }}</td>
                  @elseif ($input['tipe_harga'] == 'hpp' && $item->hargahpp_satuan ?? null)
                    <td class="text-end">{{ number_format($item->hargahpp_satuan, 0, '', '.') }}</td>
                  @else
                    <td></td>
                  @endif
                @else
                  <td class="text-end">{{ number_format($item->harga1_satuan ?? null, 0, '', '.') }}</td>
                @endif

                <td class="text-center">
                  @if ($item->id ?? null)
                    <input type="hidden" value="{{ $item->id }}" name="id[]"
                      class="input-idcart-{{ $item->id }}">
                    <input type="hidden" value="{{ $item->nama }}" name="nama[]"
                      class="input-namacart-{{ $item->id }}">
                    <input type="hidden" value="{{ $input['tipe_harga'] ?? null }}" name="tipe_harga[]"
                      class="input-tipehargacart-{{ $item->id }}">

                    @if ($input['tipe_harga'] == '1')
                      <input type="hidden" value="{{ $item->harga1_satuan ?? null }}" name="harga_satuan[]"
                        class="input-hargasatuancart-{{ $item->id }}">
                    @elseif ($input['tipe_harga'] == '2')
                      <input type="hidden" value="{{ $item->harga2_satuan ?? null }}" name="harga_satuan[]"
                        class="input-hargasatuancart-{{ $item->id }}">
                    @elseif ($input['tipe_harga'] == '3')
                      <input type="hidden" value="{{ $item->harga3_satuan ?? null }}" name="harga_satuan[]"
                        class="input-hargasatuancart-{{ $item->id }}">
                    @elseif ($input['tipe_harga'] == 'hpp')
                      <input type="hidden" value="{{ $item->hargahpp_satuan ?? null }}" name="harga_satuan[]"
                        class="input-hargasatuancart-{{ $item->id }}">
                    @endif

                    <input data-iditem="{{ $item->id }}" type="number" style="width: 250px;"
                      class="form-control mx-auto input-perubahanharga-{{ $item->id }}" name="perubahan_harga[]"
                      value="{{ $cartItem->attributes->perubahan_harga ?? null }}">
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </form>
  @endsection
