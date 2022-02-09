@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form>
      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">No Telepon</label>
        <input type="text" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control">
      </div>

      {{-- tambahkan preview image --}}
      <div class="mb-3">
        <label for="image" class="form-label">Foto Profil</label>
        <img class="img-preview img-fluid">
        <input class="form-control" type="file" id="fotoProfil">
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
