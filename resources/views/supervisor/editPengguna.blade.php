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
                value="{{ old('nomor_telepon', $user->nomor_telepon) }}">
                
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
          <select class="form-select" name="agama">
            @if($user->agama === "Islam")
              <option value="{{ $user->agama }}" selected>Islam</option>
              <option value="Kristen">Kristen</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Buddha">Buddha</option>
              <option value="Kong Hu Cu">Kong Hu Cu</option>
            @elseif ($user->agama === "Kristen") 
              <option value="{{ $user->agama }}" selected>Kristen</option>
              <option value="Islam">Islam</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Buddha">Buddha</option>
              <option value="Kong Hu Cu">Kong Hu Cu</option>
            @elseif ($user->agama === "Katolik") 
              <option value="{{ $user->agama }}" selected>Katolik</option>
              <option value="Islam">Islam</option>
              <option value="Kristen">Kristen</option>
              <option value="Hindu">Hindu</option>
              <option value="Buddha">Buddha</option>
              <option value="Kong Hu Cu">Kong Hu Cu</option>
            @elseif ($user->agama === "Hindu") 
              <option value="{{ $user->agama }}" selected>Hindu</option>
              <option value="Islam">Islam</option>
              <option value="Katolik">Katolik</option>
              <option value="Kristen">Kristen</option>
              <option value="Buddha">Buddha</option>
              <option value="Kong Hu Cu">Kong Hu Cu</option>
            @elseif ($user->agama === "Buddha")  
              <option value="{{ $user->agama }}" selected>Buddha</option>
              <option value="Islam">Islam</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Kristen">Kristen</option>
              <option value="Kong Hu Cu">Kong Hu Cu</option>           
            @else
              <option value="{{ $user->agama }}" selected>Kong Hu Cu</option>
              <option value="Islam">Islam</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Kristen">Kristen</option>
              <option value="Buddha">Buddha</option>    
            @endif                
          </select>             
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
          <select class="form-select" name="status_wali">
            @if($user->status_wali === "Orang Tua")
              <option value="{{ $user->status_wali }}" selected>Orang Tua</option>
              <option value="Wali">Wali</option>            
            @else
            <option value="{{ $user->status_wali }}" selected>Wali</option>
            <option value="Orang Tua">Orang Tua</option>      
            @endif                
          </select>             
        </div>

            
      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/dashboard/pengguna" class="batalkanAksi_btn btn btn-danger ms-3">Batal</a>
    </form>
  </div>
@endsection
