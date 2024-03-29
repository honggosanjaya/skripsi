@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/supervisor/event">Event</a></li>
    <li class="breadcrumb-item active" aria-current="page">Ubah</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('error'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
        {{ session('error') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif
  <div id="event-form">
    <div class="pt-4 px-5">
      <form id="form_submit" class="form-submit" method="POST"
        action="/supervisor/event/ubahevent/{{ $eventStatus->id ?? null }}" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="row">
          <div class="col">
            <div class="mb-3">
              <label for="kode_event" class="form-label">Kode Event <span class='text-danger'>*</span></label>
              <input type="text" class="form-control @error('kode_event') is-invalid @enderror" id="kode_event"
                name="kode_event" value="{{ old('kode_event', $eventStatus->kode ?? null) }}">
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
                name="nama_event" value="{{ old('nama_event', $eventStatus->nama ?? null) }}">
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
              <input type="number" class="form-control @error('potongan_diskon') is-invalid @enderror"
                id="potongan_diskon" name="potongan_diskon" value="{{ old('potongan_diskon', $diskon_potongan ?? null) }}"
                max="100" min="0" readonly>
              @error('potongan_diskon')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="col-3">
              <label></label>
              <select class="form-select mt-2" id="event_pilih_isian" name="event_pilih_isian">
                @if ($tipe === 'potongan')
                  <option value="potongan" selected>Potongan</option>
                  <option value="diskon" disabled>Diskon</option>
                @else
                  <option value="diskon" selected>Diskon</option>
                  <option value="potongan" disabled>Potongan</option>
                @endif
              </select>
            </div>

            <div class="col-6">
              <label for="min_pembelian" class="form-label">Minimal Pembelian</label>
              <input type="number" class="form-control @error('min_pembelian') is-invalid @enderror" id="min_pembelian"
                name="min_pembelian" value="{{ old('min_pembelian', $eventStatus->min_pembelian ?? null) }}"
                step=".01">
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
                name="tanggal_mulai" value="{{ date('Y-m-d', strtotime($eventStatus->date_start ?? null)) }}">
              @error('tanggal_mulai')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="col-6">
              <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class='text-danger'>*</span></label>
              <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                id="tanggal_selesai" name="tanggal_selesai"
                value="{{ date('Y-m-d', strtotime($eventStatus->date_end ?? null)) }}">
              @error('tanggal_selesai')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="status_enum" class="form-label">Status Event</label>
          <select class="form-select @error('status_enum') is-invalid @enderror" name="status_enum">
            @foreach ($statuses as $key => $val)
              @if (old('status_enum', $eventStatus->status_enum) == $key)
                <option value="{{ $key }}" selected>{{ $val }}</option>
              @else
                <option value="{{ $key }}">{{ $val }}</option>
              @endif
            @endforeach
          </select>
          @error('status_enum')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="d-flex flex-column mb-3">
          <label for="gambar" class="form-label">Gambar Event</label>
          <input type="hidden" name="oldGambar" value="{{ $eventStatus->gambar ?? null }}">

          @if ($eventStatus->gambar ?? null)
            <img class="img-preview img-fluid mb-4 col-sm-5"
              src="{{ asset('storage/event/' . $eventStatus->gambar) }}">
          @else
            <img class="img-preview img-fluid col-sm-5">
          @endif

          <input class="form-control @error('gambar') is-invalid @enderror" type="file" id="gambar"
            name="gambar" onchange="previewImage()">
          @error('gambar')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="keterangan" class="form-label">Keterangan</label>
          <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
            value="{{ old('keterangan') }}">{{ $eventStatus->keterangan ?? null }}</textarea>
          @error('keterangan')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="row justify-content-end mt-4">
          <div class="col-3 d-flex justify-content-end">
            <button type="submit" class="simpan_btn btn btn-warning me-3"><span class="iconify fs-5 me-1"
                data-icon="eva:edit-2-fill"></span> Edit</button>
            <a href="/supervisor/event/hapusevent/{{ $eventStatus->id ?? null }}" type="button"
              class="delete_event btn btn-danger"> <span class="iconify fs-5 me-1"
                data-icon="material-symbols:cancel-outline"></span>Delete</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/supervisor.js') }}"></script>
  @endpush
@endsection
