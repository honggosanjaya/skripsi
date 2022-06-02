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
  <div class="container">
    <div class="row">
      <div class="col-5">
        <div class="mt-3 search-box">
          <form method="GET" action="/supervisor/wilayah/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Wilayah..."
                value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-3">
        <a href="/supervisor/wilayah/tambah" class="btn btn-primary my-3 py-2">Tambah Wilayah</a>
      </div>
      <div class="col-2">
        <a href="/supervisor/wilayah/lihat" class="btn btn-primary my-3 py-2">Lihat Wilayah</a>
      </div>
    </div>
  </div>


  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama Wilayah</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($districts as $district)
        <tr>
          <td>{{ ($districts->currentPage() - 1) * $districts->perPage() + $loop->iteration }}</td>
          <td>{{ $district->nama }}</td>
          <td>
            <a href="/supervisor/wilayah/ubah/{{ $district->id }}" class="btn btn-warning">Ubah</a>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex flex-row mt-4">
    {{ $districts->links() }}
  </div>
@endsection
