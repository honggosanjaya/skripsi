@extends('layouts.mainmobile')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/datacustomer">Data Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
  </ol>
@endsection

@section('main_content')
  <div class="container pt-4" id="data-customer">
    <form method="POST" id='data-form' action="/administrasi/datacustomer/tambahcustomer" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama"
          value="{{ old('nama') }}">
        @error('nama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="id_jenis" class="form-label">Jenis Customer <span class="text-danger">*</span></label>
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
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="id_wilayah" class="form-label">Wilayah</label>
            @php
              $isHasChooseWilayah = false;
            @endphp
            <select class="form-select select-two" name="id_wilayah">
              @if ($isHasChooseWilayah == false)
                <option selected value>-- Pilih Wilayah --</option>
              @else
                <option value>-- Pilih Wilayah --</option>
              @endif
              @foreach ($districts as $district)
                @if (old('id_wilayah') == $district->id)
                  @php
                    $isHasChooseWilayah = true;
                  @endphp
                  <option value="{{ $district->id }}" selected>{{ $district->nama }}</option>
                @else
                  @php
                    $isHasChooseWilayah = false;
                  @endphp
                  <option value="{{ $district->id }}">{{ $district->nama }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
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
      </div>

      <div class="row">
        <div class="col">
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
        <div class="col">
          <div class="mb-3">
            <label for="status_telepon" class="form-label">Status Telepon</label>
            <div class="input-group mb-3">
              <input type="text" class="form-control @error('status_telepon') is-invalid @enderror" id="status_telepon"
                name="status_telepon" value="{{ old('status_telepon') }}">
              <button class="btn btn-outline-primary btn-tagline-nowa" type="button" id="button-addon2">Nomor
                WA</button>
            </div>
            @error('status_telepon')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="alamat_utama" class="form-label">Alamat Utama <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('alamat_utama') is-invalid @enderror" id="alamat_utama"
          name="alamat_utama" value="{{ old('alamat_utama') }}">
        @error('alamat_utama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="alamat_nomor" class="form-label">Alamat Nomor</label>
        <input type="text" class="form-control @error('alamat_nomor') is-invalid @enderror" id="alamat_nomor"
          name="alamat_nomor" value="{{ old('alamat_nomor') }}">
        @error('alamat_nomor')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="keterangan_alamat" class="form-label">Keterangan Alamat</label>
        <textarea class="form-control @error('keterangan_alamat') is-invalid @enderror" id="keterangan_alamat"
          name="keterangan_alamat">{{ old('keterangan_alamat') }}</textarea>
        @error('keterangan_alamat')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="status_enum" class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select" name="status_enum">
              @foreach ($statuses as $key => $val)
                @if (old('status_enum') == null)
                  <option value="{{ $key }}">{{ $val }}</option>
                @elseif (old('status_enum') == $key)
                  <option value="{{ $key }}" selected>{{ $val }}</option>
                @else
                  <option value="{{ $key }}">{{ $val }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
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
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="tipe_harga" class="form-label">Tipe Harga <span class="text-danger">*</span></label>
            <select class="form-select" name="tipe_harga">
              @foreach ($tipe_hargas as $tipe_harga)
                @if (old('tipe_harga') == $tipe_harga['value'])
                  <option value="{{ $tipe_harga['value'] }}" selected>{{ $tipe_harga['name'] }}</option>
                @else
                  <option value="{{ $tipe_harga['value'] }}">{{ $tipe_harga['name'] }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <div class="mb-3">
            <label for="limit_pembelian" class="form-label">Limit Pembelian</label>
            <input type="number" class="form-control" id="limit_pembelian" name="limit_pembelian" step=".01"
              readonly>
          </div>
        </div>
        <div class="col">
          <div class="mb-3">
            <label for="pengajuan_limit_pembelian" class="form-label">Pengajuan Limit</label>
            <input type="number" class="form-control @error('pengajuan_limit_pembelian') is-invalid @enderror"
              id="pengajuan_limit_pembelian" name="pengajuan_limit_pembelian"
              value="{{ old('pengajuan_limit_pembelian') }}" step=".01">
            @error('pengajuan_limit_pembelian')
              <div class="invalid-feedback">
                {{ $message }}
              </div>
            @enderror
          </div>
        </div>
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

      <div class="row justify-content-end mt-4">
        <div class="col d-flex justify-content-end">
          <button type="submit" class="btn btn-purple-gradient btn-submit"><span class="iconify me-1 fs-3"
              data-icon="dashicons:database-add"></span>Tambah Data</button>
        </div>
      </div>
    </form>
  </div>

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
  @push('JS')
    <script>
      $(document).on('click', '.btn-tagline-nowa', function() {
        $('#status_telepon').val('WhatsApp (WA)')
      });
    </script>
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection