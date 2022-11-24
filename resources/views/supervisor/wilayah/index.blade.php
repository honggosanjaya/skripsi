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
    @if (session()->has('succesMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('succesMessage') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <a href="/supervisor/wilayah/tambah" class="btn btn-primary me-2"><span class="iconify fs-5 me-1"
        data-icon="dashicons:database-add"></span> Tambah Wilayah</a>
    <a href="/supervisor/wilayah/lihat" class="btn btn-primary"><span class="iconify fs-5 me-1"
        data-icon="carbon:data-view"></span> Lihat Wilayah</a>

    <div class="table-responsive">
      <table class="table table-hover table-sm" id="table">
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
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td class="text-capitalize">{{ $district->nama ?? null }}</td>
              <td class="text-center">
                <a href="/supervisor/wilayah/ubah/{{ $district->id ?? null }}" class="btn btn-warning"><span
                    class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>Ubah</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
