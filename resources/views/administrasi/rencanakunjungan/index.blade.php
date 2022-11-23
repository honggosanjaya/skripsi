@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Rencana Kunjungan</li>
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
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="buat-tab" data-bs-toggle="tab" data-bs-target="#buat-tab-pane" type="button"
          role="tab" aria-controls="buat-tab-pane" aria-selected="true">Buat Rencana Kunjungan</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-tab-pane" type="button"
          role="tab" aria-controls="history-tab-pane" aria-selected="false">History Rencana Kunjungan</button>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="buat-tab-pane" role="tabpanel" aria-labelledby="buat-tab" tabindex="0">
        <h1 class="fs-4 my-4">Perencanaan Kunjungan</h1>
        <form class="form-rencanakunjungan" method="POST" action="/administrasi/rencanakunjungan/create">
          @csrf
          <div class="row">
            <div class="col">
              <div class="mb-3">
                <label for="id_staff" class="form-label">Nama Salesman <span class='text-danger'>*</span></label>
                <select class="form-select @error('id_staff') is-invalid @enderror" id="id_staff" name="id_staff"
                  value="{{ old('id_staff') }}">
                  @foreach ($staffs as $staff)
                    <option value="{{ $staff->id }}">
                      {{ $staff->nama ?? null }}
                    </option>
                  @endforeach
                </select>
                @error('id_staff')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="col">
              <div class="mb-3">
                <label class="form-label">Tanggal <span class='text-danger'>*</span></label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                  id="tanggal" value="{{ old('tanggal') }}" />
                @error('tanggal')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-6">
              <div class="d-flex justify-content-between">
                <label class="form-label">Kunjungi Berdasar Wilayah</label>
                <div class="spinner-border d-none" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
              <select class="form-select select-district select-two" id="id_district" name="id_district">
                <option disabled selected value>
                  Pilih Wilayah
                </option>
                @foreach ($districts as $district)
                  <option value="{{ $district->id }}">
                    {{ $district->nama ?? null }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            <div>
              <div class="row">
                <div class="col-6">
                  <label for="id_customer" class="form-label">Customer yang Dikunjungi <span
                      class='text-danger'>*</span></label>
                </div>
              </div>
              <div class="form-input">
                <div class="row">
                  <div class="col-6">
                    <select class="select-customer form-select @error('id_customer') is-invalid @enderror"
                      id="id_customer" name="id_customer[]">
                      <option disabled selected value>
                        Pilih Customer
                      </option>
                      @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">
                          {{ $customer->nama ?? null }}
                        </option>
                      @endforeach
                    </select>
                    @error('id_customer')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-6">
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Estimasi Nominal</span>
                      <input type="number" class="form-control input-estimasi-nominal" placeholder="boleh dikosongi"
                        name="estimasi_nominal[]">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="d-flex justify-content-end my-3">
                      <button class="btn btn-danger remove-form me-3 d-none" type="button">
                        -
                      </button>
                      <button class="btn btn-success add-form" type="button">
                        +
                      </button>
                    </div>
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
        <h1 class="fs-4 my-4">History Perencanaan Kunjungan</h1>
        <button class="btn btn_purple mx-1 unduhRak-btn mb-3">
          <i class="bi bi-download px-1"></i>Unduh RAK
        </button>

        <form id="form_submit" class="form-submit form-downloadRak d-none" method="POST"
          action="/administrasi/rencanakunjungan/cetak-rak">
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

            <div class="col">
              <div class="mb-3">
                <label class="form-label">Nama Sales</label>
                <input type="text" class="form-control" placeholder="masukkan nama sales..." name="salesman">
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
              <th scope="col" class="text-center">Nama Customer</th>
              <th scope="col" class="text-center">Nama Salesman</th>
              <th scope="col" class="text-center">Tanggal</th>
              <th scope="col" class="text-center">Estimasi Nominal</th>
              <th scope="col" class="text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $history)
              <tr>
                <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                <td>{{ $history->linkCustomer->nama ?? null }}</td>
                <td>{{ $history->linkStaff->nama ?? null }}</td>
                @if ($history->tanggal ?? null)
                  <td data-order="{{ date('Y-m-d', strtotime($history->tanggal)) }}">
                    {{ date('d M Y', strtotime($history->tanggal)) }}
                  </td>
                @else
                  <td></td>
                @endif

                @if ($history->estimasi_nominal ?? null)
                  <td>{{ number_format($history->estimasi_nominal, 0, '', '.') }}</td>
                @else
                  <td></td>
                @endif

                @if ($history->status_enum != null)
                  <td>{{ $history->status_enum == '1' ? 'Sudah Dikunjungi' : 'Belum Dikunjungi' }}</td>
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
      $(document).on('click', '#perencanaan-kunjungan .unduhRak-btn', function(e) {
        $("#perencanaan-kunjungan .form-downloadRak").toggleClass('d-none');
        $(this).toggleClass('btn_purple');
        $(this).toggleClass('btn-danger');
        $(this).hasClass("btn_purple") ? $(this).html('<i class="bi bi-download px-1"></i>Unduh RAK') :
          $(this).html('<span class="iconify fs-3 me-2" data-icon="material-symbols:cancel"></span>Batal')
      })
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
