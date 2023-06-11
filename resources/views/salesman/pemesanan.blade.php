@extends('layouts.mainreact')

@push('CSS')
  <style>
    .page_container {
      position: relative;
    }

    .btn_cart {
      position: absolute;
      top: -3rem;
      right: 1rem;
    }

    div.scrollmenu {
      overflow: auto;
      white-space: nowrap;
    }

    div.scrollmenu button {
      display: inline-block;
    }
  </style>
@endpush

@push('JS')
  <script>
    $('.search_barang').on('keyup', function(e) {
      let keyword = e.target.value;
      $('.product_card').each(function() {
        if ($(this).data("search").toLowerCase().indexOf(keyword) >= 0) {
          $(this).removeClass('d-none');
        } else {
          $(this).addClass('d-none');
        };
      });
    });
  </script>

  <script>
    var idTrip = null;
    var totalCart = parseInt($('input[name="jumlah_item_keranjang"]').val());

    function catatTripOrder(koordinat) {
      $.ajax({
        url: window.location.origin + `/api/tripOrderCustomer`,
        method: "post",
        data: {
          idCustomer: "{{ $customer->id }}",
          idStaff: "{{ auth()->user()->id_users }}",
          koordinat: koordinat,
          jam_masuk: Date.now() / 1000,
        },
        success: function(response) {
          console.log(response);
          idTrip = response.data.id;
          $('input[name="idtrip"]').val(response.data.id);
        }
      })
    }

    navigator.permissions.query({
      name: 'geolocation'
    }).then((result) => {
      if (result.state === 'prompt') {
        let timerInterval;
        let seconds = 7;
        Swal.fire({
          title: 'Peringatan Izin Akses Lokasi Perangkat',
          html: 'Selanjutnya kami akan meminta akses lokasi anda, mohon untuk mengizinkannya. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          timer: 7000,
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
              if (seconds > 0) {
                seconds -= 1;
              }
              b.textContent = seconds;
            }, 1000);
          },
        }).then((result) => {
          navigator.geolocation.getCurrentPosition(function(position) {
            const koordinat = position.coords.latitude + '@' + position.coords.longitude;
            $('input[name="koordinat"]').val(koordinat);
            catatTripOrder(koordinat);
          });
        })
      } else if (result.state === 'granted') {
        navigator.geolocation.getCurrentPosition(function(position) {
          const koordinat = position.coords.latitude + '@' + position.coords.longitude;
          $('input[name="koordinat"]').val(koordinat);
          catatTripOrder(koordinat);
        });
      } else if (result.state === 'denied') {
        let timerInterval2;
        let seconds2 = 4;
        catatTripOrder('0@0');
        Swal.fire({
          title: 'Tidak Ada Akses Lokasi Perangkat',
          html: 'Agar memudahkan kunjungan silahkan buka pengaturan browser anda dan ijinkan aplikasi mengakses lokasi. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonText: 'Tutup',
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval2 = setInterval(() => {
              if (seconds2 > 0) {
                seconds2 -= 1;
              }
              b.textContent = seconds2;
            }, 1000);
            setTimeout(() => {
              Swal.hideLoading()
            }, 4000);
          },
        })
      }
    });

    $('.kode_customer .btn-danger').on('click', function() {
      $(this).addClass('d-none');
      $('.kode_customer .btn-primary').removeClass('d-none');
      $('.form_kode_customer').addClass('d-none');
    })

    $('.kode_customer .btn-primary').on('click', function() {
      $(this).addClass('d-none');
      $('.kode_customer .btn-danger').removeClass('d-none');
      $('.form_kode_customer').removeClass('d-none');
    })

    $('textarea[name="alasan_penolakan"]').on('change', function() {
      if ($(this).val()) {
        $('#tidakJadiPesanModal .btn-danger').removeAttr('disabled');
      } else {
        $('#tidakJadiPesanModal .btn-danger').attr("disabled", true);
      }
    })

    $('.card_historyItem .btn-warning').on('click', function() {
      $(this).addClass('d-none');
      $(this).parents('.card_historyItem').find('input[name="stok_left"]').removeAttr('disabled');
      $(this).parents('.card_historyItem').find('.btn-success').removeClass('d-none');
    })

    $('.card_historyItem .btn-success').on('click', function(e) {
      const idItem = e.target.value;
      const newVal = $(this).parents('.card_historyItem').find('input[name="stok_left"]').val();
      const thisis = $(this);
      $.ajax({
        url: window.location.origin + `/api/salesman/updateStock`,
        method: "post",
        data: {
          id_customer: "{{ $customer->id }}",
          'id_item': idItem,
          'quantity': newVal
        },
        success: function(response) {
          thisis.parents('.card_historyItem').find('input[name="stok_left"]').attr("disabled", true);
          thisis.addClass('d-none');
          thisis.parents('.card_historyItem').find('.btn-warning').removeClass('d-none');
        }
      })
    })

    $('.btn_belanjalagi_keluar').on('click', function() {
      Swal.fire({
        title: 'Apakah anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Keluar!'
      }).then((result) => {
        if (result.isConfirmed) {
          if (idTrip != null) {
            $('.loader').removeClass('d-none');
            $.ajax({
              url: window.location.origin + `/api/keluarToko/${idTrip}`,
              method: "post",
              data: {
                'alasan_penolakan': null,
                'isBelanjaLagi': true,
              },
              success: function(response) {
                console.log(response);
                let redirect = "{{ env('MIX_APP_URL') }}" + '/salesman';
                window.location.replace(redirect);
              }
            })
          }
        }
      })
    })

    $('#tidakJadiPesanModal .btn-danger').on('click', function() {
      Swal.fire({
        title: 'Apakah anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Keluar!'
      }).then((result) => {
        if (result.isConfirmed) {
          if (idTrip != null) {
            const alasanPenolakan = $('textarea[name="alasan_penolakan"]').val();
            $('.loader').removeClass('d-none');
            $('#tidakJadiPesanModal .btn-danger').attr("disabled", true);
            $.ajax({
              url: window.location.origin + `/api/keluarToko/${idTrip}`,
              method: "post",
              data: {
                'alasan_penolakan': alasanPenolakan,
                'isBelanjaLagi': "{{ $isBelanjaLagi }}",
              },
              success: function(response) {
                console.log(response);
                idTrip = response.data.id;
                let redirect = "{{ env('MIX_APP_URL') }}" + '/salesman';
                window.location.replace(redirect);
              }
            })
          }
        }
      })
    })
  </script>

  <script>
    $('.button_min').on('click', function() {
      let oldVal = $(this).parents('.addtocart_action').find('input[name="quantity[]"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      if ((parseInt(oldVal) - 1) > 0) {
        $(this).parents('.addtocart_action').find('input[name="quantity[]"]').val(parseInt(oldVal) - 1);
        totalCart -= 1;
        $('.jumlah_item_keranjang').text(totalCart);
      } else {
        $(this).parents('.addtocart_action').find('input[name="quantity[]"]').val(0);
      }
    })

    $('.button_plus').on('click', function() {
      let oldVal = $(this).parents('.addtocart_action').find('input[name="quantity[]"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      if ($(this).parents('.addtocart_action').attr("data-stokrealtime") >= (parseInt(oldVal) + 1)) {
        $(this).parents('.addtocart_action').find('input[name="quantity[]"]').val(parseInt(oldVal) + 1);
        totalCart += 1;
        $('.jumlah_item_keranjang').text(totalCart);
      }
    })

    $('input[name="quantity[]"]').on('change', function(e) {
      totalCart += parseInt(e.target.value);
      $('.jumlah_item_keranjang').text(totalCart);
    })
  </script>

  <script>
    $('.btn_filter').on('click', function() {
      $('.filter_section').toggleClass('d-none');
    });

    var rooms = $('.product_card');
    var roomList = $('.productCard_wrapper');

    function sortPriceMax() {
      roomList.empty();
      rooms.sort(function(a, b) {
        return $(a).data('price') - $(b).data('price')
      });
      roomList.append(rooms);
    }

    function sortPriceMin() {
      roomList.empty();
      rooms.sort(function(a, b) {
        return $(b).data('price') - $(a).data('price')
      });
      roomList.append(rooms);
    }

    $('.harga_rendah').on('click', function() {
      sortPriceMax();
    });

    $('.harga_tinggi').on('click', function() {
      sortPriceMin();
    });

    $('.tanpa_filter').on('click', function() {
      roomList.empty();
      rooms.sort(function(a, b) {
        return $(a).data('order') - $(b).data('order')
      });
      roomList.append(rooms);
    })

    $('.kecil_besar').on('click', function() {
      var alphabeticallyOrderedDivs = $('.product_card').sort(function(a, b) {
        return String.prototype.localeCompare.call($(a).data('search').toLowerCase(), $(b).data('search')
          .toLowerCase());
      });
      roomList.empty();
      roomList.append(alphabeticallyOrderedDivs);
    });

    $('.besar_kecil').on('click', function() {
      var alphabeticallyOrderedDivs = $('.product_card').sort(function(a, b) {
        return String.prototype.localeCompare.call($(b).data('search').toLowerCase(), $(a).data('search')
          .toLowerCase());
      });
      roomList.empty();
      roomList.append(alphabeticallyOrderedDivs);
    });
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <div class="loader d-none"></div>
    <div class="kode_customer">
      <div class="d-flex justify-content-between mb-3">
        <p class='fw-bold mb-0'>Sudah punya kode customer?</p>
        <button class="btn btn-danger btn-sm d-none">Batal</button>
        <button class="btn btn-primary btn-sm">Punya</button>
      </div>

      <form class="form_kode_customer d-none">
        <div class="input-group">
          <input type="text" class="form-control" name="kode_pesanan">
          <button type="submit" class="btn btn-primary" disabled>
            <div class="btn_text">
              {{-- <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
              </div> --}}
              Proses
            </div>
          </button>
        </div>
      </form>

      <small class='text-success success_kode_customer'></small>
      <small class='text-danger error_kode_customer'></small>
    </div>

    <form action="/salesman/addtocart/{{ $customer->id }}" method="POST">
      @csrf
      <input type="hidden" name="idtrip" value="">
      <input type="hidden" name="koordinat" value="0@0">
      <input type="hidden" name="kodePesanan" value="">
      <input type="hidden" name="jumlah_item_keranjang"
        value="{{ \Cart::session(auth()->user()->id . 'salesman')->getTotalQuantity() }}">

      <button type="submit" class="btn btn_cart">
        <span class="iconify fs-2 text-white" data-icon="clarity:shopping-cart-solid"></span>
        <span class='text-white fw-bold jumlah_item_keranjang'>
          {{ \Cart::session(auth()->user()->id . 'salesman')->getTotalQuantity() }}
        </span>
      </button>

      <div class="history-item mt-4">
        <h1 class='fs-5 fw-bold'>History Item</h1>
        @if (count($history) > 0)
          @foreach ($history as $item)
            @php
              $cartItem = \Cart::session(auth()->user()->id . 'salesman')->get($item->linkItem->id);
              if ($customer->tipe_harga == 2 && ($item->linkItem->harga2_satuan ?? null)) {
                  $harga_blmdiskon = $item->linkItem->harga2_satuan;
              } elseif ($customer->tipe_harga == 3 && ($item->linkItem->harga3_satuan ?? null)) {
                  $harga_blmdiskon = $item->linkItem->harga3_satuan;
              } else {
                  $harga_blmdiskon = $item->linkItem->harga1_satuan;
              }
              
              $harga_sdhdiskon = $harga_blmdiskon - (($customer->linkCustomerType->diskon ?? 0) * $harga_blmdiskon) / 100;
            @endphp

            <div class='card_historyItem position-relative p-3'>
              @if (
                  $item->linkItem->stok < 10 &&
                      $item->linkItem->stok > 0 &&
                      $item->linkItem->status_enum != '-1' &&
                      $item->linkItem->stok > $item->linkItem->min_stok)
                <span class="badge badge_stok">Stok Menipis</span>
              @endif

              @if (
                  $item->linkItem->stok != null &&
                      ($item->linkItem->status_enum == '-1' ||
                          $item->linkItem->stok == 0 ||
                          $item->linkItem->stok <= $item->linkItem->min_stok))
                <span class="badge badge_stok">Tidak Tersedia</span>
              @endif

              <div class="row">
                <div class="col-2">
                  @if ($item->linkItem->gambar ?? null)
                    <img src="{{ asset('storage/item/' . $item->linkItem->gambar) }}" class="item_image">
                  @else
                    <img src="{{ asset('images/default_produk.png') }}" class="item_image border">
                  @endif
                </div>
                <div class="col-10">
                  <h1 class="fs-6 ms-2 mb-1 text-capitalize fw-bold">{{ $item->linkItem->nama }}</h1>
                  <p class="mb-0 ms-2">{{ $harga_sdhdiskon }} / {{ $item->linkItem->satuan ?? null }}</p>
                </div>

                <p class="mb-0">Max stok : {{ $item->stok_maksimal_customer ?? null }}</p>
                <div class="row">
                  <div class="col">
                    <p class="mb-0">Stok left</p>
                  </div>
                  <div class="col">
                    <input type="number" class="form-control" value="{{ $item->stok_terakhir_customer ?? 0 }}"
                      name="stok_left" disabled>
                  </div>
                  <div class="col">
                    <button type="button" class="btn btn-warning">Ubah</button>
                    <button type="button" class="btn btn-success d-none" value="{{ $item->linkItem->id }}">OK</button>
                  </div>
                </div>
              </div>

              <div class="row mt-2">
                <div class="col-3">
                  <p class="mb-0">Stok :</p>
                </div>
                <div class="col-9">
                  <table class='table table-bordered border-secondary mb-0'>
                    <thead>
                      <tr>
                        <th scope="col">Real Time</th>
                        <th scope="col">Today</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          {{ ($item->linkItem->stok ?? 0) + ($groupingItemStok[$item->linkItem->id] ?? 0) - ($orderRealTime[$item->linkItem->id] ?? 0) }}
                          {{ $item->linkItem->satuan }}
                        </td>
                        <td>{{ ($item->linkItem->stok ?? 0) + ($groupingItemStok[$item->linkItem->id] ?? 0) }}
                          {{ $item->linkItem->satuan }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              @if (
                  $item->linkItem->status_enum != '-1' &&
                      (($item->linkItem->stok != 0 && $item->linkItem->stok > $item->linkItem->min_stok) ||
                          $item->linkItem->stok == null))
                <div class="d-flex justify-content-between mt-2 w-75 mx-auto addtocart_action"
                  data-stokrealtime="{{ ($item->linkItem->stok ?? 0) + ($groupingItemStok[$item->linkItem->id] ?? 0) - ($orderRealTime[$item->linkItem->id] ?? 0) }}">
                  <button type="button" class="btn btn-sm btn-primary button_min">-</button>
                  <input type="number" name="quantity[]" value="{{ $cartItem->quantity ?? null }}"
                    class="form-control w-50 input-quantitycart-{{ $item->linkItem->id }}">
                  <button type="button" class="btn btn-sm btn-primary button_plus">+</button>
                </div>
                <input type="hidden" value="{{ $item->linkItem->id }}" name="id[]"
                  class="input-idcart-{{ $item->linkItem->id }}">
                <input type="hidden" value="{{ $item->linkItem->nama }}" name="nama[]"
                  class="input-namacart-{{ $item->linkItem->id }}">

                @if ($item->linkItem->gambar ?? null)
                  <input type="hidden" value="{{ asset('storage/item/' . $item->linkItem->gambar) }}" name="gambar[]"
                    class="input-gambarcart-{{ $item->linkItem->id }}">
                @else
                  <input type="hidden" value="{{ asset('images/default_produk.png') }}" name="gambar[]"
                    class="input-gambarcart-{{ $item->linkItem->id }}">
                @endif

                <input type="hidden" value="{{ $harga_sdhdiskon }}" name="harga_satuan[]"
                  class="input-hargasatuancart-{{ $item->linkItem->id }}">
                <input type="hidden" value="{{ $harga_blmdiskon }}" name="harga_normal[]"
                  class="input-hargasatuancart-{{ $item->linkItem->id }}">
              @endif
            </div>
          @endforeach
        @else
          <small class="text-danger text-center d-block">Tidak Ada Riwayat Pembelian</small>
        @endif
      </div>

      <div class="my-5 d-flex justify-content-between align-items-center">
        <h1 class="fs-6 fw-bold">Customer tidak jadi pesan ?</h1>
        @if (($isBelanjaLagi ?? null) == 'true')
          <button type="button" class="btn btn-danger btn_belanjalagi_keluar">Keluar</button>
        @else
          <button type="button" class="btn btn-danger" data-bs-toggle="modal"
            data-bs-target="#tidakJadiPesanModal">Keluar</button>
        @endif
      </div>

      <div>
        <div class="d-flex justify-content-between mb-3">
          <h1 class='fs-5 mb-0 fw-bold'>Item</h1>
          <button type="button" class='btn btn_filter'>
            <span class="iconify fs-3" data-icon="ci:filter"></span>
          </button>
        </div>

        <div class="filter_section mb-3 d-none">
          <label class="form-label">Urutkan</label>
          <div class="scrollmenu">
            <button type="button" class="btn btn-outline-secondary harga_rendah me-2">Harga Terendah</button>
            <button type="button" class="btn btn-outline-secondary harga_tinggi me-2">Harga Tertinggi</button>
            <button type="button" class="btn btn-outline-secondary kecil_besar me-2">A-Z</button>
            <button type="button" class="btn btn-outline-secondary besar_kecil me-2">Z-A</button>
            <button type="button" class="btn btn-secondary tanpa_filter">Tanpa Filter</button>
          </div>
        </div>

        <input type="text" class="form-control search_barang" name="cari_produk" placeholder="Cari Produk...">

        <div class="productCard_wrapper mt-4">
          @foreach ($items as $index => $item)
            @php
              $cartItem = \Cart::session(auth()->user()->id . 'salesman')->get($item->id);
              if ($customer->tipe_harga == 2 && ($item->harga2_satuan ?? null)) {
                  $harga_blmdiskon = $item->harga2_satuan;
              } elseif ($customer->tipe_harga == 3 && ($item->harga3_satuan ?? null)) {
                  $harga_blmdiskon = $item->harga3_satuan;
              } else {
                  $harga_blmdiskon = $item->harga1_satuan;
              }
              $harga_sdhdiskon = $harga_blmdiskon - (($customer->linkCustomerType->diskon ?? 0) * $harga_blmdiskon) / 100;
            @endphp

            <div class="card product_card" data-iditem="{{ $item->id }}" data-search="{{ $item->nama }}"
              data-price="{{ $harga_sdhdiskon ?? 0 }}" data-order={{ $index }}>
              <div class="product_img">
                @if ($item->gambar ?? null)
                  <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid">
                @else
                  <img src="{{ asset('images/default_produk.png') }}" class="img-fluid">
                @endif
              </div>

              <div class="product_desc">
                <h1 class='nama_produk fs-6'>{{ $item->nama }}</h1>
                <p class='mb-0 text-decoration-line-through'>
                  Rp. {{ number_format($harga_blmdiskon ?? 0, 0, '', '.') }}
                </p>
                <p class='mb-0'>
                  <b class='text-danger'>
                    Rp. {{ number_format($harga_sdhdiskon ?? 0, 0, '', '.') }}
                  </b> /{{ $item->satuan }}
                </p>
                <p class='mb-0'>Stok: </p>
                <table class='table table-bordered border-secondary mb-0'>
                  <thead>
                    <tr>
                      <th scope="col">R.Time</th>
                      <th scope="col">Today</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        {{ ($item->stok ?? 0) + ($groupingItemStok[$item->id] ?? 0) - ($orderRealTime[$item->id] ?? 0) }}
                        {{ $item->satuan }}
                      </td>
                      <td>{{ ($item->stok ?? 0) + ($groupingItemStok[$item->id] ?? 0) }} {{ $item->satuan }}</td>
                    </tr>
                  </tbody>
                </table>

                <div class="d-flex justify-content-between mt-2 addtocart_action"
                  data-stokrealtime="{{ ($item->stok ?? 0) + ($groupingItemStok[$item->id] ?? 0) - ($orderRealTime[$item->id] ?? 0) }}">
                  <button type="button" class="btn btn-sm btn-primary button_min">-</button>
                  <input type="number" class="form-control input-quantitycart-{{ $item->id }}" name="quantity[]"
                    value="{{ $cartItem->quantity ?? null }}">
                  <button type="button" class="btn btn-sm btn-primary button_plus">+</button>
                </div>

                <input type="hidden" value="{{ $item->id }}" name="id[]"
                  class="input-idcart-{{ $item->id }}">
                <input type="hidden" value="{{ $item->nama }}" name="nama[]"
                  class="input-namacart-{{ $item->id }}">

                @if ($item->gambar ?? null)
                  <input type="hidden" value="{{ asset('storage/item/' . $item->gambar) }}" name="gambar[]"
                    class="input-gambarcart-{{ $item->id }}">
                @else
                  <input type="hidden" value="{{ asset('images/default_produk.png') }}" name="gambar[]"
                    class="input-gambarcart-{{ $item->id }}">
                @endif

                <input type="hidden" value="{{ $harga_sdhdiskon }}" name="harga_satuan[]"
                  class="input-hargasatuancart-{{ $item->id }}">
                <input type="hidden" value="{{ $harga_blmdiskon }}" name="harga_normal[]"
                  class="input-hargasatuancart-{{ $item->id }}">
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </form>

    <div class="modal fade" id="tidakJadiPesanModal" tabindex="-1" aria-labelledby="tidakJadiPesanModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="tidakJadiPesanModalLabel">Keluar Toko</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Alasan penolakan <span class="text-danger">*</span></label>
              <textarea class="form-control" name="alasan_penolakan">{{ old('alasan_penolakan') }}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-danger" type="button" disabled>Keluar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
