@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Staf</li>
  </ol>
@endsection
@section('main_content')
  <div class="pt-4 px-5">
    @if (session()->has('pesanSukses'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('pesanSukses') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <a href="/supervisor/datastaf/create" class="btn btn-primary">
      <span class="iconify fs-4 me-1" data-icon="dashicons:database-add"></span> Tambah Staf
    </a>

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Foto</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">Email</th>
            <th scope="col" class="text-center">Telepon</th>
            <th scope="col" class="text-center">Role</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($stafs as $staf)
            <tr>
              <th scope="row" class="text-center">
                {{ ($stafs->currentPage() - 1) * $stafs->perPage() + $loop->iteration }}</th>
              <td class="text-center">
                @if ($staf->foto_profil)
                  <img src="{{ asset('storage/staff/' . $staf->foto_profil) }}" class="img-fluid" width="40">
                @else
                  <img src="{{ asset('images/default_fotoprofil.png') }}" class="img-fluid" width="40">
                @endif
              </td>
              <td>{{ $staf->nama ?? null }}</td>
              <td>{{ $staf->email ?? null }}</td>
              <td>{{ $staf->telepon ?? null }}</td>
              <td class="text-capitalize">{{ $staf->linkStaffRole->nama ?? null }}</td>
              <td class="text-capitalize">{{ $staf->status_enum == '1' ? 'Active' : 'Inactive' }}</td>
              <td>
                <div class="d-flex justify-content-center">
                  <a href="/supervisor/datastaf/{{ $staf->id }}/edit" class="btn btn-sm btn-warning ms-3 me-1">
                    <span class="iconify fs-5" data-icon="eva:edit-2-fill"></span> Edit
                  </a>

                  <form action="/supervisor/datastaf/ubahstatus/{{ $staf->id }}" method="POST">
                    @csrf
                    <button type="submit"
                      class="btn btn-sm {{ $staf->status_enum === '1' ? 'btn-danger' : 'btn-success' }}">
                      @if ($staf->status_enum === '1')
                        <span class="iconify" data-icon="material-symbols:cancel-outline"></span>
                      @else
                        <span class="iconify" data-icon="akar-icons:double-check"></span>
                      @endif
                      {{ $staf->status_enum === '1' ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{ $stafs->links() }}
    </div>
  </div>
@endsection
