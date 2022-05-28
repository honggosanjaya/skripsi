<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Profil</li>
</ol>
@endsection
@section('main_content')

<div class="container">
    <a class="btn btn-primary mt-4" href="/administrasi">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="row my-4">
        <div class="col-3">
            <h3>Profil {{ $data->linkStaffRole->nama }}</h3>
        </div>        
    </div>
    <div class="row my-4">
        <div class="col-3">
            <h5>Nama :</h5>
        </div>
        <div class="col-5">
            {{ $data->nama }}
        </div>
    </div>
    <div class="row my-4">
        <div class="col-3">
            <h5>Email :</h5>
        </div>
        <div class="col-5">
            {{ $data->email }}
        </div>
    </div>
    <div class="row my-4">
        <div class="col-3">
            <h5>Posisi :</h5>
        </div>
        <div class="col-5">
            {{ $data->linkStaffRole->nama }}
        </div>
    </div>
    <div class="row my-4">
        <div class="col-3">
           <h5>Nomor Telepon :</h5>
        </div>
        <div class="col-5">
            {{ $data->telepon }}
        </div>
    </div>
    <div class="row my-4">
        <div class="col-3">
            <h5>Foto Profil :</h5>
        </div>
        <div class="col-5">
            <img src="{{ asset('storage/staff/'.$data->foto_profil) }}"
            class="rounded" width="250px" height="250px">
        </div>
    </div>
    
    <a class="btn btn-warning mt-4">
        Ubah Password
    </a>
</div>

@endsection