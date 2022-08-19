@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/cashaccount">Cash Account</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/cashaccount/tambah">
      @csrf
      <div class="row">
        <div class="col-12">
          <div class="mb-3">
            <label for="nama_cashaccount" class="form-label">Nama Cash Account<span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama_cashaccount') is-invalid @enderror"
              id="nama_cashaccount" name="nama_cashaccount" value="{{ old('nama_cashaccount') }}">
            @error('nama_cashaccount')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col-12">
          <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan <span class='text-danger'>*</span></label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">{{ old('keterangan') }}</textarea>
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
          <button type="submit" class="btn btn-primary"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
        </div>
      </div>
    </form>
  </div>
@endsection
