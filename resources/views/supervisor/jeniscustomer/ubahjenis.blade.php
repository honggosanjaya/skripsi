@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/supervisor/jenis">Jenis Customer</a></li>
  <li class="breadcrumb-item active" aria-current="page">Ubah</li>
</ol>
@endsection

@section('main_content')
  <div class="p-4">
    <a class="btn btn-primary mt-2 mb-3" href="/supervisor/jenis"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/jenis/ubahjenis/{{ $customertype->id }}"
        enctype="multipart/form-data">
        @method('put')
        @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Jenis Customer</label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
          name="nama" value="{{ old('nama', $customertype->nama) }}">
        @error('nama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="diskon" class="form-label">Diskon (0-100)</label>
        <input type="number" class="form-control @error('diskon') is-invalid @enderror" id="diskon" name="diskon"
          value="{{ old('diskon', $customertype->diskon) }}" min="0" max="100" step=".01">
        @error('diskon')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan">
        {{ $customertype->keterangan }}
        </textarea>
        @error('keterangan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="simpan_btn btn btn-primary">Simpan</button>
      
    </form>
  </div>
@endsection
