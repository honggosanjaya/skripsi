@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/kanvas">Kanvas</a></li>
    <li class="breadcrumb-item active" aria-current="page">History</li>
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

  @if (session()->has('pesanError'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('pesanError') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4" id="kanvas">
    <div class="loading-indicator d-none">
      <div class="spinner-grow spinner-grow-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="spinner-grow spinner-grow-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <div class="spinner-grow spinner-grow-sm" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <h1 class="fs-4 mb-4">Pembuatan Kanvas Berdasarkan History</h1>
    <form id="form_submit" method="POST" action="/administrasi/kanvas/store?route=history">
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label class="form-label">Nama Kanvas<span class="text-danger">*</span></label>
            <select class="form-select select-history-kanvas select-two" required>
              <option disabled selected value>Pilih Kanvas</option>
              @foreach ($listkanvas as $kanvas)
                <option value="{{ str_replace(',', '-', $kanvas->ids) }}">{{ $kanvas->nama ?? null }}</option>
              @endforeach
            </select>

            <input type="hidden" class="hidden-nama-kanvas" id="nama" name="nama">
          </div>
        </div>

        <div class="col">
          <div class="mb-3">
            <label for="id_staff_yang_membawa" class="form-label">Sales Yang Membawa<span
                class="text-danger">*</span></label>
            <select class="form-select @error('id_staff_yang_membawa') is-invalid @enderror" id="id_staff_yang_membawa"
              name="id_staff_yang_membawa" value="{{ old('id_staff_yang_membawa') }}">
              @foreach ($staffs as $staff)
                <option value="{{ $staff->id }}">{{ $staff->nama ?? null }}</option>
              @endforeach
            </select>
            @error('id_staff_yang_membawa')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="form-group">
        <div>
          <div class="row">
            <div class="col">
              <label class="form-label">Item yang Dibawa<span class="text-danger">*</span></label>
            </div>
            <div class="col">
              <label class="form-label">Jumlah<span class="text-danger">*</span></label>
            </div>
          </div>
          <div class="form-input">
            <div class="row">
              <div class="col">
                <select class="select-item form-select @error('id_item') is-invalid @enderror" id="id_item"
                  name="id_item[]" required>
                  <option disabled selected value>Pilih Item</option>
                  @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->nama ?? null }}</option>
                  @endforeach
                </select>
                @error('id_item')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="col">
                <input type="text" class="form-control jumlah_item  @error('jumlah_item') is-invalid @enderror"
                  id="jumlah_item" name="jumlah_item[]" required>
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
          <button class="btn btn-danger remove-all-form d-none me-2" type="button">
            <span class="iconify fs-3 me-2" data-icon="bi:trash"></span>Hapus Semua
          </button>
          <button type="button" class="btn btn-primary btn-submit">
            <span class="iconify fs-3 me-2" data-icon="bi:send-check"></span>Submit
          </button>
        </div>
      </div>
    </form>


    <hr class="my-4">

    <h1 class="fs-4">History Kanvas</h1>
    <table class="table table-hover table-sm mt-4" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama</th>
          <th scope="col" class="text-center">Pengonfirmasi Pembawaan</th>
          <th scope="col" class="text-center">Yang Membawa</th>
          <th scope="col" class="text-center">Pengonfirmasi Pengembalian</th>
          <th scope="col" class="text-center">Waktu Dibawa</th>
          <th scope="col" class="text-center">Waktu Dikembalikan</th>
          <th scope="col" class="text-center">Banyak Jenis Item</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($listkanvas as $kanvas)
          <tr>
            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
            <td>
              <a class="text-primary cursor-pointer text-decoration-none detail_trigger" data-bs-toggle="modal"
                data-bs-target="#kanvas{{ str_replace(',', '-', $kanvas->ids) }}"
                data-idkanvas="{{ str_replace(',', '-', $kanvas->ids) }}">
                {{ $kanvas->nama ?? null }}
              </a>
            </td>
            <td>{{ $kanvas->linkStaffPengonfirmasiPembawaan->nama ?? null }}</td>
            <td>{{ $kanvas->linkStaffYangMembawa->nama ?? null }}</td>
            <td>{{ $kanvas->linkStaffPengonfirmasiPengembalian->nama ?? null }}</td>

            @if ($kanvas->waktu_dibawa ?? null)
              <td data-order="{{ date('Y-m-d g i a', strtotime($kanvas->waktu_dibawa)) }}">
                {{ date('j F Y, g:i a', strtotime($kanvas->waktu_dibawa)) }}
              </td>
            @else
              <td></td>
            @endif

            @if ($kanvas->waktu_dikembalikan ?? null)
              <td data-order="{{ date('Y-m-d g i a', strtotime($kanvas->waktu_dikembalikan)) }}">
                {{ date('j F Y, g:i a', strtotime($kanvas->waktu_dikembalikan)) }}
              </td>
            @else
              <td></td>
            @endif

            <td class="text-center">{{ $kanvas->banyak_jenis_item ?? null }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    @foreach ($listkanvas as $kanvas)
      <div class="modal fade" id="kanvas{{ str_replace(',', '-', $kanvas->ids) }}" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Detail Kanvas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="info-list">
                <span class="d-flex"><b>Nama Kanvas</b>
                  {{ $kanvas->nama ?? null }}</span>
                <span class="d-flex"><b>Sales yang Membawa</b>
                  {{ $kanvas->linkStaffYangMembawa->nama ?? null }}</span>
                <span class="d-flex"><b>Pengonfirmasi Pembawaan</b>
                  {{ $kanvas->linkStaffPengonfirmasiPembawaan->nama ?? null }}</span>
                <span class="d-flex"><b>Pengonfirmasi Pengembalian</b>
                  {{ $kanvas->linkStaffPengonfirmasiPengembalian->nama ?? null }}</span>
                @if ($kanvas->waktu_dibawa ?? null)
                  <span class="d-flex"><b>Waktu Dibawa</b>
                    {{ date('d F Y, g:i a', strtotime($kanvas->waktu_dibawa)) }}</span>
                @else
                  <span class="d-flex"><b>Waktu Dibawa</b></span>
                @endif
                @if ($kanvas->waktu_dikembalikan ?? null)
                  <span class="d-flex"><b>Waktu Dikembalikan</b>
                    {{ date('d F Y, g:i a', strtotime($kanvas->waktu_dikembalikan)) }}</span>
                @else
                  <span class="d-flex"><b>Waktu Dikembalikan</b></span>
                @endif
              </div>

              <div class="table-responsive mt-4">
                <table class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">No</th>
                      <th scope="col" class="text-center">Nama Barang</th>
                      <th scope="col" class="text-center">Stok Awal</th>
                      <th scope="col" class="text-center">Sisa Stok</th>
                    </tr>
                  </thead>
                  <tbody class="table_body">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  @push('JS')
    <script>
      $(document).ready(function() {
        $('.select-history-kanvas').select2();
      });
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
