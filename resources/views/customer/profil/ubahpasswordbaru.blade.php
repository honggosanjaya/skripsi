@extends('customer.layouts.customerLayouts')

@section('content')
  {{-- ide: masukkan password lama, sistem cek kesesuaian password --}}
  {{-- sebelum password lama sesuai, form input password baru dan konfirmasi password baru disabled --}}
  <div class="p-4">
    @if(session()->has('passwordSuccess'))
    <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('passwordSuccess') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session()->has('matchError'))
    <div id="hideMeAfter3Seconds">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('matchError') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    <form method="POST" action="/customer/profil/gantipassword/{{ auth()->user()->id }}">
      @csrf
      <div class="mb-3">
        <label class="form-label" hidden>User ID</label>
        <input type="text" class="form-control" value="{{ auth()->user()->id }}" readonly hidden>    
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
      <a href="/customer/profil" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
