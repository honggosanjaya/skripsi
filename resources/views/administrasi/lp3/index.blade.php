@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/pesanan">Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">LP3</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4" id="laporan-penagihan">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="buat-tab" data-bs-toggle="tab" data-bs-target="#buat-tab-pane" type="button"
          role="tab" aria-controls="buat-tab-pane" aria-selected="true">Buat LP3</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button"
          role="tab" aria-controls="history-tab-pane" aria-selected="false">History LP3</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="buat-tab-pane" role="tabpanel" aria-labelledby="buat-tab" tabindex="0">
        <h3 class="my-4">Pembuatan LP3</h3>
        <form class="form-buatlp3" method="POST" action="/administrasi/lp3/penagihan">
          @csrf
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label for="id_invoice" class="form-label">Invoice yang Ditagih</label>
                <select class="select-invoice form-select @error('id_invoice') is-invalid @enderror" id="id_invoice"
                  name="id_invoice" value="{{ old('id_invoice') }}">
                  <option disabled selected value>Pilih Invoice</option>
                  @foreach ($invoices as $invoice)
                    {{-- @if ($invoice['is_disabled'] == true)
                      <option value="{{ $invoice['id'] }}" disabled>{{ $invoice['nomor_invoice'] }}</option>
                    @elseif ($invoice['is_disabled'] == false)
                      <option value="{{ $invoice['id'] }}">{{ $invoice['nomor_invoice'] }}</option>
                    @endif --}}
                    <option value="{{ $invoice->id }}">{{ $invoice->nomor_invoice ?? null }}</option>
                  @endforeach
                </select>
                @error('id_invoice')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Nama Customer</label>
                <input type="text" class="form-control nama-customer" value="pilih invoice dulu" readonly>
              </div>
            </div>
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Jumlah Tagihan</label>
                <input type="text" class="form-control jumlah-tagihan" value="pilih invoice dulu" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label for="id_staff_penagih" class="form-label">Nama Penagih</label>
                <select class="form-select @error('id_staff_penagih') is-invalid @enderror" id="id_staff_penagih"
                  name="id_staff_penagih" value="{{ old('id_staff_penagih') }}">
                  @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}">{{ $staff->nama ?? null }}</option>
                  @endforeach
                </select>
                @error('id_staff_penagih')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                  id="tanggal" value="{{ old('tanggal') }}">
                @error('tanggal')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
          </div>
          <div class="row justify-content-end mt-4">
            <div class="col-3 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </form>
      </div>


      <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
        <h3 class="my-4">History Pembuatan LP3</h3>
        <table class="table table-hover table-sm mt-4" id="table">
          <thead>
            <tr>
              <th scope="col" class="text-center">No</th>
              <th scope="col" class="text-center">Nomor Invoice</th>
              <th scope="col" class="text-center">Nama Penagih</th>
              <th scope="col" class="text-center">Tanggal</th>
              <th scope="col" class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $history)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $history->linkInvoice->nomor_invoice ?? null }}</td>
                <td>{{ $history->linkStaffPenagih->nama ?? null }}</td>
                <td>{{ $history->tanggal ?? null }}</td>
                @if ($history->status_enum ?? null)
                  <td>{{ $history->status_enum == '1' ? 'Sudah Ditagih' : 'Belum Ditagih' }}</td>
                @else
                  <td></td>
                @endif
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
