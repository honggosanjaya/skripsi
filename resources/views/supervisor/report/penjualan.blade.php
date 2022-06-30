@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
  </ol>
@endsection

@section('main_content')
  <div id="report" class="pt-4 px-3 px-sm-5">
    <form action="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}/report/penjualan" method="get">
      <div class="row">
        <div class="col-sm-2 col-6">
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Tahun</span>
            <input type="text" class="form-control" placeholder="2023" name="year"
              value="{{ $input['year'] ?? null }}">
          </div>
        </div>
        <div class="col-sm-4 col-6">
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
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Date Start</label>
            <input type="date" name="dateStart" class="form-control" value="{{ $input['dateStart'] ?? null }}"
              id="dateStart">
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
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
    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Tanggal</th>
            <th scope="col" class="text-center">Invoice</th>
            <th scope="col" class="text-center">Total harga (Rp)</th>
            <th scope="col" class="text-center">Sales</th>
            <th scope="col" class="text-center">Customer</th>
            <th scope="col" class="text-center">Tipe customer</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $dt)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>{{ $dt->created_at }}</td>
              <td>{{ $dt->linkInvoice->nomor_invoice }}</td>
              <td> {{ number_format($dt->linkInvoice->harga_total, 0, '', '.') }}</td>
              <td>{{ $dt->linkStaff->nama }}</td>
              <td>{{ $dt->linkCustomer->nama }}</td>
              <td>{{ $dt->linkCustomer->linkCustomerType->nama }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @push('JS')
    <script src="{{ asset('js/chart.js') }}"></script>
    <script src="{{ mix('js/report.js') }}"></script>
  @endpush
@endsection
