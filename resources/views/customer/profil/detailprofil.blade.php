@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer/profil">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Detail Profil</h1>
    </div>
  </header>
@endsection

@section('content')
  <div class="mt-4">
    @if (session()->has('passwordSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('passwordSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <h1 class="fs-6 fw-bold">Foto Tempat Usaha</h1>
    @if ($data->foto != null)
      <img src="{{ asset('storage/customer/' . $data->foto_profil) }}" class="img-fluid d-block mx-auto foto_usaha">
    @else
      <img src="{{ asset('storage/customer/1652867378.png') }}" class="img-fluid d-block mx-auto foto_usaha">
      {{-- <img src="{{ asset('images/default_fotoprofil.png') }}" class="img-fluid d-block mx-auto foto_usaha"> --}}
    @endif

    <div class="info-list mt-3 py-3">
      <span><b>Nama</b>{{ $data->nama }}</span> <br>
      <span><b>Email</b>{{ $data->email }}</span> <br>
      <span><b>Alamat Lengkap</b>{{ $data->full_alamat }}</span> <br>
      <span><b>Nomor Telepon</b>{{ $data->telepon }}</span>
    </div>
  </div>


  <a class="btn btn-outline-primary w-100 mt-5" href="/customer/profil/ubahpassword">
    Ubah Password
  </a>

  <small class="text-danger text-center d-block mt-5"><span class="fw-bold">NB:</span> Apabila Terdapat Perubahan Data
    Sampaikan Pada
    Sales</small>
@endsection
