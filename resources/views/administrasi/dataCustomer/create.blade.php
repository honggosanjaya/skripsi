@extends('layouts.main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/datacustomer">Data Customer</a></li>
  <li class="breadcrumb-item active" aria-current="page">Tambah</li>
</ol>
@endsection
@section('main_content')
  <form method="POST" action="/administrasi/datacustomer/tambahcustomer" enctype="multipart/form-data">
    @csrf
    <div class="my-3">
      <label for="nama" class="form-label">Nama</label>
      <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
        value="{{ old('nama') }}">
      @error('nama')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="id_jenis" class="form-label">Jenis Customer</label>
      <select class="form-select" name="id_jenis">
        @foreach ($customer_types as $customer_type)
          @if (old('id_jenis') == $customer_type->id)
            <option value="{{ $customer_type->id }}" selected>{{ $customer_type->nama }}</option>
          @else
            <option value="{{ $customer_type->id }}">{{ $customer_type->nama }}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="my-3">
      <label for="id_wilayah" class="form-label">Wilayah</label>
      <select class="form-select" name="id_wilayah">
        @foreach ($districts as $district)
          @if (old('id_wilayah') == $district->id)
            <option value="{{ $district->id }}" selected>{{ $district->nama }}</option>
          @else
            <option value="{{ $district->id }}">{{ $district->nama }}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="my-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
        value="{{ old('email') }}">
      @error('email')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="alamat_utama" class="form-label">Alamat Utama</label>
      <input type="text" class="form-control @error('alamat_utama') is-invalid @enderror" id="alamat_utama"
        name="alamat_utama" value="{{ old('alamat_utama') }}">
      @error('alamat_utama')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="alamat_nomor" class="form-label">Alamat Nomor</label>
      <input type="text" class="form-control @error('alamat_nomor') is-invalid @enderror" id="alamat_nomor"
        name="alamat_nomor" value="{{ old('alamat_nomor') }}">
      @error('alamat_nomor')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="keterangan_alamat" class="form-label">Keterangan Alamat</label>
      <input type="text" class="form-control @error('keterangan_alamat') is-invalid @enderror" id="keterangan_alamat"
        name="keterangan_alamat" value="{{ old('keterangan_alamat') }}">
      @error('keterangan_alamat')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="telepon" class="form-label">Telepon</label>
      <input type="text" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon"
        value="{{ old('telepon') }}">
      @error('telepon')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
      <label for="tipe_retur" class="form-label">Tipe Retur</label>
      <select class="form-select" name="tipe_retur">
        <option value="">Pilih Nanti</option>
        @foreach ($retur_types as $retur_type)
          @if (old('tipe_retur') == $retur_type->id)
            <option value="{{ $retur_type->id }}" selected>{{ $retur_type->nama }}</option>
          @else
            <option value="{{ $retur_type->id }}">{{ $retur_type->nama }}</option>
          @endif
        @endforeach
      </select>
    </div>

    <div class="my-3">
      <label for="limit_pembelian" class="form-label">Limit Pembelian</label>
      <input type="number" class="form-control" id="limit_pembelian" name="limit_pembelian" step=".01" readonly>
    </div>

    <div class="my-3">
      <label for="pengajuan_limit_pembelian" class="form-label">Pengajuan Limit Pembelian</label>
      <input type="number" class="form-control @error('pengajuan_limit_pembelian') is-invalid @enderror"
        id="pengajuan_limit_pembelian" name="pengajuan_limit_pembelian" value="{{ old('pengajuan_limit_pembelian') }}"
        step=".01">
      @error('pengajuan_limit_pembelian')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="my-3">
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

    <div class="mb-3">
      <label for="foto" class="form-label">Foto</label>
      <img class="img-preview img-fluid">
      <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto"
        onchange="prevImg()">
      @error('foto')
        <div class="invalid-feedback">
          {{ $message }}
        </div>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary">Tambah Data</button>
  </form>
  <script>
    function prevImg() {
      const image = document.querySelector('#foto');
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
