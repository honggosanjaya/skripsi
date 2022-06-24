<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/owner.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/owner">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Profil</li>
  </ol>
@endsection

@section('main_content')
  <div class="px-5 pt-4">
    <div class="row justify-content-center">
      <div class="col-8">
        @if ($data->foto_profil)
          <img src="{{ asset('storage/staff/' . $data->foto_profil) }}" class="profil_picture">
        @else
          <img class="profil_picture" src="{{ asset('images/default_fotoprofil.png') }}">
        @endif

        <div class="informasi-list mb_big">
          <span><b>Nama</b>{{ $data->nama }}</span>
          <span><b>Email</b>{{ $data->email }}</span>
          <span><b>Role</b>{{ $data->linkStaffRole->nama }}</span>
          <span><b>No. Telepon</b>{{ $data->telepon }}</span>
        </div>

        <div class="row justify-content-center mt-5">
          <div class="col-6">
            <a class="btn btn-outline-primary mt-4 w-100" href="/owner/profil/ubahpassword">
              Ubah Password
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
