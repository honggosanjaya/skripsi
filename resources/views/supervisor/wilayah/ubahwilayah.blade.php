@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/wilayah">Wilayah</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/wilayah/ubahwilayah/{{ $district->id }}"
      enctype="multipart/form-data">
      @method('put')
      @csrf

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="nama_wilayah" class="form-label">Nama Wilayah Customer <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama_wilayah') is-invalid @enderror" id="nama_wilayah"
              name="nama_wilayah" value="{{ old('nama_wilayah', $district->nama) }}">
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
            <select class="form-select" name="id_parent">
              <option value="">-- Pilih Wilayah --</option>
              @foreach ($selections as $selection)
                <option value="{{ $selection[1] }}" {{ $selection[1] === $district->id ? 'selected' : '' }}>
                  {{ $selection[0] }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row justify-content-end mt-4">
        <div class="col-3 d-flex justify-content-end">
          <button type="submit" class="btn btn-warning"><span class="iconify fs-5 me-1"
              data-icon="eva:edit-2-fill"></span>Ubah</button>
        </div>
      </div>
    </form>
  </div>
@endsection
