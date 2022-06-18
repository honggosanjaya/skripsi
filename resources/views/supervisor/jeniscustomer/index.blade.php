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
  <div class="container">
    <div class="row">
      <div class="col-5">
        <div class="mt-3 search-box">
          <form method="GET" action="/supervisor/jenis/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Jenis Customer..."
                value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>
            </div>

          </form>

        </div>
      </div>
      <div class="col-4">
        <a href="/supervisor/jenis/tambah" class="btn btn-primary my-3 py-2">Tambah Jenis</a>
      </div>
    </div>
  </div>


  <table class="table">
    <thead>
      <tr>
        <th scope="col">No</th>
        <th scope="col">Nama Jenis Customer</th>
        <th scope="col">Diskon (%)</th>
        <th scope="col">Keterangan</th>
        <th scope="col">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($jenises as $jenis)
        <tr>
          <th scope="row">{{ ($jenises->currentPage() - 1) * $jenises->perPage() + $loop->iteration }}</th>
          <td>{{ $jenis->nama }}</td>
          <td>{{ $jenis->diskon }}</td>
          <td>{{ $jenis->keterangan }}</td>
          <td>
            <a href="/supervisor/jenis/ubah/{{ $jenis->id }}" class="btn btn-warning">Ubah</a>
          </td>

        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="d-flex flex-row mt-4">
    {{ $jenises->links() }}
  </div>
@endsection
