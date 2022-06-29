@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/event">Event</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/event/tambahevent"
      enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="kode_event" class="form-label">Kode Event <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('kode_event') is-invalid @enderror" id="kode_event"
              name="kode_event" value="{{ old('kode_event') }}">
            @error('kode_event')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="nama_event" class="form-label">Nama Event <span class='text-danger'>*</span></label>
            <input type="text" class="form-control @error('nama_event') is-invalid @enderror" id="nama_event"
              name="nama_event" value="{{ old('nama_event') }}">
            @error('nama_event')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <div class="row">
          <div class="col-3">
            <label for="potongan_diskon" class="form-label">Potongan/Diskon</label>
            <input type="number" class="form-control @error('potongan_diskon') is-invalid @enderror" id="potongan_diskon"
              name="potongan_diskon" value="{{ old('potongan_diskon', 0) }}" max="100" min="0">
            @error('potongan_diskon')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="col-3">
            <label></label>
            <select class="form-select mt-2" id="event_pilih_isian" name="event_pilih_isian">
              <option value="diskon">Diskon</option>
              <option value="potongan">Potongan</option>
            </select>
          </div>

          <div class="col-6">
            <label for="min_pembelian" class="form-label">Minimal Pembelian</label>
            <input type="number" class="form-control @error('min_pembelian') is-invalid @enderror" id="min_pembelian"
              name="min_pembelian" value="{{ old('min_pembelian') ?? 0 }}" step=".01">
            @error('min_pembelian')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <div class="row">
          <div class="col-6">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class='text-danger'>*</span></label>
            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai"
              name="tanggal_mulai">
            @error('tanggal_mulai')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>

          <div class="col-6">
            <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class='text-danger'>*</span></label>
            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai"
              name="tanggal_selesai" value="{{ old('tanggal_selesai') }}">
            @error('tanggal_selesai')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="status" class="form-label">Status Event</label>
        <select class="form-select @error('status') is-invalid @enderror" name="status">
          @foreach ($eventStatuses as $eventStatus)
            <option value="{{ $eventStatus->id }}">{{ $eventStatus->nama }}</option>
          @endforeach
        </select>
        @error('status')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="gambar" class="form-label">Gambar Event</label>
        <img class="img-preview img-fluid mb-3 col-sm-5">
        <input class="form-control @error('gambar') is-invalid @enderror" type="file" id="gambar" name="gambar"
          onchange="previewImage()">
        @error('gambar')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="keterangan" class="form-label">Keterangan <span class='text-danger'>*</span></label>
        <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
          value="{{ old('keterangan') }}"></textarea>
        @error('keterangan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
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
