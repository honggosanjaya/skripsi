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
    @if (session()->has('addCategorySuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('addCategorySuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif
    @if (session()->has('updateCategorySuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('updateCategorySuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
      <form method="GET" action="/supervisor/category/cari">
        <div class="input-group">
          <input type="text" class="form-control" name="cari" placeholder="Cari Category..."
            value="{{ request('cari') }}">
          <button type="submit" class="btn btn-primary">
            <span class="iconify" data-icon="fe:search"></span>
          </button>
        </div>
      </form>
      <a href="/supervisor/category/tambah" class="btn btn-primary my-3 py-2"><span class="iconify fs-4 me-1"
          data-icon="dashicons:database-add"></span>Tambah Category Item</a>
    </div>

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
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
              <th scope="row" class="text-center">
                {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
              </th>
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

    <div class="d-flex flex-row mt-4">
      {{ $categories->links() }}
    </div>
  </div>
@endsection
