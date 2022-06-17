@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/owner">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Data Supervisor</li>
</ol>
@endsection
@section('main_content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    </div>
  @endif

  <div class="row mt-4">
    <div class="col-5">
      <div class="mt-3 search-box">
        <form method="GET" action="/owner/datasupervisor/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Supervisor..."
              value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">Cari</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-5">
      <a href="/owner/datasupervisor/create" class="btn btn-primary">
        Tambah Supervisor
      </a>
    </div>
  </div>

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Foto</th>
          <th scope="col" class="text-center">Nama</th>
          <th scope="col" class="text-center">Email</th>
          <th scope="col" class="text-center">Telepon</th>
          <th scope="col" class="text-center">Status</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($supervisors as $supervisor)
          <tr>
            <th scope="row">{{ ($supervisors->currentPage() - 1) * $supervisors->perPage() + $loop->iteration }}</th>
            <td class="text-center">
              @if ($supervisor->foto_profil)
                <img src="{{ asset('storage/staff/' . $supervisor->foto_profil) }}" class="img-fluid" width="40">
              @else
                <img src="{{ asset('images/default_fotoprofil.png') }}" class="img-fluid" width="40">
              @endif
            </td>
            <td>{{ $supervisor->nama ?? null }}</td>
            <td>{{ $supervisor->email ?? null }}</td>
            <td>{{ $supervisor->telepon ?? null }}</td>
            <td>{{ $supervisor->linkStatus->nama ?? null }}</td>
            <td class="text-center">
              <a href="/owner/datasupervisor/edit/{{ $supervisor->id }}" class="btn btn-sm btn-primary mb-2">
                Edit
              </a>

              <form action="/owner/datasupervisor/ubahstatus/{{ $supervisor->id }}?route=editstatussupervisor"
                method="POST">
                @csrf
                <button type="submit"
                  class="btn btn-sm {{ $supervisor->linkStatus->nama === 'active' ? 'btn-danger' : 'btn-success' }}">
                  {{ $supervisor->linkStatus->nama === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $supervisors->links() }}
  </div>
@endsection
