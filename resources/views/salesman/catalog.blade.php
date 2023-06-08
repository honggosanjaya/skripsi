@extends('layouts.mainreact')

@push('CSS')
  <style>
    .detail_item {
      text-decoration: none;
      color: black;
    }
  </style>
@endpush

@push('JS')
  <script>
    $('.search_barang').on('keyup', function(e) {
      let keyword = e.target.value;
      $('.cart_item').each(function() {
        if ($(this).data("search").toLowerCase().indexOf(keyword) >= 0) {
          $(this).removeClass('d-none');
        } else {
          $(this).addClass('d-none');
        };
      });
    });
  </script>
@endpush

@section('main_content')
  <div class="page_container py-4 px-3">
    <input type="text" class="form-control search_barang mb-4" placeholder="Pencarian...">

    @foreach ($data as $produk)
      <div class="cart_item" data-search="{{ $produk['nama'] }}">
        <div class="d-flex">
          @if (count($produk['gambar']) > 1)
            <div id="carouselExampleIndicators" class="carousel slide">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                  aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                  aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                  aria-label="Slide 3"></button>
              </div>
              <div class="carousel-inner">
                @foreach ($produk['gambar'] as $gambar)
                  <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/item/' . $gambar->image) }}" class="item_catalogimage">
                  </div>
                @endforeach
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          @elseif(count($produk['gambar']) == 1)
            @foreach ($produk['gambar'] as $gambar)
              <img src="{{ asset('storage/item/' . $gambar->image) }}" class="item_catalogimage">
            @endforeach
          @else
            <img src="{{ asset('images/default_produk.png') }}" class="item_catalogimage">
          @endif
        </div>

        <a href="/salesman/detailcatalog/{{ $idCust }}/{{ $produk['id'] }}" class="detail_item">
          <h1 class="my-1 fs-6 fw-bold text-uppercase">{{ $produk['nama'] ?? null }}</h1>
          <p class="mb-0 fs-7">Rp. {{ number_format($produk['harga_satuan'] ?? 0, 0, '', '.') }} /
            {{ $produk['satuan'] ?? null }}</p>
        </a>
      </div>
    @endforeach
  </div>
@endsection
