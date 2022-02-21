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
        <label for="alamat" class="form-label">Alamat</label>
        <input type="text" name="alamat" class="form-control rounded-top
                @error('alamat') is-invalid @enderror" id="alamat" placeholder="jl. xxxxx"
                value="{{ old('alamat') }}" >
                
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
                value="{{ old('tanggal_lahir') }}" >
                
                @error('tanggal_lahir')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>
      
      <div class="mb-3">
        <label for="agama" class="form-label">Agama</label>
        <select class="form-select" name="agama">
          <option selected>Pilih Agama</option>
          <option value="Islam">Islam</option>
          <option value="Kristen">Kristen</option>
          <option value="Katolik">Katolik</option>
          <option value="Hindu">Hindu</option>
          <option value="Buddha">Buddha</option>
          <option value="Kong Hu Cu">Kong Hu Cu</option>          
        </select>         
      </div>

      <div class="mb-3">
        <label for="nama_wali" class="form-label">Nama Wali</label>
        <input type="text" name="nama_wali" class="form-control rounded-top
                @error('nama_wali') is-invalid @enderror" id="nama_wali" placeholder="nama wali"
                value="{{ old('nama_wali') }}" >
                
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
                value="{{ old('nomer_telepon_wali') }}" >
                
                @error('nomer_telepon_wali')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
                @enderror        
      </div>

      <div class="mb-3">
        <label for="status_wali" class="form-label">Status Wali</label>
        <select class="form-select" name="status_wali">
          <option selected>Pilih Status Wali</option>
          <option value="Orang Tua">Orang Tua</option>
          <option value="Wali">Wali</option>                
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


