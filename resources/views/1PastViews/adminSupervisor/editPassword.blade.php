@extends('layouts/main')

@section('main_content')
  {{-- ide: masukkan password lama, sistem cek kesesuaian password --}}
  {{-- sebelum password lama sesuai, form input password baru dan konfirmasi password baru disabled --}}
  <div class="p-4">
    <form>
      <div class="mb-3">
        <label class="form-label">Password Lama</label>
        <input type="password" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
