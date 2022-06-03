@extends('layouts.main')

@section('main_content')
  <form method="POST" action="/administrasi/stok/produk" enctype="multipart/form-data">
    @csrf
    <div class="my-3">
      <label for="nama" class="form-label">Nama</label>
      <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
        value="{{ old('nama') }}">
      @error('nama')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="kode_barang" class="form-label">Kode Barang</label>
      <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang"
        name="kode_barang" value="{{ old('kode_barang') }}">
      @error('kode_barang')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="stok" class="form-label">Stok</label>
      <input type="text" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
        value="{{ old('stok') }}">
      @error('stok')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>


    <div class="my-3">
      <label for="min_stok" class="form-label">Min Stok</label>
      <input type="text" class="form-control @error('min_stok') is-invalid @enderror" id="min_stok" name="min_stok"
        value="{{ old('min_stok') }}">
      @error('min_stok')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="max_stok" class="form-label">Max Stok</label>
      <input type="text" class="form-control @error('max_stok') is-invalid @enderror" id="max_stok" name="max_stok"
        value="{{ old('max_stok') }}">
      @error('max_stok')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

{{-- takeout sementara --}}
    {{-- <div class="my-3">
      <label for="max_pengadaan" class="form-label">Max Pengadaan</label>
      <input type="text" class="form-control @error('max_pengadaan') is-invalid @enderror" id="max_pengadaan"
        name="max_pengadaan" value="{{ old('max_pengadaan') }}">
      @error('max_pengadaan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div> --}}


    <div class="my-3">
      <label for="satuan" class="form-label">Satuan</label>
      <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan"
        value="{{ old('satuan') }}">
      @error('satuan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="harga_satuan" class="form-label">Harga Satuan</label>
      <input type="text" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan"
        name="harga_satuan" value="{{ old('harga_satuan') }}">
      @error('harga_satuan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="volume" class="form-label">Volume Barang (cm3)</label>
      <input type="number" class="form-control @error('volume') is-invalid @enderror" id="volume" name="volume"
        value="{{ old('volume') }}" step=".01">
      @error('volume')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>


    <div class="my-3">
      <label for="status" class="form-label">Status</label>
      <select class="form-select" name="status">
        @foreach ($statuses as $status)
          @if (old('status') == $status->id)
            <option value="{{ $status->id }}" selected>{{ $status->nama }}</option>
          @else
            <option value="{{ $status->id }}">{{ $status->nama }}</option>
          @endif
        @endforeach
      </select>
    </div>


    <div class="mb-3">
      <label for="gambar" class="form-label">Gambar</label>
      <img class="img-preview img-fluid">
      <input class="form-control @error('gambar') is-invalid @enderror" type="file" id="gambar" name="gambar"
        onchange="prevImg()">
      @error('gambar')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary">Tambah Barang</button>
  </form>

  <script>
    function prevImg() {
      const image = document.querySelector('#gambar');
      const imgPreview = document.querySelector('.img-preview');

      imgPreview.style.display = 'block';
      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);

      oFReader.onload = function(OFREvent) {
        imgPreview.src = OFREvent.target.result;
      }
    }
  </script>
@endsection
