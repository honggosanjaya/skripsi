@extends('layouts/main')

@section('main_content')
  @if (auth()->user()->linkStaff->linkStaffRole->nama == 'supervisor')
    <div class="all-notification m-fadeOut p-3">
      <div id="horizontal_scroll" class="mb-3">
        <button class="btn btn-outline-primary filter-notif active" data-notif="limit">Limit Pembelian
          ({{ $datadua['jml_pengajuan'] }})</button>
        <button class="btn btn-outline-primary filter-notif" data-notif="opname">Stok Opname
          ({{ $datadua['juml_opname'] }})</button>
      </div>

      <div class="limit_notif notif">
        @foreach ($customersPengajuanLimit as $customerPengajuanLimit)
          <div class="card_notif">
            <a href="/supervisor/datacustomer/pengajuan/{{ $customerPengajuanLimit->id ?? null }}"
              class="text-black text-decoration-none">
              <p class="mb-0 fw-bold">Pengajuan Limit Pembelian</p>
              <p class="mb-0">Pengajuan limit pembeian dari {{ $customerPengajuanLimit->nama ?? null }} </p>
            </a>
          </div>
        @endforeach
      </div>

      <div class="opname_notif notif d-none">
        @foreach ($stokOpnamePengajuan as $opname)
          <div class="card_notif">
            <p class="mb-0 fw-bold">Pengajuan Stok Opname</p>
            <p class="mb-0">Pengajuan stok opname dari {{ $opname->linkStaff->nama ?? null }} </p>
          </div>
        @endforeach
      </div>
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
        <div class="col-12 col-sm-6 col-md-3 mt-3 mt-md-0">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">slow item</span>
            <input type="number" class="form-control" name="count" value="{{ $input['count'] ?? 5 }}">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3 mt-3 mt-md-0">
          <div class="input-group mb-3">
            <span class="input-group-text">Kas</span>
            <select class="form-select" name="kas">
              <option value>-- Pilih Kas --</option>
              @foreach ($data['kas'] as $kas)
                <option {{ $kas->id == $input['kas'] ? 'selected' : '' }} value={{ $kas->id }}>
                  {{ $kas->nama }}
                </option>
              @endforeach
            </select>
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
            <input type="date" name="dateEnd" class="form-control" min="{{ $input['dateStart'] ?? null }}"
              value="{{ $input['dateEnd'] ?? null }}" id="dateEnd">
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
          <h1 class="fs-5">Total</h1>
          <div class="informasi-list">
            <span class="d-flex align-items-center">
              <b>Omzet</b>
              <span>Rp. {{ number_format($data['total_omzet'] ?? 0, 0, '', '.') }}</span>
            </span>
            {{-- <span class="d-flex align-items-center">
            <b>Pengeluaran</b>
            <span>Rp. {{ number_format(($data['pembelian'] ?? 0) - ($data['total_retur'] ?? 0), 0, '', '.') }}</span>
          </span>
          <span class="d-flex align-items-center">
            <b>Untung/Rugi</b>
            <span>Rp.
              {{ number_format(($data['total_omzet'] ?? 0) - ($data['pembelian'] ?? 0) + ($data['total_retur'] ?? 0), 0, '', '.') }}</span>
          </span> --}}
            <span class="d-flex align-items-center">
              <b>Piutang</b>
              <span>Rp. {{ number_format($data['total_piutang'] ?? 0, 0, '', '.') }}</span>
            </span>
            <span class="d-flex align-items-center">
              <b>Retur</b>
              <span>Rp. {{ number_format($data['total_retur'] ?? 0, 0, '', '.') }}</span>
            </span>
            <span class="d-flex align-items-center">
              <b>HPP</b>
              <span>Rp. {{ number_format($data['total_hpp'] ?? 0, 0, '', '.') }}</span>
            </span>
          </div>
        </div>
        <div class="data-list">
          <h1 class="fs-5">Kas</h1>
          @if ($input['kas'] ?? null)
            <div class="informasi-list">
              <span class="d-flex align-items-center">
                <b>Saldo Awal</b>
                <span>Rp. {{ number_format($data['hitungkas']['saldo_awal'] ?? 0, 0, '', '.') }}</span>
              </span>
              <span class="d-flex align-items-center">
                <b>Saldo Akhir</b>
                <span>Rp. {{ number_format($data['hitungkas']['saldo_akhir'] ?? 0, 0, '', '.') }}</span>
              </span>
              <span class="d-flex align-items-center">
                <b>Pengeluaran</b>
                <span>Rp. {{ number_format($data['hitungkas']['pengeluaran'] ?? 0, 0, '', '.') }}</span>
              </span>
              <span class="d-flex align-items-center">
                <b>Pemasukan</b>
                <span>Rp. {{ number_format($data['hitungkas']['pemasukan'] ?? 0, 0, '', '.') }}</span>
              </span>
            </div>
          @else
            <small class="text-danger d-block text-center">pilih kas terlebih dahulu</small>
          @endif
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-sm-6">
        <div class="data-list">
          <h1 class="fs-5 fw-bold mb-3">Produk Slow Moving</h1>
          <div class="sales-container">
            @foreach ($data['produk_slow'] as $item)
              <div class="sales row">
                <h3 class="fs-6 col-4 text-capitalize">{{ $item->linkItem->nama ?? null }} </h3>
                <h3 class="fs-6 col-4">{{ $item->count ?? null }} kali trasaksi</h3>
                <h3 class="fs-6 col-4">{{ $item->total ?? null }} item terjual</h3>
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
              <h3 class="fs-6 fw-normal">{{ $loop->iteration }}. {{ $item->nama ?? null }}</h3>
            @endforeach
          </div>
        </div>
      </div>

      <input type="hidden" name="loginPassword" value="{{ session('password') }}">
      <input type="hidden" name="countt" value="{{ session('count') }}">
    </div>
  </div>

  @push('JS')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ mix('js/report.js') }}"></script>
    <script src="{{ mix('js/supervisor.js') }}"></script>
    <script>
      let time = new Date().getTime();
      const setActivityTime = (e) => {
        time = new Date().getTime();
      }

      document.body.addEventListener("keypress", setActivityTime);
      const refresh = () => {
        if (new Date().getTime() - time >= 600000) {
          window.location.reload(true);
        } else {
          setTimeout(refresh, 100000);
        }
      }
      setTimeout(refresh, 100000);
    </script>
  @endpush
@endsection
