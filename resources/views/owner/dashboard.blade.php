@extends('layouts/main')

@section('main_content')
  @if (auth()->user()->linkStaff->linkStaffRole->nama == 'supervisor')
    <div class="limit_notif m-fadeOut p-3">
      @foreach ($customersPengajuanLimit as $customerPengajuanLimit)
        <div class="card_notif">
          <a href="/supervisor/datacustomer/pengajuan/{{ $customerPengajuanLimit->id }}"
            class="text-black text-decoration-none">
            <p class="mb-0 fw-bold">Pengajuan Limit Pembelian</p>
            <p class="mb-0">Pengajuan limit pembeian dari {{ $customerPengajuanLimit->nama }} </p>
          </a>
        </div>
      @endforeach
    </div>
  @endif

  <div id="report" class="px-2 px-md-5 pt-4">
    <form action="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}/" method="get">
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tahun</span>
            <input type="text" class="form-control" placeholder="2023" name="year"
              value="{{ $input['year'] ?? null }}">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <select class="form-select" aria-label="Default select example" name="month">
            <option {{ 1 == $input['month'] ? 'selected' : '' }} value="1">Januari</option>
            <option {{ 2 == $input['month'] ? 'selected' : '' }} value="2">Febuari</option>
            <option {{ 3 == $input['month'] ? 'selected' : '' }} value="3">Maret</option>
            <option {{ 4 == $input['month'] ? 'selected' : '' }} value="4">April</option>
            <option {{ 5 == $input['month'] ? 'selected' : '' }} value="5">Mei</option>
            <option {{ 6 == $input['month'] ? 'selected' : '' }} value="6">Juni</option>
            <option {{ 7 == $input['month'] ? 'selected' : '' }} value="7">Juli</option>
            <option {{ 8 == $input['month'] ? 'selected' : '' }} value="8">Agustus</option>
            <option {{ 9 == $input['month'] ? 'selected' : '' }} value="9">September</option>
            <option {{ 10 == $input['month'] ? 'selected' : '' }} value="10">Oktober</option>
            <option {{ 11 == $input['month'] ? 'selected' : '' }} value="11">November</option>
            <option {{ 12 == $input['month'] ? 'selected' : '' }} value="12">Desember</option>
          </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">slow item</span>
            <input type="number" class="form-control" placeholder="2" name="count"
              value="{{ $input['count'] ?? 5 }}">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3 mt-3 mt-sm-0">
            <label class="form-label">Date Start</label>
            <input type="date" name="dateStart" class="form-control" value="{{ $input['dateStart'] ?? null }}"
              id="dateStart">
          </div>
        </div>
        <div class="col">
          <div class="mb-3 mt-3 mt-sm-0">
            <label class="form-label">Date End</label>
            <input type="date" name="dateEnd" class="form-control" value="{{ $input['dateEnd'] ?? null }}"
              id="dateEnd">
          </div>
        </div>
      </div>

      <div class="row justify-content-end">
        <div class="col d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"><span class="iconify fs-3 me-1"
              data-icon="clarity:filter-grid-solid"></span>Filter Data</button>
        </div>
      </div>
    </form>
    @php
      $produk_laris['item_name'] = [];
      $produk_laris['item_total'] = [];
    @endphp
    @foreach ($data['produk_laris'] as $item)
      @php
        array_push($produk_laris['item_name'], $item->linkItem->nama);
        array_push($produk_laris['item_total'], $item->total);
      @endphp
    @endforeach

    <div class="row mt-4">
      <div class="col-12 col-lg-7">
        <div class="graph">
          <canvas id="kinerjaSalesChart" data-label="{{ json_encode($produk_laris['item_name']) }}"
            data-value="{{ json_encode($produk_laris['item_total']) }}">
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-5">
        <div class="data-list">
          <h1 class="fs-5 fw-bold mb-2">Total Omzet</h1>
          <span>Rp. {{ number_format($data['omzet']->total, 0, '', '.') }}</span>
          <h1 class="fs-5 fw-bold mb-2 mt-4">Total Pengeluaran</h1>
          <span>Rp. {{ number_format($data['pembelian']->total, 0, '', '.') }}</span>
          <h1 class="fs-5 fw-bold mb-2 mt-4">Total Untung/Rugi</h1>
          <span>Rp. {{ number_format($data['omzet']->total - $data['pembelian']->total, 0, '', '.') }}</span>
          {{-- <button class="btn btn-primary d-block mx-auto mt-4">View Detail</button> --}}
        </div>
      </div>

      <div class="col-12 col-sm-6">
        <div class="data-list">
          <h1 class="fs-5 fw-bold mb-3">Produk Slow Moving</h1>
          <div class="sales-container">
            @foreach ($data['produk_slow'] as $item)
              <div class="sales row">
                <h3 class="fs-6 col-4 text-capitalize">{{ $item->linkItem->nama }} </h3>
                <h3 class="fs-6 col-4">{{ $item->count }} kali trasaksi</h3>
                <h3 class="fs-6 col-4">{{ $item->total }} item terjual</h3>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6">
        <div class="data-list">
          <h1 class="fs-5 fw-bold mb-3">Produk Tidak Terjual</h1>
          <div class="sales-container">
            @foreach ($data['produk_tidak_terjual'] as $item)
              <h3 class="fs-6 fw-normal">{{ $loop->iteration }}. {{ $item->nama }}</h3>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const dropdownLimit = document.querySelector(".alert_limit");
    const notifLimit = document.querySelector(".limit_notif");

    dropdownLimit.addEventListener("click", function() {
      dropdownLimit.classList.toggle('active');
      notifLimit.classList.toggle("m-fadeIn");
      notifLimit.classList.toggle("m-fadeOut");
    });
  </script>

  @push('JS')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ mix('js/report.js') }}"></script>
  @endpush
@endsection
