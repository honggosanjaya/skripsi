@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/kanvas">Kanvas</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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

  <div class="px-5 pt-4" id="kanvas">
    <h1 class="fs-4 mb-4">Pembuatan Kanvas</h1>
    <form id="form_submit" method="POST" action="/administrasi/kanvas/store">
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Kanvas<span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              value="{{ old('nama') }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
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
          <button class="btn btn-danger remove-all-form d-none me-2" type="button">Hapus Semua</button>
          <button type="button" class="btn btn-primary btn-submit">Submit</button>
        </div>
      </div>
    </form>
  </div>

  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
