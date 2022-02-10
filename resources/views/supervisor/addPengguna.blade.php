@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form method="POST" action="/dashboard/pengguna/tambahuser">
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control rounded-top
                @error('nama') is-invalid @enderror" id="nama" placeholder="Nama" required 
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
                @error('nomor_telepon') is-invalid @enderror" id="nomor_telepon" placeholder="Nomor Telepon" required 
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
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control rounded-top
                @error('email') is-invalid @enderror" id="email" placeholder="Email" required 
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
                @error('password') is-invalid @enderror" id="password" placeholder="Password" required 
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
@endsection
