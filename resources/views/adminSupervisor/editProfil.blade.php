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
      <div class="mb-3">
        <label for="alamat" class="form-label">Alamat</label>
        <input type="text" name="alamat" class="form-control rounded-top
                @error('alamat') is-invalid @enderror" id="alamat" placeholder="jl. xxxxx"
                value="{{ old('alamat', $user->alamat) }}" >
                
                @error('alamat')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
        <input type="text" name="tanggal_lahir" class="form-control rounded-top datetimepicker
                @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" placeholder="tanggal lahir"
                value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}" >
                
                @error('tanggal_lahir')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>
      
      <div class="mb-3">
        <label for="agama" class="form-label">Agama</label>
        <input type="text" name="agama" class="form-control rounded-top
                @error('agama') is-invalid @enderror" id="agama" placeholder="agama"
                value="{{ old('agama', $user->agama) }}" >
                
                @error('agama')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label for="nama_wali" class="form-label">Nama Wali</label>
        <input type="text" name="nama_wali" class="form-control rounded-top
                @error('nama_wali') is-invalid @enderror" id="nama_wali" placeholder="nama wali"
                value="{{ old('nama_wali', $user->nama_wali) }}" >
                
                @error('nama_wali')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label for="nomer_telepon_wali" class="form-label">Nomer Telepon Wali</label>
        <input type="text" name="nomer_telepon_wali" class="form-control rounded-top
                @error('nomer_telepon_wali') is-invalid @enderror" id="nomer_telepon_wali" placeholder="08xxxxxxxxx"
                value="{{ old('nomer_telepon_wali', $user->nomer_telepon_wali) }}" >
                
                @error('nomer_telepon_wali')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label for="status_wali" class="form-label">Status Wali</label>
        <input type="text" name="status_wali" class="form-control rounded-top
                @error('status_wali') is-invalid @enderror" id="status_wali" placeholder="Status Wali"
                value="{{ old('status_wali', $user->status_wali) }}" >
                
                @error('status_wali')
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
