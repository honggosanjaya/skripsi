@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/owner">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/owner/datasupervisor">Data Supervisor</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection
@section('main_content')
  <div class="pt-4 px-5">
    <form method="POST" action="/owner/datasupervisor?route=tambahsupervisor" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
          value="{{ old('nama') }}">
        @error('nama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row">
        <div class="col-12 col-sm-6">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
              value="{{ old('email') }}">
            @error('email')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col-12 col-sm-6">
          <div class="mb-3">
            <label for="telepon" class="form-label">Telepon</label>
            <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon"
              name="telepon" value="{{ old('telepon') }}">
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
                @if (old('role') == $role->id)
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
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status">
              @foreach ($statuses as $status)
                @if (old('status') == $status->id)
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
        <img class="img-preview img-fluid">
        <input class="form-control @error('foto_profil') is-invalid @enderror" type="file" id="foto_profil"
          name="foto_profil" onchange="prevImg()">
        @error('foto_profil')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
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
