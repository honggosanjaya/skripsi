@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/owner.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/owner">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Supervisor</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    @if (session()->has('pesanSukses'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('pesanSukses') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <div class="justify-content-between d-sm-flex">
      <form method="GET" action="/owner/datasupervisor/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Supervisor..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">
            <span class="iconify me-2" data-icon="fe:search"></span>Cari
          </button>
        </div>
      </form>

      <a href="/owner/datasupervisor/create" class="btn btn-primary mt-3 mt-sm-0 ms-0 ms-sm-3">
        <span class="iconify fs-4 me-1" data-icon="dashicons:database-add"></span> Tambah Supervisor
      </a>
    </div>

    <div class="table-responsive mt-4">
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
              <th scope="row" class="text-center">
                {{ ($supervisors->currentPage() - 1) * $supervisors->perPage() + $loop->iteration }}
              </th>
              <td class="text-center">
                @if ($supervisor->foto_profil)
                  <img src="{{ asset('storage/staff/' . $supervisor->foto_profil) }}" class="img-fluid" width="40">
                @else
                  <img src="{{ asset('images/default_fotoprofil.png') }}" class="img-fluid" width="40">
                @endif
              </td>
              <td>
                <a href="/owner/datasupervisor/{{ $supervisor->id }}"
                  class="text-decoration-none">{{ $supervisor->nama ?? null }}</a>
              </td>
              <td>{{ $supervisor->email ?? null }}</td>
              <td>{{ $supervisor->telepon ?? null }}</td>
              <td class="text-center text-capitalize">{{ $supervisor->status_enum == '1' ? 'Active' : 'Inactive' }}</td>
              <td class="text-center">
                <a href="/owner/datasupervisor/edit/{{ $supervisor->id }}" class="btn btn-sm btn-warning mb-2">
                  <span class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>Edit
                </a>

                <form action="/owner/datasupervisor/ubahstatus/{{ $supervisor->id }}?route=editstatussupervisor"
                  method="POST">
                  @csrf
                  <button type="submit"
                    class="btn btn-sm {{ $supervisor->status_enum === '1' ? 'btn-danger' : 'btn-success' }}">
                    {{ $supervisor->status_enum === '1' ? 'Nonaktifkan' : 'Aktifkan' }}
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $supervisors->links() }}
    </div>
  </div>
@endsection
