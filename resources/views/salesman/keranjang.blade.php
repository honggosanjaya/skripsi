@extends('layouts.mainreact')

@push('CSS')
  <style>
    .button_bottom {
      max-height: auto !important;
    }
  </style>
@endpush

@push('JS')
  <script>
    function convertPrice(price) {
      let convertedPrice = 'Rp. ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return convertedPrice;
    }

    $('.delete_all_cart').on('click', function() {
      $.ajax({
        url: window.location.origin + `/api/salesman/removeallcart`,
        method: "get",
        success: function(response) {
          if (response.status == 'success') {
            $('.page_container').empty();
            $('.page_container').html(
              `<small class='text-danger text-center d-block mt-5'>Keranjang Kosong</small>`);
            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message,
              showConfirmButton: false,
              timer: 1500,
            });
          }
        }
      })
    })

    $('.button_min').on('click', function(e) {
      let idItem = e.target.value;
      let oldVal = $(this).parents('.action_button').find('input[name="quantity"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      if ((parseInt(oldVal) - 1) > 1) {
        $(this).parents('.action_button').find('input[name="quantity"]').val(parseInt(oldVal) - 1);
        $(this).removeAttr('disabled');
        $.ajax({
          url: window.location.origin + `/api/salesman/changecartitems`,
          method: "post",
          data: {
            'id': idItem,
            'iduser': "{{ auth()->user()->id }}",
            'quantity': parseInt(oldVal) - 1
          },
          success: function(response) {
            console.log(response);
            let oldtotalpesanan = parseInt($('input[name="total_pesanan"]').val());
            oldtotalpesanan += parseInt(response.perubahan);
            $('input[name="total_pesanan"]').val(oldtotalpesanan);
            $('.total_pesanan').text(convertPrice(oldtotalpesanan));
          }
        })
      } else {
        $(this).parents('.action_button').find('input[name="quantity"]').val(1);
        $(this).attr("disabled", true);
      }
    })

    $('.button_plus').on('click', function(e) {
      let idItem = e.target.value;
      let oldVal = $(this).parents('.action_button').find('input[name="quantity"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      $(this).parents('.action_button').find('input[name="quantity"]').val(parseInt(oldVal) + 1);
      $.ajax({
        url: window.location.origin + `/api/salesman/changecartitems`,
        method: "post",
        data: {
          'id': idItem,
          'iduser': "{{ auth()->user()->id }}",
          'quantity': parseInt(oldVal) + 1
        },
        success: function(response) {
          console.log(response);
          let oldtotalpesanan = parseInt($('input[name="total_pesanan"]').val());
          oldtotalpesanan += parseInt(response.perubahan);
          $('input[name="total_pesanan"]').val(oldtotalpesanan);
          $('.total_pesanan').text(convertPrice(oldtotalpesanan));
        }
      })
      $(this).parents('.action_button').find('.button_min').removeAttr('disabled');
    })

    $('input[name="check_item"]').on('change', function(e) {
      if (!$(this).is(":checked")) {
        $(this).parents('.cart_item').find('.grid_ornot').removeClass('grid_item');
        $(this).parents('.cart_item').find('.elipsis_ornot').removeClass('elipsis');
        $(this).parents('.cart_item').find('.btn_deleteItem').addClass('d-none');
      } else {
        $(this).parents('.cart_item').find('.grid_ornot').addClass('grid_item');
        $(this).parents('.cart_item').find('.elipsis_ornot').addClass('elipsis');
        $(this).parents('.cart_item').find('.btn_deleteItem').removeClass('d-none');
      }
    })

    $('.btn_deleteItem').on('click', function(e) {
      const idItem = e.target.value;
      $.ajax({
        url: window.location.origin + `/api/salesman/removecartitem/${idItem}`,
        method: "get",
        success: function(response) {
          console.log(response);
          $(`.cart_item[data-iditem=${idItem}]`).remove();
          if ($('.cart_item').length() == 0) {
            $('.page_container').empty();
            $('.page_container').html(
              `<small class='text-danger text-center d-block mt-5'>Keranjang Kosong</small>`);
          }
        }
      })
    });

    $('input[name="estimasi_pengiriman"]').on('change', function() {
      if ($(this).val) {
        $('.btn_rincian_pesanan').removeAttr('disabled');
      } else {
        $('.btn_rincian_pesanan').attr("disabled", true);
      }
    });

    $('.btn_pakai_event').on('click', function() {
      const kodeEvent = $('input[name="kode_event"]').val();
      const totPesanan = parseInt($('input[name="total_pesanan"]').val());
      $.ajax({
        url: window.location.origin + `/api/salesman/event/${kodeEvent}`,
        method: "post",
        data: {
          'total_pesanan': totPesanan
        },
        success: function(response) {
          console.log(response);
          if (response.status == 'success') {
            $('.success_kode_event').text(response.message);
            let oldtotalpesanan = parseInt($('input[name="total_pesanan"]').val());
            oldtotalpesanan -= parseInt(response.potongan);
            $('input[name="total_pesanan"]').val(oldtotalpesanan);
            $('.total_pesanan').addClass('text-decoration-line-through');
            $('.total_pesanan_diskon').removeClass('d-none').text(convertPrice(oldtotalpesanan));
            $('.diskon_event').text('- ' + response.potongan);
          } else if (response.status == 'error') {
            $('.error_kode_event').text(response.message);
          }
        }
      })
    });
  </script>

  <script>
    function showDetail(product) {
      return `<tr>
                <td>${product.name}</td>
                <td>${product.quantity} x ${product.attributes.harga_normal}</td>
                <td class='text-end'>${product.quantity * product.attributes.harga_normal}</td>
              </tr>`;
    }

    $('.btn_rincian_pesanan').on('click', function(e) {
      $.ajax({
        url: window.location.origin + `/api/salesman/getcartitem`,
        method: "get",
        success: function(response) {
          let totalHargaNormal = 0;
          let totalHargaDiskon = 0;

          let produks = "";
          response.data.forEach((produk) => {
            produks += showDetail(produk);
            totalHargaDiskon += (parseInt(produk.price) * produk.quantity);
            totalHargaNormal += (parseInt(produk.attributes.harga_normal) * produk.quantity);
          });
          $(".tbody_rincian_pesanan").html(produks);
          $('.subtotal_pesanan').text(totalHargaNormal);
          $('.diskon_customer').text('- ' + (totalHargaNormal - totalHargaDiskon));
          $('.total_akhir_pesanan').text($('input[name="total_pesanan"]').val());
        }
      })
    });
  </script>

  <script>
    function buatOrder() {
      $('.loader').removeClass('d-none');
      $.ajax({
        url: window.location.origin + `/api/salesman/getcartitem`,
        method: "get",
        success: function(response) {
          if (response.status == 'success') {
            let obj = {
              keranjang: response.data,
              idCustomer: "{{ $customer->id }}",
              estimasiWaktuPengiriman: $('input[name="estimasi_pengiriman"]').val(),
              jatuhTempo: $('input[name="jatuh_tempo"]').val(),
              keterangan: $('textarea[name="keterangan_pesanan"]').val(),
              kodeEvent: $('input[name="kode_event"]').val(),
              totalHarga: $('input[name="total_pesanan"]').val(),
              idTrip: $('input[name="id_trip"]').val(),
              tipeRetur: "{{ $customer->tipe_retur }}",
              metode_pembayaran: $('select[name="metode_pembayaran"]').val(),
              stok_kanvas: $('input[name="from_kanvas"]').val(),
              koordinat: $('input[name="koordinat"]').val(),
              kodePesanan: $('input[name="kodePesanan"]').val(),
              limit_pembelian: $('input[name="limit_pembelian"]').val()
            }
            // console.log(obj);
            $.ajax({
              url: window.location.origin + `/api/salesman/buatOrder`,
              method: "post",
              data: obj,
              success: function(response) {
                console.log(response);
                if (response.status == 'success') {
                  $('.loader').addClass('d-none');
                  Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan!',
                    text: response.success_message,
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#7066e0',
                    confirmButtonText: 'Belanja Lagi',
                    cancelButtonText: 'Selesai'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      const id_trip = $('input[name="id_trip"]').val();
                      $.ajax({
                        url: window.location.origin + `/api/belanjalagi/${id_trip}`,
                        method: "get",
                        success: function(response) {
                          let redirect = "{{ env('MIX_APP_URL') }}" +
                            `/salesman/order/${response.data.customer.id}?isBelanjaLagi=true`;
                          window.location.replace(redirect);
                        }
                      })
                    } else {
                      let redirect = "{{ env('MIX_APP_URL') }}" + '/salesman';
                      window.location.replace(redirect);
                    }
                  })
                }
              }
            })
          }
        }
      })
    }

    $('.btn_checkout').on('click', function() {
      Swal.fire({
        title: 'Apakah anda yakin?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Checkout!'
      }).then((result) => {
        if (result.isConfirmed) {
          const limitPembelian = $('input[name="limit_pembelian"]').val();
          if ($('input[name="total_pesanan"]').val() > limitPembelian) {
            Swal.fire({
              icon: 'error',
              title: 'Checkout Error',
              text: 'total pesanan melibihi limit',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Tetap Lanjutkan',
              cancelButtonText: 'Batal'
            }).then((result) => {
              if (result.isConfirmed) {
                buatOrder();
              }
            })
          } else {
            buatOrder();
          }
        }
      })
    })
  </script>

  <script>
    $('input[name="from_kanvas"]').on('change', function(e) {
      console.log(e.target.value);
    });
  </script>
@endpush

@section('main_content')
  <input type="text" value="{{ $idTrip }}" name="id_trip">
  <input type="text" value="{{ $koordinat ?? '0@0' }}" name="koordinat">
  <input type="text" value="{{ $kodePesanan ?? null }}" name="kodePesanan">

  <div class="loader d-none"></div>
  <div class="alert alert-info mb-0" role="alert">
    <p class="mb-0 text-center"><b>Limit Pembelian :</b>
      {{ $customer->limit_pembelian ? 'Rp. ' . number_format($customer->limit_pembelian, 0, '', '.') : 'Tanpa Limit' }}
    </p>
    <input type="hidden" value="{{ $customer->limit_pembelian ?? null }}" name="limit_pembelian">
  </div>
  <div class="page_container pt-4">
    @if (count($cartItems) > 0)
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="fs-5 fw-bold">Keranjang Customer</h1>
        <button class='btn btn-danger delete_all_cart'>Hapus Semua</button>
      </div>

      @foreach ($cartItems as $item)
        <div class="cart_item" data-iditem={{ $item->id }}>
          <div class="d-flex">
            <div class="form-check pl-0">
              <label class="customCheckbox_wrapper">
                <input type="checkbox" class="form-check-input custom_checkbox" name="check_item"
                  value="{{ $item->id }}">
                <span class="custom_checkbox"></span>
              </label>
            </div>
            <img src="{{ $item->attributes->gambar }}" class="item_cartimage">
          </div>

          <div class="grid_ornot">
            <div class="detail_item">
              <h2 class="mb-0 fs-6 fw-bold elipsis_ornot">{{ $item->name }}</h2>
              <p class='mb-0 d-inline'>Rp. {{ number_format($item->price, 0, '', '.') }}</p>
              <div class="d-flex my-2 action_button">
                <button class="btn btn-sm btn-primary button_min" value="{{ $item->id }}"
                  {{ $item->quantity == 1 ? 'disabled' : '' }}>-</button>
                <input type="number" class="text-center" style="width: 4ch;" name="quantity"
                  value="{{ $item->quantity }}" disabled>
                <button class="btn btn-sm btn-primary button_plus" value="{{ $item->id }}">+</button>
              </div>
            </div>

            <button class="btn btn-danger btn_deleteItem d-none" value="{{ $item->id }}">
              <span class="iconify " data-icon="bxs:trash"></span>
            </button>
          </div>
        </div>
      @endforeach

      <div class="mb-btnBottom">
        <div class="form-check form-switch mt-3">
          <input class="form-check-input" type="checkbox" name="from_kanvas">
          <label class="form-label">Ambil dari stok kanvas</label>
        </div>

        <label class="form-label">Keterangan Pesanan</label>
        <textarea class="form-control" name="keterangan_pesanan"></textarea>

        <label class="form-label mt-3">Estimasi Waktu Pengiriman <span class='text-danger'>*</span></label>
        <div class="input-group">
          <input type="number" class="form-control" name="estimasi_pengiriman">
          <div class="border p-2 d-flex justify-content-center align-items-center rounded-end">Hari</div>
        </div>

        <label class="form-label mt-3">Jatuh Tempo<span class='text-danger'>*</span></label>
        <div class="input-group">
          <input type="number" class="form-control" name="jatuh_tempo" value="{{ $customer->jatuh_tempo ?? 7 }}">
          <div class="border p-2 d-flex justify-content-center align-items-center rounded-end">Hari</div>
        </div>

        <label class="form-label mt-3">Kode Event</label>
        <div class="input-group">
          <input type="text" class="form-control" name="kode_event">
          <button class="btn btn-success btn_pakai_event">Gunakan</button>
        </div>
        <small class='text-danger d-block error_kode_event'></small>
        <small class='text-success d-block success_kode_event'></small>

        <label class="form-label mt-3">Tipe Retur</label>
        <select class="form-select" name="tipe_retur">
          <option value="1">Potongan</option>
          <option value="2">Tukar Guling</option>
        </select>

        <label class="form-label mt-3">Metode Pembayaran</label>
        <select class="form-select mb-3" name="metode_pembayaran">
          <option value="1">Tunai</option>
          <option value="2">Giro</option>
          <option value="3">Transfer</option>
        </select>

        <div class="button_bottom d-flex justify-content-between align-items-center">
          <div>
            <p class='mb-0'>Total Pesanan:</p>
            <input type="hidden" name="total_pesanan"
              value="{{ \Cart::session(auth()->user()->id . 'salesman')->getSubTotal() }}">
            <h1 class='mb-0 fs-4 total_pesanan'>Rp.
              {{ number_format(\Cart::session(auth()->user()->id . 'salesman')->getSubTotal() ?? 0, 0, '', '.') }}
            </h1>
            <h1 class='mb-0 fs-4 total_pesanan_diskon d-none'></h1>
          </div>
          <button class='btn btn-success btn_rincian_pesanan' data-bs-toggle="modal" data-bs-target="#exampleModal"
            disabled>
            <span class="iconify me-2" data-icon="uil:invoice"></span> Rincian Pesanan
          </button>
        </div>
      </div>
    @else
      <small class='text-danger text-center d-block mt-5'>Keranjang Kosong</small>
    @endif

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel"><span class="iconify me-2"
                data-icon="uil:invoice"></span>Rincian Pesanan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead>
                <tr>
                  <th class='text-center'>Nama</th>
                  <th class='text-center'>Kuantitas</th>
                  <th class='text-center'>Harga</th>
                </tr>
              </thead>
              <tbody class="tbody_rincian_pesanan">
              </tbody>
            </table>

            <div class="d-flex justify-content-between px-2">
              <p class='mb-0 fw-bold'>Subtotal pesanan</p>
              <p class='mb-0 subtotal_pesanan'></p>
            </div>
            <div class="d-flex justify-content-between px-2">
              <p class='mb-0 fw-bold'>Diskon Customer {{ $customer->linkCustomerType->nama ?? null }}</p>
              <p class='mb-0 diskon_customer'></p>
            </div>
            <div class="d-flex justify-content-between px-2">
              <p class='mb-0 fw-bold'>Event</p>
              <p class='mb-0 diskon_event'>- 0</p>
            </div>
            <hr />
            <div class="d-flex justify-content-between px-2">
              <p class='mb-0 fw-bold'>Total Akhir</p>
              <p class='mb-0 total_akhir_pesanan'></p>
            </div>
            <p class="mb-0 text-center mt-3"><b>Nb:</b>Pesanan ini diambilkan dari stok kanvas gudang</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success btn_checkout">
              <span class="iconify fs-3 me-1" data-icon="ic:baseline-shopping-cart-checkout"></span> CHEKOUT
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
