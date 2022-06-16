@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
  <li class="breadcrumb-item active" aria-current="page">Ubah</li>
</ol>
@endsection
@section('main_content')
  <form method="POST" action="/administrasi/stok/produk/{{ $item->id }}" enctype="multipart/form-data">
    @method('put')
    @csrf
    <div class="my-3">
      <label for="nama" class="form-label">Nama</label>
      <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
        value="{{ old('nama', $item->nama) }}">
      @error('nama')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="kode_barang" class="form-label">Kode Barang</label>
      <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" id="kode_barang"
        name="kode_barang" value="{{ old('kode_barang', $item->kode_barang) }}">
      @error('kode_barang')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

{{-- item tidak boleh ubah stok --}}
    {{-- <div class="my-3">
      <label for="stok" class="form-label">Stok</label>
      <input type="text" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
        value="{{ old('stok', $item->stok) }}">
      @error('stok')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div> --}}

    <div class="my-3">
      <label for="min_stok" class="form-label">Min Stok</label>
      <input type="text" class="form-control @error('min_stok') is-invalid @enderror" id="min_stok" name="min_stok"
        value="{{ old('min_stok', $item->min_stok) }}">
      @error('min_stok')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="max_stok" class="form-label">Max Stok</label>
      <input type="text" class="form-control @error('max_stok') is-invalid @enderror" id="max_stok" name="max_stok"
        value="{{ old('max_stok', $item->max_stok) }}">
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
        name="max_pengadaan" value="{{ old('max_pengadaan', $item->max_pengadaan) }}">
      @error('max_pengadaan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div> --}}

    <div class="my-3">
      <label for="satuan" class="form-label">Satuan</label>
      <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan"
        value="{{ old('satuan', $item->satuan) }}">
      @error('satuan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="harga_satuan" class="form-label">Harga Satuan</label>
      <input type="text" class="form-control @error('harga_satuan') is-invalid @enderror" id="harga_satuan"
        name="harga_satuan" value="{{ old('harga_satuan', $item->harga_satuan) }}">
      @error('harga_satuan')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="volume" class="form-label">Volume Barang</label>
      <input type="number" class="form-control @error('volume') is-invalid @enderror" id="volume" name="volume"
        value="{{ old('volume', $item->volume) }}" step=".01">
      @error('volume')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select class="form-select" name="status">
        @foreach ($statuses as $status)
          @if (old('status', $item->status) == $status->id)
            <option value="{{ $status->id }}" selected>{{ $status->nama }}</option>
          @else
            <option value="{{ $status->id }}">{{ $status->nama }}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label for="gambar" class="form-label">Gambar</label>
      <input type="hidden" name="oldImage" value="{{ $item->gambar }}">
      @if ($item->gambar)
        <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-preview img-fluid d-block">
      @else
        <img class="img-preview img-fluid">
        <p>Belum ada gambar</p>
      @endif
      <input class="form-control @error('gambar') is-invalid @enderror" type="file" id="gambar" name="gambar"
        onchange="prevImg()">
      @error('gambar')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary">Edit</button>
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
