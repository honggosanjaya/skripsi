<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Jenis Customer</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    @if (session()->has('addJenisSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('addJenisSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif
    @if (session()->has('updateJenisSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('updateJenisSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
      <form method="GET" action="/supervisor/jenis/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Jenis Customer..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">
            <span class="iconify me-2" data-icon="fe:search"></span>Cari
          </button>
        </div>
      </form>
      <a href="/supervisor/jenis/tambah" class="btn btn-primary my-3 py-2"><span class="iconify fs-4 me-1"
          data-icon="dashicons:database-add"></span>Tambah Jenis</a>
    </div>

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama Jenis Customer</th>
            <th scope="col" class="text-center">Diskon (%)</th>
            <th scope="col" class="text-center">Keterangan</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($jenises as $jenis)
            <tr>
              <th scope="row" class="text-center">
                {{ ($jenises->currentPage() - 1) * $jenises->perPage() + $loop->iteration }}</th>
              <td>{{ $jenis->nama }}</td>
              <td class="text-center">{{ $jenis->diskon }}</td>
              <td>{{ $jenis->keterangan }}</td>
              <td class="text-center">
                <a href="/supervisor/jenis/ubah/{{ $jenis->id }}" class="btn btn-warning"><span
                    class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>
                  Ubah</a>
              </td>

            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex flex-row mt-4">
      {{ $jenises->links() }}
    </div>
  </div>
@endsection
