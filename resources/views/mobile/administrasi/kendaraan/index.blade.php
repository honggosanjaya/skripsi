@extends('layouts.mainmobile')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kendaraan</li>
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
    <form method="GET" action="/administrasi/kendaraan/cari" class="col-10 mx-auto">
      <div class="input-group">
        <input type="text" class="form-control" name="cari" placeholder="Cari Kendaraan..."
          value="{{ request('cari') }}">
        <button type="submit" class="btn btn-purple-gradient">
          <span class="iconify fs-5 me-2" data-icon="fe:search"></span>
        </button>
      </div>
    </form>

    <div class="row justify-content-end">
      <div class="col d-flex justify-content-center">
        <a href="/administrasi/kendaraan/tambah" class="btn btn-purple-gradient mt-4 mb-5"><span class="iconify fs-4 me-1"
            data-icon="dashicons:database-add"></span>Tambah Kendaraan</a>
      </div>
    </div>

    @foreach ($vehicles as $vehicle)
      <div class="list-mobile">
        <div class="d-flex justify-content-between align-items-start mb-4">
          <div>
            <h1 class="fs-5 mb-0 title">{{ $vehicle->nama ?? null }}</h1>
            <span class="text-secondary text-uppercase">{{ $vehicle->kode_kendaraan ?? null }}</span>
          </div>
        </div>

        <div class="informasi-list">
          <span class="d-flex align-items-center"><b class="fs-6">Kapasitas Volume </b>
            <span>{{ number_format($vehicle->kapasitas_volume ?? 0, 0, '', '.') }} cm<sup>3</sup></span>
          </span>
          <span class="d-flex align-items-center"><b class="fs-6">Kapasitas Harga </b>
            <span>Rp {{ number_format($vehicle->kapasitas_harga ?? 0, 0, '', '.') }}</span>
          </span>
          @if ($vehicle->tanggal_pajak ?? null)
            <span class="d-flex align-items-center"><b class="fs-6">Tanggal Pajak </b>
              <span>{{ date('d M Y', strtotime($vehicle->tanggal_pajak)) }}</span>
            </span>
          @endif
        </div>

        <div class="action d-flex justify-content-center mt-3">
          <a href="/administrasi/kendaraan/ubah/{{ $vehicle->id }}" class="btn btn-warning me-3">
            <span class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span> Edit
          </a>
          <a href="/administrasi/kendaraan/{{ $vehicle->id }}" class="btn btn-purple">
            <span class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Lihat Detail
          </a>
        </div>
      </div>
    @endforeach

    <div class="mt-5 d-flex justify-content-center">
      {{ $vehicles->appends(request()->except('page'))->links() }}
    </div>
  </div>
@endsection
