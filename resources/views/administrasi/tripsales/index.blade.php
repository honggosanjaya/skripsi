@extends('layouts/main')
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

  <div class="px-5 pt-4" id="perencanaan-kunjungan">
    <h1 class="fs-4 mb-4">Koordinat Trip Sales</h1>
    <form action="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/tripsales" method="get" class="mb-5">
      <input type="hidden" name="koordinat" value="true">
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Date Start</label>
            <input type="date" name="tripDateStart" class="form-control" value="{{ $input['tripDateStart'] ?? null }}"
              id="tripDateStart">
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Date End</label>
            <input type="date" name="tripDateEnd" class="form-control" value="{{ $input['tripDateEnd'] ?? null }}"
              id="tripDateEnd">
          </div>
        </div>

        <div class="col">
          <div class="mb-3">
            <label class="form-label">Nama Sales</label>
            <input type="text" class="form-control" placeholder="masukkan nama sales..." name="tripSalesman"
              value="{{ $input['tripSalesman'] ?? null }}">
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

    <table class="table table-hover table-sm" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama Customer</th>
          <th scope="col" class="text-center">Nama Sales</th>
          <th scope="col" class="text-center">Waktu Masuk</th>
          <th scope="col" class="text-center">Waktu Keluar</th>
          <th scope="col" class="text-center">Status</th>
          <th scope="col" class="text-center">Cek Lokasi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($tripssales as $dt)
          <tr>
            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
            <td class="text-center">{{ $dt->linkCustomer->nama ?? null }}</td>
            <td class="text-center">{{ $dt->linkStaff->nama ?? null }}</td>
            @if ($dt->waktu_masuk ?? null)
              <td data-order="{{ date('Y-m-d g i a', strtotime($dt->waktu_masuk)) }}">
                {{ date('j F Y, g:i a', strtotime($dt->waktu_masuk)) }}
              </td>
            @else
              <td></td>
            @endif
            @if ($dt->waktu_keluar ?? null)
              <td data-order="{{ date('Y-m-d g i a', strtotime($dt->waktu_keluar)) }}">
                {{ date('j F Y, g:i a', strtotime($dt->waktu_keluar)) }}
              </td>
            @else
              <td></td>
            @endif
            @if ($dt->status_enum ?? null)
              <td class="text-center">{{ $dt->status_enum == '1' ? 'Trip' : 'Effective Call' }}</td>
            @else
              <td></td>
            @endif
            <td class="text-center">
              <a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/tripsales/{{ $dt->id }}"
                class="btn btn-primary">
                <span class="iconify fs-3 me-2" data-icon="akar-icons:check-in"></span> Cek
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
