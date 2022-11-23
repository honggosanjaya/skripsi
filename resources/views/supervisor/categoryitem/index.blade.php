@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Category Item</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <a href="/supervisor/category/tambah" class="btn btn-primary"><span class="iconify fs-4 me-1"
        data-icon="dashicons:database-add"></span>Tambah Category Item</a>

    <div class="table-responsive">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Nama Category Item</th>
            <th scope="col" class="text-center">Keterangan</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
            <tr>
              <th scope="row" class="text-center">{{ $loop->iteration }}</th>
              <td>{{ $category->nama ?? null }}</td>
              <td>{{ $category->keterangan ?? null }}</td>
              <td class="text-center">
                <a href="/supervisor/category/ubah/{{ $category->id ?? null }}" class="btn btn-warning"><span
                    class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span>
                  Ubah</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
