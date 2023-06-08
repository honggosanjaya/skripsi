@extends('layouts.mainreact')

@push('CSS')
  <style>
    .card-product {
      color: black;
      text-decoration: none;
    }

    .card-product:hover {
      color: black;
    }
  </style>
@endpush

@push('JS')
  <script>
    function convertPrice(price) {
      let convertedPrice = 'Rp. ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return convertedPrice;
    }

    $('.btn_kalkulator').on('click', function() {
      $('.form_diskon').toggleClass('d-none');
    })

    $('.btn_diskon').on('click', function() {
      const hargaNormal = $('input[name="harga_normal"]').val();
      let harga_setelahdiskon = hargaNormal;

      const diskon1 = $('input[name="diskon_1"]').val();
      const diskon2 = $('input[name="diskon_2"]').val();
      const diskon3 = $('input[name="diskon_3"]').val();
      const diskons = [diskon1, diskon2, diskon3];
      console.log(diskons);
      diskons.forEach(function(diskon) {
        if (diskon != '') {
          harga_setelahdiskon = harga_setelahdiskon - (harga_setelahdiskon * diskon / 100)
        }
      });

      $('.harga_normal').addClass('text-decoration-line-through');
      $('.harga_diskon').text(convertPrice(harga_setelahdiskon));
    })
  </script>
@endpush

@section('main_content')
  <div class="page_container py-4 px-3 detail-katalog">
    <div class="detail-product-info">
      @if (count($item['link_galery_item']) > 0)
        <div id="carouselExampleIndicators" class="carousel slide mt-4">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
              aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
              aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
              aria-label="Slide 3"></button>
          </div>
          <div class="carousel-inner">
            @foreach ($item['link_galery_item'] as $gambar)
              <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                @if ($gambar->image ?? null)
                  <img src="{{ asset('storage/item/' . $gambar->image) }}" class="d-block mx-auto" height="200">
                @else
                  <img src="{{ asset('images/default_produk.png') }}" class="d-block mx-auto" height="200">
                @endif
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
      @else
        <img src="{{ asset('images/default_produk.png') }}" class="d-block mx-auto mt-4" height="200">
      @endif

      <div class="d-flex align-items-center mt-3">
        <h1 class="fs-5 fw-bold mb-0 harga_normal">Rp. {{ number_format($item['harga_satuan'] ?? 0, 0, '', '.') }} </h1>
        <button class="btn btn-sm btn-primary ms-4 btn_kalkulator">
          <span class="iconify fs-4" data-icon="bxs:calculator"></span>
        </button>
      </div>

      <h1 class="fs-5 fw-bold mb-0 harga_diskon"></h1>
      <form class="form_diskon d-none mt-3">
        <input type="hidden" value="{{ $item['harga_satuan'] ?? 0 }}" name="harga_normal">
        <div class="mb-3">
          <label class="form-label">Diskon 1</label>
          <input type="number" class="form-control" name="diskon_1">
        </div>
        <div class="mb-3">
          <label class="form-label">Diskon 2</label>
          <input type="number" class="form-control" name="diskon_2">
        </div>
        <div class="mb-3">
          <label class="form-label">Diskon 3</label>
          <input type="number" class="form-control" name="diskon_3">
        </div>
        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-sm btn-warning btn_diskon">OK</button>
        </div>
      </form>

      <h2 class="fs-6 text-uppercase fw-bold mt-3 mb-1">{{ $item['nama'] }}</h2>
      <span>Stok {{ $item['stok'] }}</span>
    </div>
    <hr>

    @if ($item['deskripsi'])
      <div class="deskripsi_produk">
        <h1 class="fs-5 fw-bold">Deskripsi Produk</h1>
        <h2 class="fs-6">{{ $item['deskripsi'] }}</h2>
      </div>
      <hr>
    @endif

    @if (count($related_item) > 0)
      <div class="related-product">
        <h1 class="fs-5 fw-bold">Related Produk</h1>
        <div class="horizontal-scroll-wrapper">
          @foreach ($related_item as $item)
            <a href="/salesman/detailcatalog/{{ $idCust }}/{{ $item->id }}" class="card-product">
              @if ($item->gambar ?? null)
                <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid">
              @endif
              <div class="card-product--text">
                <h3 class="fs-6 fw-normal text-capitalize">{{ $item->nama }}</h3>
                <h1 class="fs-6 fw-bold">
                  @if ($tipeHarga == 3 && ($item->harga3_satuan ?? null))
                    Rp. {{ number_format($item->harga3_satuan, 0, '', '.') }}
                  @elseif ($tipeHarga == 2 && ($item->harga2_satuan ?? null))
                    Rp. {{ number_format($item->harga2_satuan, 0, '', '.') }}
                  @else
                    Rp. {{ number_format($item->harga1_satuan ?? 0, 0, '', '.') }}
                  @endif
                </h1>
              </div>
            </a>
          @endforeach
        </div>
        <hr>
      </div>
    @endif

    @if (count($category_item) > 0)
      <div class="related-product">
        <h1 class="fs-5 fw-bold">Produk Dengan Category Serupa</h1>
        <div class="horizontal-scroll-wrapper">
          @foreach ($category_item as $item)
            <a href="/salesman/detailcatalog/{{ $idCust }}/{{ $item->id }}" class="card-product">
              @if ($item->gambar ?? null)
                <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid">
              @endif
              <div class="card-product--text">
                <h3 class="fs-6 fw-normal text-capitalize">{{ $item->nama }}</h3>
                <h1 class="fs-6 fw-bold">
                  @if ($tipeHarga == 3 && ($item->harga3_satuan ?? null))
                    Rp. {{ number_format($item->harga3_satuan, 0, '', '.') }}
                  @elseif ($tipeHarga == 2 && ($item->harga2_satuan ?? null))
                    Rp. {{ number_format($item->harga2_satuan, 0, '', '.') }}
                  @else
                    Rp. {{ number_format($item->harga1_satuan ?? 0, 0, '', '.') }}
                  @endif
                </h1>
              </div>
            </a>
          @endforeach
        </div>
        <hr>
      </div>
    @endif

    @if (count($new_item) > 0)
      <div class="related-product">
        <h1 class="fs-5 fw-bold">Produk Terbaru</h1>
        <div class="horizontal-scroll-wrapper">
          @foreach ($new_item as $item)
            <a href="/salesman/detailcatalog/{{ $idCust }}/{{ $item->id }}" class="card-product">
              @if ($item->gambar ?? null)
                <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid">
              @endif
              <div class="card-product--text">
                <h3 class="fs-6 fw-normal text-capitalize">{{ $item->nama }}</h3>
                <h1 class="fs-6 fw-bold">
                  @if ($tipeHarga == 3 && ($item->harga3_satuan ?? null))
                    Rp. {{ number_format($item->harga3_satuan, 0, '', '.') }}
                  @elseif ($tipeHarga == 2 && ($item->harga2_satuan ?? null))
                    Rp. {{ number_format($item->harga2_satuan, 0, '', '.') }}
                  @else
                    Rp. {{ number_format($item->harga1_satuan ?? 0, 0, '', '.') }}
                  @endif
                </h1>
              </div>
            </a>
          @endforeach
        </div>
        <hr>
      </div>
    @endif
  </div>
@endsection
