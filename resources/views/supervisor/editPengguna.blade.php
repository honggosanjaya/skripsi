@extends('layouts/main')

@section('main_content')
  <div class="p-4">
    <form method="POST" action="/dashboard/pengguna/ubahuser/{{ $user->id }}">
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
        <label for="nomor_telepon" class="form-label">No Telepon</label>
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
        <label class="form-label">Status</label>
        
        <select class="form-select" name="status">
          @if($user->status === "1")
            <option value="{{ $user->status }}" selected>Aktif</option>
            <option value="0">Tidak Aktif</option>            
          @else
          <option value="{{ $user->status }}" selected>Tidak Aktif</option>
          <option value="1">Aktif</option> 
          @endif                
        </select>   
      </div>

      <div class="mb-3">
        <label class="form-label">Role</label>
        
        <select class="form-select" name="role">
          @if($user->role === "1")
            <option value="{{ $user->role }}" selected>Admin</option>
            <option value="0">Sales</option>
            <option value="2">Supervisor</option>
          @elseif ($user->role === "2") 
            <option value="{{ $user->role }}" selected>Supervisor</option>
            <option value="0">Sales</option>
            <option value="1">Admin</option>
          @else
            <option value="{{ $user->role }}" selected>Sales</option>            
            <option value="1">Admin</option>
            <option value="2">Supervisor</option>
          @endif                
        </select>   
      </div>


      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control rounded-top
                @error('email') is-invalid @enderror" id="email" placeholder="Email" 
                value="{{ old('email', $user->email) }}" readonly>
                
                @error('email')
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
