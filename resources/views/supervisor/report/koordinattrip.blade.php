@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Koordinat Trip</li>
  </ol>
@endsection
@section('main_content')
  <div id="report" class="px-3 px-sm-5 pt-4">
    <form action="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/report/koordinattrip" method="get">
      <div class="row">
        <div class="col-6 col-md-2">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tahun</span>
            <input type="text" class="form-control" placeholder="2023" name="year"
              value="{{ $input['year'] ?? null }}">
          </div>
        </div>
        <div class="col-6 col-md-4 mb-3">
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

        <div class="col-sm-6">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Nama Salesman</span>
            <input type="text" class="form-control" placeholder="julian" name="salesman"
              value="{{ $input['salesman'] ?? null }}">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="mb-3">
            <label class="form-label">Date Start</label>
            <input type="date" name="dateStart" class="form-control" value="{{ $input['dateStart'] ?? null }}"
              id="dateStart">
          </div>
        </div>
        <div class="col-sm-6">
          <div class="mb-3">
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

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
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
          @foreach ($data as $dt)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration ?? null }}</th>
              <td class="text-center">{{ $dt->linkCustomer->nama ?? null }}</td>
              <td class="text-center">{{ $dt->linkStaff->nama ?? null }}</td>
              @if ($dt->waktu_masuk ?? null)
                <td>{{ date('j F Y, g:i a', strtotime($dt->waktu_masuk)) }}</td>
              @else
                <td></td>
              @endif
              @if ($dt->waktu_keluar ?? null)
                <td>{{ date('j F Y, g:i a', strtotime($dt->waktu_keluar)) }}</td>
              @else
                <td></td>
              @endif
              @if ($dt->status_enum ?? null)
                <td class="text-center">{{ $dt->status_enum == '1' ? 'Trip' : 'Effective Call' }}</td>
              @else
                <td></td>
              @endif
              <td class="text-center">
                <a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/report/koordinattrip/{{ $dt->id }}"
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
      <script src="{{ asset('js/chart.js') }}"></script>
      <script src="{{ mix('js/report.js') }}"></script>
    @endpush
  @endsection
