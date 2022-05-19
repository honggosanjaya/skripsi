@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/supervisor/jenis">Jenis Customer</a></li>
  <li class="breadcrumb-item active" aria-current="page">Tambah</li>
</ol>
@endsection

@section('main_content')
  <div class="p-4">
    <a class="btn btn-primary mt-2 mb-3" href="/supervisor/jenis"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>
    @if(session()->has('addJenisSuccess'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('addJenisSuccess') }}
                <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
            </div>
        @endif
    <form class="form_jenis" method="POST" action="/supervisor/jenis/tambahjenis">
      @csrf
      <div class="mb-3">
        <label for="nama_jenis" class="form-label">Nama Jenis Customer</label>
        <input type="text" class="form-control @error('nama_jenis') is-invalid @enderror" id="nama_jenis"
          name="nama_jenis" value="{{ old('nama_jenis') }}">
        @error('nama_jenis')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="diskon" class="form-label">Diskon (0-100)</label>
        <input type="number" class="form-control @error('diskon') is-invalid @enderror" id="diskon" name="diskon"
          value="{{ old('diskon') }}" min="0" max="100" step=".01">
        @error('diskon')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan</label>
        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
          value="{{ old('keterangan') }}"></textarea>
        @error('keterangan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="/supervisor/jenis" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
