@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Event</h1>
    </div>
  </header>
@endsection

@section('content')
  <div class="container pt-4">
    <form method="GET" action="/customer/event/cari">
      <div class="input-group">
        <input type="text" class="form-control" name="cari" placeholder="Cari Event..."
          value="{{ request('cari') }}">
        <button type="submit" class="btn btn-primary">
          <span class="iconify fs-3" data-icon="ant-design:search-outlined"></span>
        </button>
      </div>
    </form>
    @foreach ($events as $event)
      <div class="event_card">
        <h1 class="fs-5 fw-bold">{{ $event->nama }}</h1>
        @if ($event->diskon != null)
          <p class="mb-0 fs-7">Promo Diskon {{ $event->diskon }} %</p>
        @else
          <p class="mb-0 fs-7">Promo Potongan Rp. {{ number_format($event->potongan, 0, '', '.') }}</p>
        @endif
        <p class="mb-0 fs-7">Minimum Pembelian: {{ number_format($event->min_pembelian, 0, '', '.') }}</p>
        <p class="mb-0 fs-7">Berlaku Hingga: {{ date('d F Y', strtotime($event->date_end)) }}</p>
        <p class="mb-0 fs-7 text-primary text-end cursor_pointer" data-bs-toggle="modal"
          data-bs-target="#event{{ $event->id }}">
          Lihat Detail
        </p>

        <!-- Modal -->
        <div class="modal fade" id="event{{ $event->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $event->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                @if ($event->gambar)
                  <img src="{{ asset('storage/event/' . $event->gambar) }}" class="img-fluid mb-4">
                @endif
                @if ($event->diskon != null)
                  <h3 class="fs-6 fw-bold">Promo Diskon {{ $event->diskon }} %</h3>
                @else
                  <h3 class="fs-6 fw-bold">Promo Potongan Rp {{ $event->potongan }}</h3>
                @endif
                <h3 class="fs-6 fw-bold mb-0">Keterangan</h3>
                <p>{{ $event->keterangan }}</p>
                <h3 class="fs-6 fw-bold"> Minimum Pembelian Rp. {{ number_format($event->min_pembelian, 0, '', '.') }}
                </h3>
                <h3 class="fs-6 fw-bold"> Mulai {{ date('d F Y', strtotime($event->date_start)) }} Hingga
                  {{ date('d F Y', strtotime($event->date_end)) }}</h3>
                <small class="text-danger d-block text-center"> NB: Tanyakan pada sales untuk informasi lebih
                  lanjut</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{ $events->links() }}
@endsection
