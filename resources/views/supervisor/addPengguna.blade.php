@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form method="POST" action="/dashboard/pengguna/tambahuser" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control rounded-top
                @error('nama') is-invalid @enderror" id="nama" placeholder="Nama"
                value="{{ old('nama') }}">
                
                @error('nama')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror
      </div>

      <div class="mb-3">
        <label for="nomor_telepon" class="form-label">No Telepon</label>
        <input type="text" name="nomor_telepon" class="form-control rounded-top
                @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" placeholder="Nomor Telepon" 
                value="{{ old('nomor_telepon') }}">
                
                @error('nomor_telepon')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label class="form-label">Role</label>
        <select class="form-select" name="role">
          <option selected>Pilih Role</option>
          <option value="0">Sales</option>
          <option value="1">Admin</option>
          <option value="2">Supervisor</option>
        </select>        
      </div>

      <div class="mb-3">
        <label for="foto_profil" class="form-label">Foto Profil</label>
        <img class="img-preview img-fluid mb-3 col-sm-5">
        <input class="form-control @error('foto_profil') is-invalid @enderror" type="file" id="foto_profil" 
        name="foto_profil" onchange="previewImage()">
            @error('foto_profil')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror   
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control rounded-top
                @error('email') is-invalid @enderror" id="email" placeholder="Email" 
                value="{{ old('email') }}">
                
                @error('email')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
        </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control rounded-top
                @error('password') is-invalid @enderror" id="password" placeholder="Password"  
                value="{{ old('password') }}">
                
                @error('password')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror            
      </div>

      
      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard/pengguna" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
<script src="{{ mix('js/main.js') }}"></script>

@endsection


