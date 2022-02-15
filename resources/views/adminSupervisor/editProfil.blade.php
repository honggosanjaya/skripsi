@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form method="POST" action="/dashboard/profil/ubahprofil/{{ $user->id }}" enctype="multipart/form-data">
      @method('put')
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control rounded-top
                @error('nama') is-invalid @enderror" id="nama" placeholder="Nama"
                value="{{ old('nama', $user->nama) }}">
                
                @error('nama')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
      </div>

      <div class="mb-3">
        <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
        <input type="text" name="nomor_telepon" class="form-control rounded-top
                @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" placeholder="Nomor Telepon"
                value="{{ old('nomor_telepon', $user->nomor_telepon) }}" readonly>
                
                @error('nomor_telepon')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" class="form-control rounded-top
                @error('email') is-invalid @enderror" id="email" placeholder="Email"
                value="{{ old('email', $user->email) }}" readonly>
                
                @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      {{-- tambahkan preview image --}}
      <div class="d-flex flex-column mb-3">
        <label for="foto_profil" class="form-label">Foto Profil</label>
        <input type="hidden" name="oldProfil" value="{{ $user->foto_profil }}">

        @if ($user->foto_profil)
        <img class="img-preview img-fluid mb-4" src="{{ asset('storage/'.$user->foto_profil) }}"
        width="150px" height="150px">
        @else
        <img class="img-preview img-fluid">
        @endif
       
        <input class="form-control @error('foto_profil') is-invalid @enderror" type="file" id="foto_profil" 
        name="foto_profil" onchange="previewImage()">
            @error('foto_profil')
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
