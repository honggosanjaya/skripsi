@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('main_content')
  {{-- ide: masukkan password lama, sistem cek kesesuaian password --}}
  {{-- sebelum password lama sesuai, form input password baru dan konfirmasi password baru disabled --}}
  <div class="p-4">
    @if(session()->has('passwordError'))
    <div id="hideMeAfter3Seconds">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('passwordError') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <form method="POST" action="/administrasi/profil/check/{{ auth()->user()->id_users }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">User ID</label>
        <input type="text" class="form-control" value="{{ auth()->user()->id_users }}" readonly>    
      </div>
      <div class="mb-3">
        <label for="passwordLama" class="form-label">Password Lama</label>
        <input id="passwordLama" type="password" class="form-control @error('passwordLama') is-invalid @enderror" 
        name="passwordLama" placeholder="Masukkan Password Lama">
        @error('passwordLama')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/administrasi" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
