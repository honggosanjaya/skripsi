@extends('layouts/main')

@section('main_content')
  @if (session()->has('successMessage'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('successMessage') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif


  <div class="stok-page">
    @foreach ($items as $item)
      <div class="card product_wrapper mt-4">
        <h3>Nama Barang</h3>
        <p>{{ $item->nama_barang ?? null }}</p>

        <h3>Stok Saat Ini</h3>
        <p>{{ $item->stok ?? null }} {{ $item->satuan ?? null }}</p>

        <form method="POST" action="/dashboard/produk/stok/{{ $item->id }}">
          @method('put')
          @csrf

          <div class="mb-3 w-50">
            <label class="form-label">Perubahan Stok</label>
            <div class="position-relative">
              <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok"
                value="{{ old('stok') }}">
              @error('stok')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror

              <span class="satuan">{{ $item->satuan ?? null }}</span>
            </div>
          </div>

          <div class="form-check form-switch">
            <input type="checkbox" class="form-check-input" role="switch" id="aksi" name="aksi" value="dikurangi">
            <label class="form-check-label" for="aksi">Stok Dikurangi</label>
          </div>
        </form>
      </div>
    @endforeach

    <button class="btn btn-danger ubahStok-btn">ubah</button>
  </div>
@endsection
