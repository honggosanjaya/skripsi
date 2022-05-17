@extends('layouts/main')

@section('main_content')
  {{-- ide: masukkan password lama, sistem cek kesesuaian password --}}
  {{-- sebelum password lama sesuai, form input password baru dan konfirmasi password baru disabled --}}
  <div class="p-4">
    @if(session()->has('passwordSuccess'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('passwordSuccess') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session()->has('matchError'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('matchError') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="/dashboard/profil/gantipassword/{{ $user->id }}">
      @csrf
      <div class="mb-3">
        <label class="form-label">User ID</label>
        <input type="text" class="form-control" value="{{ $user->id }}" readonly>    
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password Baru</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
        name="password" placeholder="Masukkan Password Baru">
        @error('password')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="konfirmasiPasswordBaru" class="form-label">Konfirmasi Password Baru</label>
        <input id="konfirmasiPasswordBaru" type="password" class="form-control @error('konfirmasiPasswordBaru') is-invalid @enderror" 
        name="konfirmasiPasswordBaru" placeholder="Masukkan Konfirmasi Password Baru">
        @error('konfirmasiPasswordBaru')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
