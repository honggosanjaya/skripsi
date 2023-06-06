@extends('layouts.mainreact')

@section('main_content')
  <div class="page_container pt-4">
    @if (auth()->user()->linkStaff->foto_profil ?? null)
      <img src="{{ asset('storage/staff/' . auth()->user()->linkStaff->foto_profil) }}" class="profil-saya_foto">
    @else
      <img src="{{ asset('images/default_fotoprofil.png') }}" class="profil-saya_foto">
    @endif

    <ul class="info-list mt-4">
      <li><b>Nama</b>{{ $data->nama ?? null }}</li>
      <li><b>Email</b>{{ $data->email ?? null }}</li>
      <li><b>Telepon</b>{{ $data->telepon ?? null }}</li>
      @if ($data->status_enum ?? null)
        <li><b>Status</b>{{ $data->status_enum == '1' ? 'Aktif' : 'Tidak aktif' }}</li>
      @endif
    </ul>

    @if ($isSalesman ?? null)
      <a href='/salesman/changepassword' class="btn btn-outline-primary w-100 mt-4">Ubah Password</a>
    @else
      <a href='/shipper/changepassword' class="btn btn-outline-primary w-100 mt-4">Ubah Password</a>
    @endif
  </div>
@endsection
