@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/supervisor/event">Event</a></li>
  <li class="breadcrumb-item active" aria-current="page">Tambah</li>
</ol>
@endsection

@section('main_content')
  <div class="p-4">
    <a class="btn btn-primary mt-2 mb-3" href="/supervisor/event"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>
    
    <form id="form_submit" class="form-submit" method="POST" action="/supervisor/event/tambahevent">
      @csrf
      <div class="mb-3">
        <label for="kode_event" class="form-label">Kode Event</label>
        <input type="text" class="form-control @error('kode_event') is-invalid @enderror" id="kode_event"
          name="kode_event" value="{{ old('kode_event', $eventStatus->kode) }}">
        @error('kode_event')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="nama_event" class="form-label">Nama Event</label>
        <input type="text" class="form-control @error('nama_event') is-invalid @enderror" id="nama_event"
          name="nama_event" value="{{ old('nama_event', $eventStatus->nama) }}">
        @error('nama_event')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
          <div class="row">

              <div class="col-3">
                <label for="potongan-diskon" class="form-label">Potongan/Diskon</label>
                <input type="number" class="form-control @error('potongan-diskon') is-invalid @enderror" id="potongan-diskon" name="potongan-diskon"
                  value="{{ old('potongan-diskon', $eventStatus->diskon) }}" max="100" min="0">
                @error('potongan-diskon')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="col-3">
                <label></label>
                <select class="form-select mt-2" id="event-pilih-isian">
                    <option value="diskon">Diskon</option>
                    <option value="potongan">Potongan</option>                    
                </select>                
              </div>

              <div class="col-6">
                <label for="min_pembelian" class="form-label">Minimal Pembelian</label>
                <input type="number" class="form-control @error('min_pembelian') is-invalid @enderror" id="min_pembelian" name="min_pembelian"
                  value="{{ old('min_pembelian', $eventStatus->min_pembelian) }}" step=".01">
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
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai"
                    name="tanggal_mulai" value="{{ old('tanggal_mulai',$eventStatus->date_start) }}">
                    @error('tanggal_mulai')
                    <div class="invalid-feedback">
                    {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="col-6">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai"
                    name="tanggal_selesai" value="{{ old('tanggal_selesai', $eventStatus->date_end) }}">
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
                @foreach ($selections as $selection)
                    <option value="{{ $selection->id }}" {{ ( $selection->id === ($eventStatus->status)) ? 'selected' : '' }}>{{ $eventStatus->nama }}</option>
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
          value="{{ old('keterangan') }}">{{ $eventStatus->keterangan }}</textarea>
        @error('keterangan')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
        </div>

      <button type="submit" class="simpan_btn btn btn-primary">Simpan</button>
      
    </form>
  </div>
@endsection
