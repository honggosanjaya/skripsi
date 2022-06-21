<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')

@section('main_content')
  <div class="mt-3 search-box">
    <form method="GET" action="/dashboard/pengguna/cari">
      <div class="input-group w-50">
        <input type="text" class="form-control" name="cari" placeholder="Cari Pengguna..."
          value="{{ request('cari') }}">
        <button type="submit" class="btn btn-primary">
          <span class="iconify me-2" data-icon="fe:search"></span>Cari
        </button>
      </div>

    </form>
  </div>

  <a href="/dashboard/pengguna/tambah" class="btn btn-primary my-4">Tambah User</a>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">user_id</th>
        <th scope="col">nama lengkap</th>
        <th scope="col">nomor telepon</th>
        <th scope="col">email</th>
        <th scope="col">profil</th>
        <th scope="col">status</th>
        <th scope="col">role</th>
        <th scope="col">aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $user->id }}</td>
          <td>{{ $user->nama }}</td>
          <td>{{ $user->nomor_telepon }}</td>
          <td>{{ $user->email }}</td>
          <td><img src="{{ asset('storage/' . $user->foto_profil) }}" class="img-preview img-fluid" width="50px"
              height="50px">
          </td>

          @if ($user->status === '1')
            <td class="text-success">Aktif</td>
          @else
            <td class="text-danger">Tidak Aktif</td>
          @endif

          @if ($user->role === '1')
            <td>Admin</td>
          @elseif ($user->role === '2')
            <td>Supervisor</td>
          @else
            <td>Sales</td>
          @endif

          <td>
            <a href="/dashboard/pengguna/ubah/{{ $user->id }}" class="btn btn-warning">Ubah</a>
          </td>

        </tr>
      @endforeach

    </tbody>
  </table>
@endsection
