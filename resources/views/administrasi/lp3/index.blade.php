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
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
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
                <label for="id_staff_penagih" class="form-label">Nama Penagih <span class='text-danger'>*</span></label>
                <select class="form-select select2 @error('id_staff_penagih') is-invalid @enderror" id="id_staff_penagih"
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
                <label class="form-label">Tanggal <span class='text-danger'>*</span></label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                  id="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}">
                @error('tanggal')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>
          </div>

          {{-- {{ dd($districts) }} --}}

          <div class="row mb-3">
            <div class="col-6">
              <div class="d-flex justify-content-between">
                <label class="form-label">Tagih Berdasar Wilayah</label>
                <div class="spinner-border d-none" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <select class="form-select select-district select2" id="id_district" name="id_district">
                <option disabled selected value>Pilih Wilayah</option>
                @foreach ($districts as $district)
                  <option value="{{ $district->id }}"> {{ $district->nama ?? null }} </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <div>
              <div class="row">
                <div class="col">
                  <label class="form-label">Invoice yang Ditagih <span class='text-danger'>*</span></label>
                </div>
                <div class="col">
                  <label class="form-label">Nama Customer</label>
                </div>
                <div class="col">
                  <label class="form-label">Jumlah Tagihan</label>
                </div>
              </div>
              <div class="form-input">
                <div class="row">
                  <div class="col">
                    <select class="select-invoice form-select @error('id_invoice') is-invalid @enderror" id="id_invoice"
                      name="id_invoice[]">
                      <option disabled selected value>Pilih Invoice</option>
                      @foreach ($invoices as $invoice)
                        <option value="{{ $invoice->id }}">{{ $invoice->nomor_invoice ?? null }}</option>
                      @endforeach
                    </select>
                    @error('id_invoice')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                  <div class="col">
                    <input type="text" class="form-control nama-customer" value="pilih invoice dulu" readonly>
                  </div>
                  <div class="col">
                    <input type="text" class="form-control jumlah-tagihan" value="pilih invoice dulu" readonly>
                  </div>
                </div>

                <div class="row justify-content-end my-3">
                  <div class="col-4 d-flex justify-content-end">
                    <button class="btn btn-danger remove-form me-3 d-none" type="button">-</button>
                    <button class="btn btn-success add-form" type="button">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row justify-content-end mt-4">
            <div class="col-6 d-flex justify-content-end">
              <button type="button" class="btn btn-danger delete-all me-3">
                <span class="iconify fs-3 me-2" data-icon="bi:trash"></span>Hapus Semua
              </button>
              <button type="submit" class="btn btn-primary">
                <span class="iconify fs-3 me-2" data-icon="bi:send-check"></span>Submit
              </button>
            </div>
          </div>
        </form>
      </div>


      <div class="tab-pane fade" id="history-tab-pane" role="tabpanel" aria-labelledby="history-tab" tabindex="0">
        <h3 class="my-4">History Pembuatan LP3</h3>

        <button class="btn btn_purple mx-1 unduhLp3-btn mb-3">
          <i class="bi bi-download px-1"></i>Unduh LP3
        </button>

        <form id="form_submit" class="form-submit form-downloadLp3 d-none" method="POST"
          action="/administrasi/lp3/cetak-lp3">
          @csrf
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

          <div class="row justify-content-end mb-4">
            <div class="col d-flex justify-content-end">
              <button type="submit" class="btn btn-primary"> <span class="iconify fs-3 me-2"
                  data-icon="ic:round-print"></span>Cetak</button>
            </div>
          </div>
        </form>

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
                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                <td>{{ $history->linkInvoice->nomor_invoice ?? null }}</td>
                <td>{{ $history->linkStaffPenagih->nama ?? null }}</td>
                <td>{{ $history->tanggal ?? null }}</td>
                @if ($history->status_enum != null)
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
    <script>
      $(document).on('click', '#laporan-penagihan .unduhLp3-btn', function(e) {
        $("#laporan-penagihan .form-downloadLp3").toggleClass('d-none');
        $(this).toggleClass('btn_purple');
        $(this).toggleClass('btn-danger');

        $(this).hasClass("btn_purple") ? $(this).html('<i class="bi bi-download px-1"></i>Unduh LP3') :
          $(this).html('<span class="iconify fs-3 me-2" data-icon="material-symbols:cancel"></span>Batal')
      })
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
