@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/jenis">Jenis Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST"
      action="/supervisor/jenis/ubahjenis/{{ $customertype->id ?? null }}" enctype="multipart/form-data">
      @method('put')
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Jenis Customer <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              value="{{ old('nama', $customertype->nama ?? null) }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="diskon" class="form-label">Diskon (0-100) % <span class='text-danger'>*</span></label>
            <input type="number" class="form-control @error('diskon') is-invalid @enderror" id="diskon" name="diskon"
              value="{{ old('diskon', $customertype->diskon ?? null) }}" min="0" max="100" step=".01">
            @error('diskon')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ $customertype->keterangan ?? null }}</textarea>
            @error('keterangan')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-warning">Ubah</button>
        </div>
      </div>
    </form>
  </div>
@endsection
