@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Produk</h1>
    </div>
    <div class="position-relative">
      <a href="/customer/produk/cart?route=customerOrder" class="cart">
        <span class="iconify fs-3" set-cart-position" data-icon="clarity:shopping-cart-solid"></span>
        @if (($cartQuantity = \Cart::session(auth()->user()->id . 'customerOrder')->getTotalQuantity()) != 0)
          <div class="cart-quantity">
            {{ $cartQuantity }}
          </div>
        @else
          <div class="cart-quantity d-none">
            {{ $cartQuantity }}
          </div>
        @endif
      </a>
    </div>
  </header>
@endsection

@section('content')
  <div class="d-flex justify-content-between mt-3">
    <form method="GET" action="/customer/produk/cari">
      <div class="input-group">
        <input type="text" class="form-control" name="cari" placeholder="Cari Produk..."
          value="{{ request('cari') }}">
        <button type="submit" class="btn btn-primary">
          <span class="iconify" data-icon="fe:search"></span>
        </button>
      </div>
    </form>

    <div>
      <a href="/filterProduk"></a>
      <i type="button" class="bi bi-funnel-fill fs-3" data-bs-toggle="modal" data-bs-target="#exampleModal"></i>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" id="filterprice" value="price" checked>
                <label class="form-check-label" for="filterprice">
                  Harga Item
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="filter" id="filternama" value="nama">
                <label class="form-check-label" for="filternama">
                  Nama Item
                </label>
              </div>
              <hr>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="order" id="asc" value="asc" checked>
                <label class="form-check-label" for="asc">
                  Terendah
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="order" id="desc" value="desc">
                <label class="form-check-label" for="desc">
                  Tertinggi
                </label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-filter-produk" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary submit-filter-produk">Filter</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="list-produk" class="mt-4">
    @include('customer.c_listproduk')
  </div>
@endsection
