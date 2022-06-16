@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Data Staf</li>
</ol>
@endsection
@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="row mt-4">
    <div class="col-5">

    </div>
    <div class="col-5">
      <a href="/supervisor/datastaf/create" class="btn btn-primary">
        Tambah Staf
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
          <th scope="col" class="text-center">Role</th>
          <th scope="col" class="text-center">Status</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($stafs as $staf)
          {{-- <tr onclick="window.location='/administrasi/datacustomer/{{ $customer->id }}';"> --}}
          <tr>
            <th scope="row">{{ ($stafs->currentPage() - 1) * $stafs->perPage() + $loop->iteration }}</th>
            <td class="text-center">
              @if ($staf->foto_profil)
                <img src="{{ asset('storage/staff/' . $staf->foto_profil) }}" class="img-fluid" width="40">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid" width="40">
              @endif
            </td>
            <td>{{ $staf->nama ?? null }}</td>
            <td>{{ $staf->email ?? null }}</td>
            <td>{{ $staf->telepon ?? null }}</td>
            <td>{{ $staf->linkStaffRole->nama ?? null }}</td>
            <td>{{ $staf->linkStatus->nama ?? null }}</td>
            <td class="text-center">
              <a href="/supervisor/datastaf/{{ $staf->id }}/edit" class="btn btn-sm btn-primary mb-2">
                Edit
              </a>

              <form action="/supervisor/datastaf/ubahstatus/{{ $staf->id }}" method="POST">
                @csrf
                <button type="submit"
                  class="btn btn-sm {{ $staf->linkStatus->nama === 'active' ? 'btn-danger' : 'btn-success' }}">
                  {{ $staf->linkStatus->nama === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $stafs->links() }}
  </div>
@endsection
