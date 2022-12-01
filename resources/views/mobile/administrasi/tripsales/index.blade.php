@extends('layouts.mainmobile')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Trip Sales</li>
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

  <div class="container pt-4" id="perencanaan-kunjungan">
    <div class="row mb-5 align-items-center">
      <div class="col-7">
        <h1 class="fs-5 mb-0">Koordinat Trip Sales</h1>
      </div>
      <div class="col-5 d-flex justify-content-end">
        <button type="button" class="btn btn-purple-gradient filter-btn"><span class="iconify me-1 fs-3"
            data-icon="material-symbols:filter-alt-outline"></span>Filter</button>
      </div>
    </div>

    @if (count($tripssales) == 0)
      <p class="text-danger text-center">Tidak ada data</p>
    @else
      @foreach ($tripssales as $dt)
        <div class="list-mobile">
          <div class="d-flex justify-content-between align-items-start mb-4">
            <h1 class="fs-5 mb-0 title">{{ $dt->linkStaff->nama ?? null }}</h1>

            @if ($dt->status_enum ?? null)
              @if ($dt->status_enum == '1')
                <p class="mb-0 badge bg-warning">Trip</p>
              @else
                <p class="mb-0 badge bg-success">Effective Call</p>
              @endif
            @endif
          </div>

          <div class="row">
            <div class="col">
              <div class="flex-item">
                <p><small>Nama Customer</small><br><strong>{{ $dt->linkCustomer->nama ?? null }}</strong>
                </p>
              </div>
            </div>
            @if ($dt->waktu_masuk ?? null)
              <div class="col">
                <div class="flex-item">
                  <p><small>Waktu
                      Masuk</small><br><strong>{{ date('j F Y, g:i a', strtotime($dt->waktu_masuk)) }}</strong></p>
                </div>
              </div>
            @endif
            @if ($dt->waktu_keluar ?? null)
              <div class="col">
                <div class="flex-item">
                  <p><small>Waktu
                      Keluar</small><br><strong>{{ date('j F Y, g:i a', strtotime($dt->waktu_keluar)) }}</strong>
                  </p>
                </div>
              </div>
            @endif

          </div>

          <div class="action d-flex justify-content-center mt-3">
            <a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/tripsales/{{ $dt->id }}"
              class="btn btn-purple-gradient w-100">
              <span class="iconify fs-3 me-2" data-icon="akar-icons:check-in"></span> Cek
            </a>
          </div>
        </div>
      @endforeach
    @endif

    <div class="mt-5 d-flex justify-content-center">
      {{ $tripssales->appends(request()->except('page'))->links() }}
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
        <form action="/administrasi/tripsales" method="get">
          <button type="submit" class="btn btn-danger">Reset</button>
        </form>
      </div>

      <form action="/administrasi/tripsales" method="get" class="mb-5">
        <input type="hidden" name="koordinat" value="true">
        <div class="row">
          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Date Start</label>
              <input type="date" name="tripDateStart" class="form-control"
                value="{{ $input['tripDateStart'] ?? null }}" id="tripDateStart">
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Date End</label>
              <input type="date" name="tripDateEnd" class="form-control" value="{{ $input['tripDateEnd'] ?? null }}"
                id="tripDateEnd">
            </div>
          </div>

          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Nama Sales</label>
              <input type="text" class="form-control" placeholder="masukkan nama sales..." name="tripSalesman"
                value="{{ $input['tripSalesman'] ?? null }}">
            </div>
          </div>

          <div class="col-12">
            <div class="mb-3">
              <label class="form-label">Nama Customer</label>
              <input type="text" class="form-control" placeholder="masukkan nama customer..." name="tripCustomer"
                value="{{ $input['tripCustomer'] ?? null }}">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col">
            <button type="submit" class="btn btn-purple-gradient w-100"><span class="iconify fs-3 me-1"
                data-icon="clarity:filter-grid-solid"></span>Filter Data</button>
          </div>
        </div>
      </form>
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
