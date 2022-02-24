@extends('layouts/main')

@section('main_content')
  @if (session()->has('successMessage'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('successMessage') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  @foreach ($items as $item)
    <div class="card product_wrapper mt-4">
      <h3>Nama Barang</h3>
      <p>{{ $item->nama_barang ?? null }}</p>

      <h3>Stok Saat Ini</h3>
      <p>{{ $item->stok ?? null }} {{ $item->satuan ?? null }}</p>

      <h3>Harga</h3>
      <p>{{ $item->harga_satuan ?? null }}</p>


      <form method="POST" action="/dashboard/produk/stok/{{ $item->id }}">
        @method('put')
        @csrf

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label class="form-label">Perubahan Stok</label>
              <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
                value="{{ old('stok') }}">
              @error('stok')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-check form-switch">
              <input type="checkbox" class="form-check-input" role="switch" id="aksi" name="aksi" value="dikurangi">
              <label class="form-check-label" for="aksi">Stok Dikurangi</label>
            </div>
          </div>

          <div class="col">
            <div class="mb-3">
              <label class="form-label">Satuan Stok</label>
              <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan" name="satuan"
                value="{{ old('satuan', $item->satuan) }}">
              @error('satuan')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-danger my-3">ubah</button>
      </form>

    </div>
  @endforeach
@endsection
