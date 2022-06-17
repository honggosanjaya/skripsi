@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/supervisor/wilayah">Wilayah</a></li>
  <li class="breadcrumb-item active" aria-current="page">Ubah</li>
</ol>
@endsection

@section('main_content')
  <div class="p-4">
    
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/wilayah/ubahwilayah/{{ $district->id }}"
        enctype="multipart/form-data">
        @method('put')
      @csrf
      <div class="mb-3">
        <label for="nama_wilayah" class="form-label">Nama Wilayah Customer</label>
        <input type="text" class="form-control @error('nama_wilayah') is-invalid @enderror" id="nama_wilayah"
          name="nama_wilayah" value="{{ old('nama_wilayah', $district->nama ) }}">
        @error('nama_wilayah')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="wilayah_parent" class="form-label">Wilayah Parent</label>
        <select class="form-select" name="id_parent">
            <option value="">-- Pilih Wilayah --</option>
            @foreach ($selections as $selection)
                <option value="{{ $selection[1] }}" {{ ( $selection[1] === ($district->id)) ? 'selected' : '' }}>
                    {{ $selection[0] }}
                </option>
            @endforeach
        </select>
      </div>

      <button type="submit" class="simpan_btn btn btn-primary">Simpan</button>
      
    </form>
  </div>
@endsection
