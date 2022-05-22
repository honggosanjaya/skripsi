@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/kendaraan">Kendaraan</a></li>
  <li class="breadcrumb-item active" aria-current="page">Tambah</li>
</ol>
@endsection

@section('main_content')
  <div class="p-4">
    <a class="btn btn-primary mt-2 mb-3" href="/administrasi/kendaraan"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>
    
    <form id="form_submit" class="form-submit" method="POST" action="/administrasi/kendaraan/tambahkendaraan">
      @csrf
      <div class="mb-3">
        <label for="nama_kendaraan" class="form-label">Nama Kendaraan</label>
        <input type="text" class="form-control @error('nama_kendaraan') is-invalid @enderror" id="nama_kendaraan"
          name="nama_kendaraan" value="{{ old('nama_kendaraan') }}">
        @error('nama_kendaraan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="plat_kendaraan" class="form-label">Plat Nomor Kendaraan</label>
        <input type="text" class="form-control @error('plat_kendaraan') is-invalid @enderror" id="plat_kendaraan"
          name="plat_kendaraan" value="{{ old('plat_kendaraan') }}">
        @error('plat_kendaraan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="kapasitas_harga" class="form-label">Kapasitas Harga (Rp)</label>
        <input type="number" class="form-control @error('kapasitas_harga') is-invalid @enderror" id="kapasitas_harga" name="kapasitas_harga"
          value="{{ old('kapasitas_harga') }}" step=".01">
        @error('kapasitas_harga')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="kapasitas_volume" class="form-label">Kapasitas Volume (Cm3)</label>
        <input type="number" class="form-control @error('kapasitas_volume') is-invalid @enderror" id="kapasitas_volume" name="kapasitas_volume"
          value="{{ old('kapasitas_volume') }}">
        @error('kapasitas_volume')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="simpan_btn btn btn-primary">Simpan</button>
      
    </form>
  </div>
@endsection