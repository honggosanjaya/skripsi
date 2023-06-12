@extends('layouts.mainreact')


@push('JS')
  <script>
    $('.button_min').on('click', function() {
      let oldVal = $(this).parents('.addtocart_action').find('input[name="quantity"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      if ((parseInt(oldVal) - 1) > 0) {
        $(this).parents('.addtocart_action').find('input[name="quantity"]').val(parseInt(oldVal) - 1);
      } else {
        $(this).parents('.addtocart_action').find('input[name="quantity"]').val(0);
      }
    })

    $('.button_plus').on('click', function() {
      let oldVal = $(this).parents('.addtocart_action').find('input[name="quantity"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      $(this).parents('.addtocart_action').find('input[name="quantity"]').val(parseInt(oldVal) + 1);
    })
  </script>
  <script>
    function showRincian(item) {
      return `<div class="info-product_retur mt-2">
                  <div class="d-flex align-items-center border-bottom">
                    <img src=${item.attributes.gambar} class="img-fluid item_image me-3" />
                    <div>
                      <h2 class='fs-6 mb-0 fw-bold'>${item.name}</h2>
                      <h5 class='mb-0'>${item.quantity} barang</h5>
                    </div>
                  </div>
                  <span class='title'>Harga Satuan</span><span class='desc'>${item.price}</span>
                  <span class='title'>Alasan</span><span class='desc'>${item.attributes.alasan}</span>
                </div>`;
    }

    function retur() {
      const iditem = $(this).parents('.list_history-item').attr("data-iditem");
      const alasan = $(this).parents('.list_history-item').find('input[name="alasan_retur"]').val();
      const quantity = $(this).parents('.list_history-item').find('input[name="quantity"]').val();
      const harga_satuan = $(this).parents('.list_history-item').find('input[name="harga_satuan"]').val();
      const nama_item = $(this).parents('.list_history-item').find('input[name="nama_item"]').val();
      const gambar = $(this).parents('.list_history-item').find('input[name="gambar"]').val();
      if (quantity != '' && alasan != '') {
        $('.loader').removeClass('d-none');
        $.ajax({
          url: window.location.origin + `/api/lapangan/retur/cart`,
          method: "POST",
          data: {
            id_item: iditem,
            kuantitas: quantity,
            harga_satuan: harga_satuan,
            alasan: alasan,
            nama_item: nama_item,
            gambar: gambar,
          },
          success: function(response) {
            console.log(response);
            $('.loader').addClass('d-none');
            $('.rincian_retur').removeClass('d-none');
            if (response.status == 'success') {
              let rincians = "";
              response.data.forEach((item) => {
                rincians += showRincian(item);
              });
              $(".rincian_wrapper").html(rincians);
            }
          },
        });
      }
    }

    $(document).off().on('change', 'input[name="alasan_retur"]', retur);
    $(document).on('change', 'input[name="quantity"]', retur);
    $(document).on('click', '.button_plus', retur);
    $(document).on('click', '.button_min', retur);
  </script>

  <script>
    $(document).on('click', '.btn_hapus_keranjang', function() {
      Swal.fire({
        title: 'Apakah anda yakin untuk menghapus keranjang ?',
        showDenyButton: true,
        confirmButtonText: 'Ya',
        denyButtonText: `Tidak`,
      }).then((result) => {
        if (result.isConfirmed) {
          $('.loader').removeClass('d-none');
          $.ajax({
            url: window.location.origin + `/api/lapangan/retur/clearcart`,
            method: "get",
            success: function(response) {
              if (response.status == 'success') {
                $('.loader').addClass('d-none');
                $('input[name="quantity"]').val('');
                $('input[name="alasan_retur"]').val('');
                $('.rincian_wrapper').empty();
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil',
                  text: response.message,
                  showConfirmButton: false,
                  timer: 1500,
                });
              }
            },
          });
        }
      })
    })

    $(document).on('click', '.btn_ajukan_retur', function() {
      $('.loader').removeClass('d-none');
      $.ajax({
        url: window.location.origin + `/api/shipper/retur`,
        method: "POST",
        data: {
          id_customer: "{{ $customer->id }}",
          id_invoice: $('input[name="id_invoice"]').val(),
        },
        success: function(response) {
          console.log(response);
          if (response.status == 'success') {
            $('.loader').addClass('d-none');
            Swal.fire({
              title: 'Success',
              text: response.message,
              showDenyButton: false,
              showCancelButton: false,
              confirmButtonText: 'OK',
              icon: 'success',
            }).then((result) => {
              if (result.isConfirmed) {
                if ($('input[name="id_invoice"]').val() != '') {
                  let redirect = "{{ env('MIX_APP_URL') }}" + '/lapangan/jadwal';
                  window.location.replace(redirect);
                } else {
                  let redirect = "{{ env('MIX_APP_URL') }}" + '/salesman/trip/' + "{{ $customer->id }}";
                  window.location.replace(redirect);
                }
              }
            })
          }
        },
      });
    })
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-3">
    <div class="loader d-none"></div>
    <div class="d-flex justify-content-end">
      <button class="btn btn-danger btn-sm btn_hapus_keranjang me-4">
        <span class="iconify fs-3 me-2" data-icon="bi:trash"></span>Hapus Keranjang
      </button>
      <button class="btn btn-success btn-sm btn_ajukan_retur">
        <span class="iconify fs-3 me-1" data-icon="ic:baseline-assignment-return"></span>Ajukan Retur
      </button>
    </div>

    @if ($id_invoice ?? null)
      <input type="hidden" name="id_invoice" value="{{ $id_invoice }}">
    @else
      <input type="hidden" name="id_invoice">
    @endif


    <div class="retur-product_wrapper my-3 border">
      @foreach ($history as $item)
        @php
          $cartItem = \Cart::session(auth()->user()->id . 'retur')->get($item->linkItem->id);
        @endphp
        <div class="list_history-item p-2" data-iditem="{{ $item->linkItem->id }}">
          <input type="hidden" value="{{ $item->linkItem->nama }}" name="nama_item">
          <div class="d-flex align-items-center">
            @if ($item->linkItem->gambar ?? null)
              <img src="{{ asset('storage/item/' . $item->linkItem->gambar) }}" class="item_image me-3">
              <input type="hidden" name="gambar" value="{{ asset('storage/item/' . $item->linkItem->gambar) }}">
            @else
              <img src="{{ asset('images/default_produk.png') }}" class="item_image me-3">
              <input type="hidden" name="gambar" value="{{ asset('images/default_produk.png') }}">
            @endif
            <div>
              <h2 class='fs-6 text-capitalize fw-bold'>{{ $item->linkItem->nama ?? null }}</h2>
              <p class="mb-0">
                @if ($latestOrderItems[$item->id_item][0] ?? null)
                  Rp. {{ number_format($latestOrderItems[$item->id_item][0]->harga_satuan ?? 0, 0, '', '.') }}
                  <input type="hidden" name="harga_satuan"
                    value="{{ $latestOrderItems[$item->id_item][0]->harga_satuan ?? 0 }}">
                @else
                  @php
                    if ($customer->tipe_harga == 2 && ($item->linkItem->harga2_satuan ?? null)) {
                        $harga_blmdiskon = $item->linkItem->harga2_satuan;
                    } elseif ($customer->tipe_harga == 3 && ($item->linkItem->harga3_satuan ?? null)) {
                        $harga_blmdiskon = $item->linkItem->harga3_satuan;
                    } else {
                        $harga_blmdiskon = $item->linkItem->harga1_satuan;
                    }
                    $harga_sdhdiskon = $harga_blmdiskon - (($customer->linkCustomerType->diskon ?? 0) * $harga_blmdiskon) / 100;
                  @endphp
                  Rp. {{ number_format($harga_sdhdiskon ?? 0, 0, '', '.') }}
                  <input type="hidden" name="harga_satuan" value="{{ $harga_sdhdiskon ?? 0 }}">
                @endif
              </p>
            </div>
          </div>

          <div class="row mt-2 align-items-center addtocart_action">
            <div class="col-5">
              <label class="form-label mb-0">Jumlah Retur</label>
            </div>
            <div class="col-7 d-flex justify-content-around">
              <button class="btn btn-primary button_min btn-sm"> - </button>
              <input type="number" class="form-control mx-2" name="quantity" value="{{ $cartItem->quantity ?? null }}">
              <button class="btn btn-primary button_plus btn-sm"> + </button>
            </div>
          </div>

          <label class="form-label">Alasan Retur</label>
          <input type="text" class="form-control" name="alasan_retur"
            value="{{ $cartItem->attributes->alasan ?? null }}">
        </div>
      @endforeach
    </div>

    <hr class="my-4">
    <div class="rincian_retur {{ $cartItems->count() > 0 ? '' : 'd-none' }}">
      <h6 class="fs-6 fw-bold">Rincian Retur</h6>
      <div class="rincian_wrapper">
        @foreach ($cartItems as $item)
          <div class="info-product_retur mt-2">
            <div class="d-flex align-items-center border-bottom">
              <img src={{ $item->attributes->gambar ?? null }} class="img-fluid item_image me-3" />
              <div>
                <h2 class='fs-6 mb-0 fw-bold'>{{ $item->name ?? null }}</h2>
                <h5 class='mb-0'>{{ $item->quantity ?? null }} barang</h5>
              </div>
            </div>
            <span class='title'>Harga Satuan</span><span class='desc'>{{ $item->price ?? null }}</span>
            <span class='title'>Alasan</span><span class='desc'>{{ $item->attributes->alasan ?? null }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
