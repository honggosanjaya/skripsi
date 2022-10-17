@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/wilayah">Wilayah</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/wilayah/tambahwilayah">
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama_wilayah" class="form-label">Nama Wilayah Customer <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama_wilayah') is-invalid @enderror" id="nama_wilayah"
              name="nama_wilayah" value="{{ old('nama_wilayah') }}">
            @error('nama_wilayah')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="wilayah_parent" class="form-label">Wilayah yang Dituju (Parent) <span
                class='text-danger'>*</span></label>
            <select class="form-select @error('nama_wilayah') is-invalid @enderror" name="id_parent">
              <option value="">-- Pilih Wilayah --</option>
              @foreach ($dropdown as $d)
                @if ($d[1] == old('id_parent'))
                  <option value="{{ $d[1] }}" selected>{{ $d[0] }}</option>
                @else
                  <option value="{{ $d[1] }}">{{ $d[0] }}</option>
                @endif
              @endforeach
            </select>
            @error('id_parent')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
        </div>
      </div>
    </form>
  </div>
@endsection
