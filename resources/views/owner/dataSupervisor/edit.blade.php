@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/owner">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/owner/datasupervisor">Data Supervisor</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form method="POST" action="/owner/datasupervisor/{{ $supervisor->id }}/edit?route=editsupervisor"
      enctype="multipart/form-data">
      @method('put')
      @csrf

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
              value="{{ old('nama', $supervisor->nama) }}">
            @error('nama')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-sm-6">
          <div class="mb-3">
            <label for="email" class="form-label">Email <span class='text-danger'>*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
              value="{{ old('email', $supervisor->email) }}">
            @error('email')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col-12 col-sm-6">
          <div class="mb-3">
            <label for="telepon" class="form-label">Telepon <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon"
              name="telepon" value="{{ old('telepon', $supervisor->telepon) }}">
            @error('telepon')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12 col-sm-6">
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" name="role">
              @foreach ($roles as $role)
                @if (old('role', $supervisor->role) == $role->id)
                  <option value="{{ $role->id }}" selected>{{ $role->nama }}</option>
                @else
                  <option value="{{ $role->id }}">{{ $role->nama }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-12 col-sm-6">
          <div class="mb-3">
            <label for="status_enum" class="form-label">Status</label>
            <select class="form-select" name="status_enum">
              @foreach ($statuses as $key => $val)
                @if (old('status_enum', $supervisor->status_enum) == $key)
                  <option value="{{ $key }}" selected>{{ $val }}</option>
                @else
                  <option value="{{ $key }}">{{ $val }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="foto_profil" class="form-label">Foto Profil</label>
        <input type="hidden" name="oldImage" value="{{ $supervisor->foto_profil }}">
        @if ($supervisor->foto_profil)
          <img src="{{ asset('storage/staff/' . $supervisor->foto_profil) }}" class="img-preview img-fluid d-block">
        @else
          <img class="img-preview img-fluid">
          <p>Belum ada foto profil</p>
        @endif
        <input class="form-control @error('supervisor') is-invalid @enderror" type="file" id="foto_profil"
          name="foto_profil" onchange="prevImg()">
        @error('foto_profil')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
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
