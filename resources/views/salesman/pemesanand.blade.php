@extends('layouts.mainreact')

@push('CSS')
@endpush

@push('JS')
  <script>
    var idTrip = null;

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
            catatTripOrder(koordinat);
          });
        })
      } else if (result.state === 'granted') {
        navigator.geolocation.getCurrentPosition(function(position) {
          const koordinat = position.coords.latitude + '@' + position.coords.longitude;
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
                'isBelanjaLagi': false,
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
      let oldVal = $(this).parents('.addtocart_action').find('input[name="quantity"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      if ((parseInt(oldVal) - 1) > 0) {
        $(this).parents('.addtocart_action').find('input[name="quantity"]').val(parseInt(oldVal) - 1);
      } else {
        $(this).parents('.addtocart_action').find('input[name="quantity"]').val('');
      }
    })

    $('.button_plus').on('click', function() {
      console.log('ha')
      let oldVal = $(this).parents('.addtocart_action').find('input[name="quantity"]').val();
      if (oldVal == '') {
        oldVal = 0;
      }
      if ($(this).parents('.addtocart_action').attr("data-stokrealtime") >= (parseInt(oldVal) + 1)) {
        $(this).parents('.addtocart_action').find('input[name="quantity"]').val(parseInt(oldVal) + 1);
      }
    })
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

    <div class="history-item mt-4">
      <h1 class='fs-5 fw-bold'>History Item</h1>
      @if (count($history) > 0)
        @foreach ($history as $item)
          @php
            $cartItem = \Cart::session(auth()->user()->id . 'salesman')->get($item->linkItem->id);
            if ($customer->tipe_harga == 2 && ($item->linkItem->harga2_satuan ?? null)) {
                $harga_satuan = $item->linkItem->harga2_satuan;
            } elseif ($customer->tipe_harga == 3 && ($item->linkItem->harga3_satuan ?? null)) {
                $harga_satuan = $item->linkItem->harga3_satuan;
            } else {
                $harga_satuan = $item->linkItem->harga1_satuan;
            }
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
                <p class="mb-0 ms-2">{{ $harga_satuan }} / {{ $item->linkItem->satuan ?? null }}</p>
              </div>

              <p class="mb-0">Max stok : {{ $item->stok_maksimal_customer ?? null }}</p>
              <div class="row">
                <div class="col">
                  <p class="mb-0">Stok left</p>
                </div>
                <div class="col">
                  <input type="number" class="form-control" value="{{ $item->stok_terakhir_customer ?? 0 }}"
                    name="stok_left">
                </div>
                <div class="col">
                  <button type="button" class="btn btn-sm btn-warning">Ubah</button>
                  <button type="button" class="btn btn-sm btn-success">Simpan</button>
                </div>
              </div>
            </div>

            <div class="row">
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
                <input type="number" name="quantity" value="{{ $cartItem->quantity ?? null }}"
                  class="form-control w-50">
                <button type="button" class="btn btn-sm btn-primary button_plus">+</button>
              </div>
            @endif
          </div>
        @endforeach
      @else
        <small class="text-danger text-center d-block">Tidak Ada Riwayat Pembelian</small>
      @endif
    </div>

    <div class="my-5 d-flex justify-content-between align-items-center">
      <h1 class="fs-6 fw-bold"> Customer tidak jadi pesan ?</h1>
      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
        data-bs-target="#tidakJadiPesanModal">Keluar</button>
    </div>

    <div>
      <div class="d-flex justify-content-between mb-3">
        <h1 class='fs-5 mb-0 fw-bold'>Item</h1>
        <button type="button" class='btn'>
          <span class="iconify fs-3" data-icon="ci:filter"></span>
        </button>
      </div>

      {{-- <form class='mb-3'> --}}
      <div class="input-group">
        <input type="text" class="form-control" name="cari_produk" placeholder="Cari Produk...">
        <button type="button" class="btn btn-primary">Cari</button>
      </div>
      {{-- </form> --}}

      <div class="productCard_wrapper">
        @foreach ($items as $item)
          @php
            $cartItem = \Cart::session(auth()->user()->id . 'salesman')->get($item->id);
          @endphp

          <div class="card product_card" data-iditem="{{ $item->id }}">
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
                @php
                  if ($customer->tipe_harga == 2) {
                      $harga_blmdiskon = $item->harga2_satuan;
                  } elseif ($customer->tipe_harga == 3) {
                      $harga_blmdiskon = $item->harga3_satuan;
                  } else {
                      $harga_blmdiskon = $item->harga1_satuan;
                  }
                  $harga_sdhdiskon = $harga_blmdiskon - (($customer->linkCustomerType->diskon ?? 0) * $harga_blmdiskon) / 100;
                @endphp
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
                <input type="number" class="form-control" name="quantity" value="{{ $cartItem->quantity ?? null }}">
                <button type="button" class="btn btn-sm btn-primary button_plus">+</button>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

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
