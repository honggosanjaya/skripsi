@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/datastaf">Data Staff</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <h3 class="mb-5">Detail Staff</h3>
    <div class="informasi-list mb_big">
      <span><b>Nama</b>{{ $staff->nama ?? null }}</span>
      <span><b>Email</b>{{ $staff->email ?? null }}</span>
      <span><b>Telepon</b>{{ $staff->telepon ?? null }}</span>
      <span><b>Role</b>{{ $staff->linkStaffRole->nama ?? null }}</span>
      @if ($staff->status_enum ?? null)
        <span><b>Status</b>{{ $staff->status_enum == '1' ? 'Active' : 'Inactive' }}</span>
      @endif
      <span><b>Foto Profil</b>
        @if ($staff->foto_profil ?? null)
          <img src="{{ asset('storage/staff/' . $staff->foto_profil) }}" class="img-preview img-fluid">
        @else
          Tidak ada foto
        @endif
      </span>
    </div>
  </div>
@endsection
