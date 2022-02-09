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

      <div class="mb-3">
        <label class="form-label">Status Aktif</label>
        <select class="form-select">
          <option selected>Pilih Status</option>
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Tidak Aktif</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Role</label>
        <select class="form-select">
          <option selected>Pilih Role</option>
          <option value="sales">Sales</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard/pengguna" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
