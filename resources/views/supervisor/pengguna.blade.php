@extends('layouts/main')

@section('main_content')
  <div class="mt-3 search-box">
    <form method="GET" action="/dashboard/pengguna/cari">
      <i class="bi bi-search"></i>
      <input type="text" class="form-control w-75" name="cari" placeholder="Cari Pengguna..."
      value="{{ request('cari') }}">   
    </form>    
  </div>

  <a href="/dashboard/pengguna/tambah" class="btn btn-primary my-4">Tambah User</a>

  @if(session()->has('addDataSuccess'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('addDataSuccess') }}
  <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  @if(session()->has('updateDataSuccess'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('updateDataSuccess') }}
  <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  @if(session()->has('deleteDataSuccess'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
  {{ session('deleteDataSuccess') }}
  <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  @endif

  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">user_id</th>
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
      @foreach ($users as $user)
      <tr>
        <th scope="row">{{ $loop->iteration }}</th>
        <td>{{ $user->id }}</td>
        <td>{{ $user->nama }}</td>
        <td>{{ $user->nomor_telepon }}</td>
        <td>{{ $user->email }}</td>
        <td>foto</td>
        
        @if ($user->status === "1")
        <td class="text-success">Aktif</td>
        @else
        <td class="text-danger">Tidak Aktif</td>
        @endif
        
        @if ($user->role === "1")
        <td>Admin</td>
        @elseif ($user->role === "2")
        <td>Supervisor</td>
        @else
        <td>Sales</td>
        @endif    
            
        <td>
          <a href="/dashboard/pengguna/ubah/{{ $user->id }}" class="btn btn-warning">Ubah</a>
        </td>
        <td>
          <a href="/dashboard/pengguna/hapus/{{ $user->id }}" class="btn btn-danger">Hapus</a>
        </td>
      </tr>
      @endforeach
      
    </tbody>
  </table>
@endsection
