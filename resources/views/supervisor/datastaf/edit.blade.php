@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/datastaf">Data Staf</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form method="POST" action="/supervisor/datastaf/{{ $staf->id }}" enctype="multipart/form-data">
      @method('put')
      @csrf
      <input type="hidden" name="oldGambar" value="{{ $staf->foto_profil }}">

      <div class="mb-3">
        <label for="nama" class="form-label">Nama <span class='text-danger'>*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
          value="{{ old('nama', $staf->nama) }}">
        @error('nama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="email" class="form-label">Email <span class='text-danger'>*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
              value="{{ old('email', $staf->email) }}">
            @error('email')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="telepon" class="form-label">Telepon <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon"
              name="telepon" value="{{ old('telepon', $staf->telepon) }}">
            @error('telepon')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role">
              @foreach ($roles as $role)
                @if (old('role', $staf->role) == $role->id)
                  <option value="{{ $role->id }}" selected>{{ $role->nama }}</option>
                @else
                  <option value="{{ $role->id }}">{{ $role->nama }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status">
              @foreach ($statuses as $status)
                @if (old('status', $staf->status) == $status->id)
                  <option value="{{ $status->id }}" selected>{{ $status->nama }}</option>
                @else
                  <option value="{{ $status->id }}">{{ $status->nama }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="foto_profil" class="form-label">Foto Profil</label>
        <input type="hidden" name="oldImage" value="{{ $staf->foto_profil }}">
        @if ($staf->foto_profil)
          <img src="{{ asset('storage/staff/' . $staf->foto_profil) }}" class="img-preview img-fluid d-block">
        @else
          <img class="img-preview img-fluid">
          <p>Belum ada foto profil</p>
        @endif
        <input class="form-control @error('staf') is-invalid @enderror" type="file" id="foto_profil" name="foto_profil"
          onchange="prevImg()">
        @error('foto_profil')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-warning">Ubah</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    function prevImg() {
      const image = document.querySelector('#foto_profil');
      const imgPreview = document.querySelector('.img-preview');

      imgPreview.style.display = 'block';
      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);

      oFReader.onload = function(OFREvent) {
        imgPreview.src = OFREvent.target.result;
      }
    }
  </script>
@endsection
