@extends('layouts.mainmobile')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif

  <div class="container pt-4">
    <div class="row justify-content-end mb-4">
      <div class="col d-flex justify-content-end">
        <button type="button" class="btn btn-primary filter-btn"><span class="iconify me-1 fs-3"
            data-icon="material-symbols:filter-alt-outline"></span>Filter</button>
      </div>
    </div>

    @if (count($orders) == 0)
      <p class="text-danger text-center">Tidak ada data</p>
    @else
      @foreach ($orders as $order)
        <div class="list-mobile">
          <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
              <h1 class="fs-5 mb-0 title">{{ $order->linkCustomer->nama ?? null }}</h1>
              @if ($order->created_at ?? null)
                <span class="text-secondary">{{ date('d M Y', strtotime($order->created_at ?? '-')) }}</span>
              @endif
            </div>
            @if ($order->linkOrderTrack->status_enum ?? null)
              <p class="mb-0 badge badge-order-track-{{ $order->linkOrderTrack->status_enum }}">
                @if ($order->linkOrderTrack->status_enum == '0')
                  {{ 'Diajukan Customer' }}
                @elseif ($order->linkOrderTrack->status_enum == '1')
                  {{ 'Diajukan Salesman' }}
                @elseif ($order->linkOrderTrack->status_enum == '2')
                  {{ 'Dikonfirmasi Admin' }}
                @elseif ($order->linkOrderTrack->status_enum == '3')
                  {{ 'Dalam Perjalanan' }}
                @elseif ($order->linkOrderTrack->status_enum == '4')
                  {{ 'Order Telah Sampai' }}
                @elseif ($order->linkOrderTrack->status_enum == '5')
                  {{ 'Pembayaran' }}
                @elseif ($order->linkOrderTrack->status_enum == '6')
                  {{ 'Order Selesai' }}
                @elseif ($order->linkOrderTrack->status_enum == '-1')
                  {{ 'Order Ditolak' }}
                @endif
              </p>
            @endif
          </div>

          <span class="fs-6">Pesanan diperoleh dari <strong>{{ $order->linkStaff->nama ?? null }}</strong></span>
          @if ($order->linkInvoice ?? null)
            <span>dengan total harga sebesar
              <strong>Rp {{ number_format($order->linkInvoice->harga_total ?? 0, 0, '', '.') }}</strong></span>
          @endif

          <div class="action d-flex justify-content-center mt-3">
            <a href="/administrasi/pesanan/detail/{{ $order->id }}" class="btn btn-purple-gradient w-100">
              <span class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Lihat Detail
            </a>
          </div>
        </div>
      @endforeach
    @endif

    <div class="mt-5 d-flex justify-content-center">
      {{ $orders->appends(request()->except('page'))->links() }}
    </div>

    <div class="popup-bottom">
      <div class="row justify-content-end">
        <div class="col d-flex justify-content-end">
          <button class="close-filter-btn btn"><span class="iconify me-1 fs-3 "
              data-icon="ic:baseline-close"></span></button>
        </div>
      </div>

      <div class="d-flex justify-content-between mt-3">
        <h1 class="fs-5">Filter</h1>
        <form action="/administrasi/pesanan" method="get">
          <button type="submit" class="btn btn-danger">Reset</button>
        </form>
      </div>

      <form action="/administrasi/pesanan" method="get">
        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Nama Salesman</label>
              <input type="text" class="form-control" name="nama_salesman"
                value="{{ $input['nama_salesman'] ?? null }}">
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Nama Customer</label>
              <input type="text" class="form-control" name="nama_customer"
                value="{{ $input['nama_customer'] ?? null }}">
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Status Pesanan</label>
              <select class="form-select" name="status_pesanan">
                <option value='default'>Semua Status</option>
                <option {{ -1 == $input['status_pesanan'] ? 'selected' : '' }} value="-1">Ditolak</option>
                <option {{ 0 == $input['status_pesanan'] ? 'selected' : '' }} value="0">Diajukan Customer</option>
                <option {{ 1 == $input['status_pesanan'] ? 'selected' : '' }} value="1">Diajukan Salesman</option>
                <option {{ 2 == $input['status_pesanan'] ? 'selected' : '' }} value="2">Dikonfirmasi</option>
                <option {{ 3 == $input['status_pesanan'] ? 'selected' : '' }} value="3">Dalam Perjalanan</option>
                <option {{ 4 == $input['status_pesanan'] ? 'selected' : '' }} value="4">Telah Sampai</option>
                <option {{ 5 == $input['status_pesanan'] ? 'selected' : '' }} value="5">Pembayaran</option>
                <option {{ 6 == $input['status_pesanan'] ? 'selected' : '' }} value="6">Selesai</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <hr class="my-4">
            <h1 class="fs-5">Urutkan</h1>
            <label class="form-label">Urutkan Berdasar</label>
            <div class="d-flex flex-wrap">
              @if ($input['filter'] ?? null)
                @if ($input['filter'] == 'hargarendah')
                  <input type="radio" class="btn-check" id="harga-terendah" autocomplete="off"
                    name="filter"value="hargarendah" checked>
                @else
                  <input type="radio" class="btn-check" id="harga-terendah" autocomplete="off"
                    name="filter"value="hargarendah">
                @endif
              @else
                <input type="radio" class="btn-check" id="harga-terendah" autocomplete="off"
                  name="filter"value="hargarendah">
              @endif
              <label class="btn btn-outline-primary me-3 mb-3" for="harga-terendah">Harga Total Terendah</label>

              @if ($input['filter'] ?? null)
                @if ($input['filter'] == 'hargatinggi')
                  <input type="radio" class="btn-check" id="harga-tertinggi" autocomplete="off" name="filter"
                    value="hargatinggi" checked>
                @else
                  <input type="radio" class="btn-check" id="harga-tertinggi" autocomplete="off" name="filter"
                    value="hargatinggi">
                @endif
              @else
                <input type="radio" class="btn-check" id="harga-tertinggi" autocomplete="off" name="filter"
                  value="hargatinggi">
              @endif
              <label class="btn btn-outline-primary me-3 mb-3" for="harga-tertinggi">Harga Total Tertinggi</label>

              @if ($input['filter'] ?? null)
                @if ($input['filter'] == 'terlama')
                  <input type="radio" class="btn-check" id="terlama" autocomplete="off" name="filter"
                    value="terlama" checked>
                @else
                  <input type="radio" class="btn-check" id="terlama" autocomplete="off" name="filter"
                    value="terlama">
                @endif
              @else
                <input type="radio" class="btn-check" id="terlama" autocomplete="off" name="filter"
                  value="terlama">
              @endif
              <label class="btn btn-outline-primary me-3 mb-3" for="terlama">Dibuat Terlama</label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <button type="submit" class="btn btn-purple-gradient w-100 mt-4"><span class="iconify me-1 fs-3"
                data-icon="material-symbols:filter-alt-outline"></span>Filter</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
    <script>
      $(document).on('click', '.filter-btn', function(e) {
        $(".popup-bottom").addClass('show-up');
        $('.main-mobile').addClass('card-overlay');
      });

      $(document).on('click', '.close-filter-btn', function(e) {
        $(".popup-bottom").removeClass('show-up');
        $('.main-mobile').removeClass('card-overlay');
      });
    </script>
  @endpush
@endsection
