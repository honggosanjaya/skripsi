@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form class="form_produk" method="POST" action="/dashboard/produk">
      @csrf
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang</label>
        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang"
          name="nama_barang" value="{{ old('nama_barang') }}">
        @error('nama_barang')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
          value="{{ old('stok') }}">
        @error('stok')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="satuan" class="form-label">Satuan Stok</label>
        <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan"
          value="{{ old('satuan') }}">
        @error('satuan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="harga_satuan" class="form-label">Harga Satuan</label>
        <input type="text" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan"
          name="harga_satuan" value="{{ old('harga') }}">
        @error('harga_satuan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard/produk" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
