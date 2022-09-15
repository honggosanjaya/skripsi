@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Kas</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('pesanSukses') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4" id="sistem-kas">
    <h1 class="fs-5">Pilih Kas</h1>
    @foreach ($bukuKas as $buku)
      <a href="/administrasi/kas/{{ $buku->id }}"
        class="btn btn-{{ $buku->account % 5 == 0 ? 'primary' : ($buku->account % 5 == 1 ? 'warning' : ($buku->account % 5 == 2 ? 'success' : ($buku->account % 5 == 3 ? 'danger' : 'info'))) }}">
        <span class="iconify fs-3 me-2" data-icon="akar-icons:book-open"></span>
        {{ $buku->nama }}
      </a>
    @endforeach
  </div>
@endsection
