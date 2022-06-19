<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Wilayah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    @if (session()->has('addWilayahSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('addWilayahSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif
    @if (session()->has('updateWilayahSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('updateWilayahSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
      <form method="GET" action="/supervisor/wilayah/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Wilayah..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">Cari</button>
        </div>
      </form>
      <div>
        <a href="/supervisor/wilayah/tambah" class="btn btn-primary my-3 py-2"><span class="iconify fs-5 me-1"
            data-icon="dashicons:database-add"></span> Tambah Wilayah</a>
        <a href="/supervisor/wilayah/lihat" class="btn btn-primary my-3 py-2"><span class="iconify fs-5 me-1"
            data-icon="carbon:data-view"></span> Lihat Wilayah</a>
      </div>
    </div>

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama Wilayah</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($districts as $district)
            <tr>
              <th scope="row" class="text-center">
                {{ ($districts->currentPage() - 1) * $districts->perPage() + $loop->iteration }}</th>
              <td class="text-capitalize">{{ $district->nama }}</td>
              <td class="text-center">
                <a href="/supervisor/wilayah/ubah/{{ $district->id }}" class="btn btn-warning"><span
                    class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>Ubah</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex flex-row mt-4">
      {{ $districts->links() }}
    </div>
  </div>
@endsection
