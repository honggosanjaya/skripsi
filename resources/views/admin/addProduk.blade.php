@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form>
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Satuan Stok</label>
        <input type="text" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Harga Satuan</label>
        <input type="text" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard/produk" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
