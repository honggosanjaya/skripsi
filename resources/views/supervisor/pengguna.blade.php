@extends('layouts/main')

@section('main_content')
  <div class="mt-3 search-box">
    <i class="bi bi-search"></i>
    <input type="text" class="form-control " placeholder="Cari Pengguna...">
  </div>

  <a href="/dashboard/pengguna/tambah" class="btn btn-primary my-4">Tambah User</a>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">id</th>
        <th scope="col">nama lengkap</th>
        <th scope="col">nomor telepon</th>
        <th scope="col">email</th>
        <th scope="col">foto</th>
        <th scope="col">status</th>
        <th scope="col">role</th>
        <th scope="col">aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>1</td>
        <td>Hendro Hanjaya</td>
        <td>0862729107</td>
        <td>hendro@kantor.co.id</td>
        <td>foto</td>
        <td>aktif</td>
        <td>sales</td>
        <td>
          <a href="/dashboard/pengguna/ubah" class="btn btn-warning">Ubah</a>
        </td>
      </tr>
    </tbody>
  </table>
@endsection
