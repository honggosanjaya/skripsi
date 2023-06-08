@extends('layouts.mainreact')

@push('CSS')
  <style>
    .select2-selection__clear {
      display: none !important;
    }
  </style>
@endpush

@push('JS')
  <script>
    $(document).ready(function() {
      if ($('textarea[name="alasan_penolakan"]').val()) {
        $('.btn_selesai_keluar').removeAttr("disabled");
      }
    });

    $('.btn_bukti_galeri').on("click", function() {
      $('.bukti_galeri').removeClass('d-none');
      $('.bukti_kamera').addClass('d-none');
      $('.bukti_kamera img').attr('src', '');
      $('input[name="bukti_kamera"]').val('').change();
    });

    $('.btn_bukti_kamera').on("click", function() {
      $('.bukti_kamera').removeClass('d-none');
      $('.bukti_galeri').addClass('d-none');
      $('.bukti_galeri img').attr('src', '');
      $('input[name="bukti_galeri"]').val('').change();
    });

    $('.btn_selesai_keluar').on("click", function(e) {
      $('input[name="status_enum"]').val(e.target.value);
      $('.form_trip').submit();
    });

    $('.btn_order').on("click", function(e) {
      $('input[name="status_enum"]').val(e.target.value);
      $('.form_trip').submit();
    });

    $('textarea[name="alasan_penolakan"]').on("change", function(e) {
      if ($(this).val) {
        $('.btn_selesai_keluar').removeAttr("disabled");
      } else {
        $('.btn_selesai_keluar').attr("disabled", true);
      }
    });

    $('.btn_no_wa').on("click", function(e) {
      $('input[name="status_telepon"]').val(e.target.value);
    });

    function prevImgGalery() {
      $('.foto_customer').addClass('d-none');
      const image = document.querySelector('.bukti_galeri input');
      const imgPreview = document.querySelector('.bukti_galeri .img-preview');
      imgPreview.style.display = 'block';
      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);
      oFReader.onload = function(OFREvent) {
        imgPreview.src = OFREvent.target.result;
      }
    }

    function prevImgKamera() {
      $('.foto_customer').addClass('d-none');
      const image = document.querySelector('.bukti_kamera input');
      const imgPreview = document.querySelector('.bukti_kamera .img-preview');
      imgPreview.style.display = 'block';
      const oFReader = new FileReader();
      oFReader.readAsDataURL(image.files[0]);
      oFReader.onload = function(OFREvent) {
        imgPreview.src = OFREvent.target.result;
      }
    }
  </script>

  <script>
    navigator.permissions.query({
      name: 'geolocation'
    }).then((result) => {
      if (result.state === 'prompt') {
        let timerInterval;
        let seconds = 7;
        Swal.fire({
          title: 'Peringatan Izin Akses Lokasi Perangkat',
          html: 'Selanjutnya kami akan meminta akses lokasi anda, mohon untuk mengizinkannya. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          timer: 7000,
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval = setInterval(() => {
              if (seconds > 0) {
                seconds -= 1;
              }
              b.textContent = seconds;
            }, 1000);
          },
        }).then((result) => {
          navigator.geolocation.getCurrentPosition(function(position) {
            $('input[name="koordinat"]').val(position.coords.latitude + '@' + position.coords.longitude);
          });
        })
      } else if (result.state === 'granted') {
        navigator.geolocation.getCurrentPosition(function(position) {
          $('input[name="koordinat"]').val(position.coords.latitude + '@' + position.coords.longitude);
        });
      } else if (result.state === 'denied') {
        let timerInterval2;
        let seconds2 = 4;
        Swal.fire({
          title: 'Tidak Ada Akses Lokasi Perangkat',
          html: 'Agar memudahkan kunjungan silahkan buka pengaturan browser anda dan ijinkan aplikasi mengakses lokasi. <br><br> Tunggu <b></b> detik untuk menutupnya',
          icon: 'info',
          allowOutsideClick: false,
          allowEscapeKey: false,
          confirmButtonText: 'Tutup',
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector('b')
            timerInterval2 = setInterval(() => {
              if (seconds2 > 0) {
                seconds2 -= 1;
              }
              b.textContent = seconds2;
            }, 1000);
            setTimeout(() => {
              Swal.hideLoading()
            }, 4000);
          },
        })
      }
    });
  </script>
@endpush

@section('main_content')
  <div class="page_container py-4">
    @if ($customer->id ?? null)
      <div class="d-flex mb-4">
        <a href="/lapangan/retur/{{ $customer->id }}" class="btn btn-sm btn-primary">
          <span class="iconify fs-4 me-2" data-icon="material-symbols:change-circle-outline-rounded"></span>Retur
        </a>
        <a href="/salesman/catalog/{{ $customer->id }}" class="btn btn-purple btn-sm ms-3">
          <span class="iconify fs-4 me-2" data-icon="carbon:shopping-catalog"></span>Katalog
        </a>
      </div>
    @endif

    <form method="POST" action="/salesman/simpancustomer" enctype="multipart/form-data" class="form_trip">
      @csrf
      <div class="mb-3">
        <label for="nama" class="form-label">Nama Customer<span class='text-danger'>*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama"
          value="{{ old('nama', $customer->nama ?? null) }}">
        @error('nama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email Customer</label>
        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
          value="{{ old('email', $customer->email ?? null) }}">
        @error('email')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="alamat_utama" class="form-label">Alamat Utama<span class='text-danger'>*</span></label>
        <input type="text" class="form-control @error('alamat_utama') is-invalid @enderror" name="alamat_utama"
          value="{{ old('alamat_utama', $customer->alamat_utama ?? null) }}">
        @error('alamat_utama')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="alamat_nomor" class="form-label">Alamat Nomor</label>
        <input type="text" class="form-control @error('alamat_nomor') is-invalid @enderror" name="alamat_nomor"
          value="{{ old('alamat_nomor', $customer->alamat_nomor ?? null) }}">
        @error('alamat_nomor')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="id_jenis" class="form-label">Jenis Customer <span class='text-danger'>*</span></label>
        <select class="form-select select2" name="id_jenis">
          @foreach ($jenises as $jenis)
            <option value="{{ $jenis->id }}"
              {{ old('jenis', $customer->id_jenis ?? null) == $jenis->id ? 'selected' : '' }}>
              {{ $jenis->nama }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Wilayah</label>
        <select class="form-select select2" name="id_wilayah">
          @foreach ($wilayah as $dt)
            <option value="{{ $dt->id }}"
              {{ old('id_wilayah', $customer->id_wilayah ?? null) == $dt->id ? 'selected' : '' }}>
              {{ $dt->nama }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Keterangan Alamat</label>
        <textarea class="form-control" name="keterangan_alamat">{{ old('keterangan_alamat', $customer->keterangan_alamat ?? null) }}</textarea>
      </div>

      <div class="mb-3">
        <label for="telepon" class="form-label">Telepon</label>
        <input type="text" class="form-control @error('telepon') is-invalid @enderror" name="telepon"
          value="{{ old('telepon', $customer->telepon ?? null) }}">
        @error('telepon')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Status Telepon</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control"
            value="{{ old('status_telepon', $customer->status_telepon ?? null) }}" name="status_telepon">
          <button type="button" class="btn btn-outline-primary btn_no_wa" value="WhatsApp (WA)">Nomor WA</button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Durasi Trip <span class='text-danger'>*</span></label>
        <div class="input-group mb-3">
          <input type="number" class="form-control"
            value={{ old('durasi_kunjungan', $customer->durasi_kunjungan ?? (null ?? 7)) }} name="durasi_kunjungan">
          <button class="btn btn-outline-secondary" type="button">Hari</button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Metode Pembayaran <span class='text-danger'>*</span></label>
        <select class="form-select" name="metode_pembayaran">
          <option value="1"
            {{ old('metode_pembayaran', $customer->metode_pembayaran ?? null) == '1' ? 'selected' : '' }}>Tunai</option>
          <option value="2"
            {{ old('metode_pembayaran', $customer->metode_pembayaran ?? null) == '2' ? 'selected' : '' }}>Giro</option>
          <option value="3"
            {{ old('metode_pembayaran', $customer->metode_pembayaran ?? null) == '3' ? 'selected' : '' }}>Transfer
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Jatuh Tempo <span class='text-danger'>*</span></label>
        <div class="input-group mb-3">
          <input type="number" class="form-control" value={{ old('jatuh_tempo', $customer->jatuh_tempo ?? 7) }}
            name="jatuh_tempo">
          <button class="btn btn-outline-secondary" type="button">Hari</button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Alasan Penolakan</label>
        <textarea class="form-control" name="alasan_penolakan">{{ old('alasan_penolakan', $customer->alasan_penolakan ?? null) }}</textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Foto Tempat Usaha</label>
        <div class="d-flex gap-4 mb-3">
          <button type='button' class='btn btn-primary btn_bukti_galeri'>
            <span class="iconify fs-3 me-1" data-icon="clarity:image-gallery-solid"></span> Galeri
          </button>
          <button type='button' class='btn btn-secondary btn_bukti_kamera'>
            <span class="iconify fs-3 me-1" data-icon="charm:camera"></span>Kamera
          </button>
        </div>

        @if ($customer->foto ?? null)
          <img src="{{ asset('storage/customer/' . $customer->foto) }}"
            class="img-fluid img_prev mb-3 d-block foto_customer">
        @endif

        <div class="bukti_galeri d-none">
          <img class="img-preview img-fluid mb-3 d-block">
          <input class="form-control" type="file" accept="image/*" name="bukti_galeri" onchange="prevImgGalery()">
        </div>
        <div class="bukti_kamera d-none">
          <img class="img-preview img-fluid mb-3 d-block">
          <input type="file" accept="image/*" capture="camera" class="form-control" name="bukti_kamera"
            onchange="prevImgKamera()">
        </div>
      </div>

      <input type="hidden" value="" name="status_enum">
      <input type="hidden" value="{{ now() }}" name="jam_masuk">
      <input type="hidden" value="{{ auth()->user()->id_users }}" name="id_staff">
      <input type="hidden" value="" name="koordinat">
      <input type="hidden" value="{{ $customer->id ?? null }}" name="id">

      <div class="d-flex justify-content-end">
        <button class="btn btn-danger me-3 btn_selesai_keluar" value="trip" disabled>
          Selesai dan Keluar
        </button>

        <button class="btn btn-success btn_order" type="button" value="order">
          <span class="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
        </button>
      </div>
    </form>
  </div>
@endsection
