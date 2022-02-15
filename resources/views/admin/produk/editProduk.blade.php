@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form class="form_produk" method="POST" action="/dashboard/produk/{{ $barang->id }}">
      @method('put')
      @csrf
      <div class="mb-3">
        <label for="nama_barang" class="form-label">Nama Barang</label>
        <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" id="nama_barang"
          name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}">
        @error('nama_barang')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
          value="{{ old('stok', $barang->stok) }}">
        @error('stok')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="satuan" class="form-label">Satuan Stok</label>
        <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan"
          value="{{ old('satuan', $barang->satuan) }}">
        @error('satuan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="harga_satuan" class="form-label">Harga Satuan</label>
        <input type="text" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan"
          name="harga_satuan" value="{{ old('harga_satuan', $barang->harga_satuan) }}">
        @error('harga_satuan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="status_produk" class="form-label">Status Produk</label>
        <select class="form-select @error('status_produk') is-invalid @enderror" id="status_produk" name="status_produk"
          value="{{ old('status_produk', $barang->status_produk) }}">
          <option hidden="true">Pilih Status Produk</option>
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Tidak Aktif</option>
        </select>
        @error('status_produk')
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
