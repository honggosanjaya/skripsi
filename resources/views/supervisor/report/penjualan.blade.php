@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
  </ol>
@endsection

@section('main_content')
  <div id="report" class="pt-4 px-3 px-sm-5">
    <form action="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/report/penjualan" method="get">
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
              <th scope="row" class="text-center">{{ $loop->iteration ?? null }}</th>
              <td>{{ $dt->created_at ?? null }}</td>
              <td>
                <a class="text-primary cursor-pointer text-decoration-none" data-bs-toggle="modal"
                  data-bs-target="#invoice{{ $dt->linkInvoice->id }}">
                  {{ $dt->linkInvoice->nomor_invoice ?? null }}
                </a>
              </td>
              <td> {{ number_format($dt->linkInvoice->harga_total ?? 0, 0, '', '.') }}</td>
              <td>{{ $dt->linkStaff->nama ?? null }}</td>
              <td>{{ $dt->linkCustomer->nama ?? null }}</td>
              <td>{{ $dt->linkCustomer->linkCustomerType->nama ?? null }}</td>
            </tr>


            <div class="modal fade" id="invoice{{ $dt->linkInvoice->id }}" tabindex="-1"
              aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="info-list">
                      <span class="d-flex"><b>Nomor invoice</b>
                        {{ $dt->linkInvoice->nomor_invoice ?? null }}</span>
                      <span class="d-flex"><b>Tanggal pesan</b>
                        {{ date('d F Y', strtotime($dt->linkInvoice->created_at ?? '-')) }}</span>
                      @if ($dt->linkOrderTrack->status_enum != null)
                        <span class="d-flex"><b>Status Pesanan</b>
                          {{ $dt->linkOrderTrack->status_enum == '4' ? 'Telah Sampai' : ($dt->linkOrderTrack->status_enum == '5' ? 'Pembayaran' : 'Selesai') }}</span>
                      @endif
                      <span class="d-flex"><b>Event</b>
                        {{ $dt->linkInvoice->linkEvent->nama ?? null }}</span>

                      @if ($dt->linkInvoice->linkEvent != null)
                        @if ($dt->linkInvoice->linkEvent->potongan != null)
                          <span class="d-flex"><b>Potongan Event</b>
                            {{ $dt->linkInvoice->linkEvent->potongan }}</span>
                        @elseif($dt->linkInvoice->linkEvent->diskon != null)
                          <span class="d-flex"><b>Diskon Event</b>
                            {{ $dt->linkInvoice->linkEvent->diskon }} %</span>
                        @endif
                      @endif

                      <span class="d-flex"><b>Harga Total</b>
                        Rp. {{ number_format($dt->linkInvoice->harga_total ?? 0, 0, '', '.') }}</span>
                      @if ($dt->linkInvoice->jatuh_tempo != null)
                        <span class="d-flex"><b>Jatuh Tempo</b>
                          {{ date('d M Y', strtotime($dt->linkInvoice->jatuh_tempo)) }}</span>
                      @else
                        <span class="d-flex"><b>Jatuh Tempo</b></span>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
