@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <h1 class="page_title logo">salesMan</h1>
    @if ($customer->foto != null)
      <img class="profil_picture" src="{{ asset('storage/customer/' . $customer->foto) }}">
    @else
      <img class="profil_picture" src="{{ asset('images/default_fotoprofil.png') }}">
    @endif
  </header>
@endsection

@section('content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds" class="mt-4">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif
  <div id="horizontal_scroll" class="mt-4">
    <div class="card_event-wrapper d-flex">
      @foreach ($events as $event)
        <div class="card_event border">
          {{-- <img src="{{ asset('storage/event/' . $event->gambar) }}" alt=""> --}}
          <h1 class="text-wrap">{{ $event->nama }}</h1>
          <small class="text-wrap">Dapatkan {{ $event->diskon ? 'diskon' : 'potongan' }} sebesar
            {{ $event->diskon ? $event->diskon . ' %' : 'Rp. ' . $event->potongan }}</small>
          <small><span class="fw-bold">Berlaku Hingga </span>{{ date('d F Y', strtotime($event->date_end)) }}</small>
        </div>
      @endforeach
    </div>
  </div>

  <div class="kode_pesanan">
    <h1 class="fs-5 fw-bold">Kode Pesanan</h1>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Kode</th>
          <th scope="col" class="text-center">Aksi</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($kode_customers as $kode)
          <tr>
            <th scope="row" class="text-center align-middle">{{ $kode->id }}</th>
            <td class="align-middle">
              <div class="d-flex">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                  data-bs-target="#kode{{ $kode->id }}">
                  <span class="iconify fs-4" data-icon="fluent:apps-list-detail-24-filled"></span>
                </button>
                @if ($kode->linkOrderTrack->status_enum == '0')
                  <form action="/customer/historyorder/hapus/{{ $kode->id }}" method="POST" class="handleHapusKode">
                    @csrf
                    <button class="btn btn-danger ms-2 hapus_btn">
                      <span class="iconify fs-4" data-icon="fluent:delete-dismiss-24-filled"></span>
                    </button>
                  </form>
                @endif
              </div>
            </td>
            <td class="align-middle">Diajukan Customer</td>

            <!-- Modal -->
            <div class="modal fade" id="kode{{ $kode->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
              aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Detail Order {{ $kode->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><span class="fw-bold">Kode Pesanan:</span> {{ $kode->id }}</p>

                    <div class="row border-bottom">
                      <div class="col-5">
                        <p class="mb-0 fw-bold text-center">Item</p>
                      </div>
                      <div class="col-3">
                        <p class="mb-0 fw-bold text-center">Kuantitas</p>
                      </div>
                      <div class="col-4">
                        <p class="mb-0 fw-bold text-center">Harga Satuan</p>
                      </div>
                    </div>

                    @foreach ($kode->linkOrderItem as $item)
                      <div class="row align-items-center">
                        <div class="col-5">
                          <p class="mb-0">{{ $item->linkItem->nama }}</p>
                        </div>
                        <div class="col-3">
                          <p class="mb-0 text-center">{{ $item->kuantitas }}</p>
                        </div>
                        <div class="col-4">
                          <p class="mb-0 text-center">
                            {{ $item->harga_satuan - ($item->harga_satuan * $customer->linkCustomerType->diskon) / 100 }}
                          </p>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="history_items">
    <h1 class="fs-5 fw-bold mb-4">History Item</h1>
    <div class="card_wrapper">
      @foreach ($histories as $history)
        <div class="card {{ $history->linkItem->status_enum == '-1' ? 'inactive_product' : '' }}">
          @if ($history->linkItem->status_enum == '-1')
            <div class='inactive_sign'>
              <p class='mb-0'>Tidak Tersedia</p>
            </div>
          @endif

          @if ($history->linkItem->gambar)
            <img src="{{ asset('storage/item/' . $history->linkItem->gambar) }}" class="img-fluid img_card">
          @else
            <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_card">
          @endif
          <div class="card-body p-2">
            <h1 class="fs-6 fw-bold">{{ $history->linkItem->nama }}</h1>
            {{-- <h2 class="fs-6 mb-0">
              Rp. {{ number_format($history->linkItem->harga_satuan, 0, '', '.') }} /
              {{ $history->linkItem->satuan }}
            </h2> --}}
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <input type="hidden" name="loginPassword" value="{{ session('password') }}">
  <input type="hidden" name="countt" value="{{ session('count') }}">
@endsection
