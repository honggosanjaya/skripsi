@extends('layouts.mainreact')

@push('JS')
  <script>
    $('.btn_perlu_dikirim').on("click", function() {
      $('.perlu_dikirim').removeClass('d-none');
      $('.sudah_sampai').addClass('d-none');
    });

    $('.btn_sudah_sampai').on("click", function() {
      $('.sudah_sampai').removeClass('d-none');
      $('.perlu_dikirim').addClass('d-none');
    });

    $('.btn_bukti_galeri').on("click", function() {
      $(this).parents('.modal-body').find('.bukti_galeri').removeClass('d-none');
      $(this).parents('.modal-body').find('.bukti_kamera').addClass('d-none');
      $(this).parents('.modal-body').find('.bukti_kamera img').addClass('d-none');
    });

    $('.btn_bukti_kamera').on("click", function() {
      $(this).parents('.modal-body').find('.bukti_kamera').removeClass('d-none');
      $(this).parents('.modal-body').find('.bukti_galeri').addClass('d-none');
      $(this).parents('.modal-body').find('.bukti_galeri img').addClass('d-none');
    });

    $('input[type=file]').on("change", function() {
      if ($(this).val()) {
        $(this).parents('.modal-content').find('.bukti_kamera img').removeClass('d-none');
        $(this).parents('.modal-content').find('.bukti_galeri img').removeClass('d-none');
        $(this).parents('.modal-content').find('.btn_submit_bukti').removeAttr('disabled');
      } else {
        $(this).parents('.modal-content').find('.btn_submit_bukti').attr("disabled", true);
      }
    });

    function prevImgGalery() {
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
@endpush

@section('main_content')
  <div class="page_container pt-4">
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    @endif

    <h1 class="fs-6 fw-bold">Data Pengiriman</h1>
    <div class="d-flex gap-4 align-items-center justify-content-between">
      <button class="btn btn-warning w-50 btn_perlu_dikirim">Perlu Dikirim</button>
      <button class="btn btn-outline-success w-50 btn_sudah_sampai">Sudah Sampai</button>
    </div>

    <div class='pengiriman_wrapper perlu_dikirim mt-4'>
      @foreach ($perludikirims as $perludikirim)
        <div class="list_pengiriman px-2">
          <div class='info-2column'>
            <span class='d-flex'>
              <b>No. Invoice</b>
              <div class='word_wrap'>{{ $perludikirim->linkInvoice->nomor_invoice ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Cutomer</b>
              <div class='word_wrap'>{{ $perludikirim->linkCustomer->nama ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Telepon</b>
              <div class='word_wrap'>{{ $perludikirim->linkCustomer->telepon ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Alamat</b>
              <div class='text-wrap word_wrap'>{{ $perludikirim->linkCustomer->full_alamat ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Waktu Berangkat</b>
              <p class='mb-0 word_wrap'>
                {{ $perludikirim->linkOrderTrack->waktu_berangkat ? date('j F Y, g:i a', strtotime($perludikirim->linkOrderTrack->waktu_berangkat)) : '' }}
              </p>
            </span>
          </div>
          <p class='mb-0 detail-pengiriman_link' data-bs-toggle="modal"
            data-bs-target="#detailModal{{ $perludikirim->id }}">Lihat detail</p>
        </div>

        <div class="modal fade" id="detailModal{{ $perludikirim->id }}" tabindex="-1" aria-labelledby="detailModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="detailModalLabel">Detail Pengiriman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class='info-2column'>
                  <span class='d-flex'>
                    <b>No. Invoice</b>
                    <p class='mb-0 word_wrap'>{{ $perludikirim->linkInvoice->nomor_invoice ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Customer</b>
                    <p class='mb-0 word_wrap'>{{ $perludikirim->linkCustomer->nama ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Telepon</b>
                    <p class='mb-0 word_wrap'>{{ $perludikirim->linkCustomer->telepon ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Alamat</b>
                    <p class='mb-0 word_wrap'>
                      @if ($perludikirim->linkCustomer->koordinat ?? null)
                        <a target="_blank"
                          href='https://www.google.com/maps/search/?api=1&query={{ str_replace('@', ',', $perludikirim->linkCustomer->koordinat) }}'>
                          {{ $perludikirim->linkCustomer->full_alamat }}
                        </a>
                      @else
                        {{ $perludikirim->linkCustomer->full_alamat }}
                      @endif
                    </p>
                  </span>
                  <span class='d-flex'>
                    <b>Keterangan Alamat</b>
                    <p class='mb-0 word_wrap'>{{ $perludikirim->linkCustomer->keterangan_alamat ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Waktu Berangkat</b>
                    <p class='mb-0 word_wrap'>
                      {{ $perludikirim->linkOrderTrack->waktu_berangkat ? date('j F Y, g:i a', strtotime($perludikirim->linkOrderTrack->waktu_berangkat)) : '' }}
                    </p>
                  </span>
                  <span class='d-flex'>
                    <b>Total Pembayaran</b>
                    <p class='mb-0 word_wrap'>{{ $perludikirim->linkInvoice->harga_total ?? null }}</p>
                  </span>
                  @if ($perludikirim->linkCustomer->foto ?? null)
                    <img src="{{ asset('storage/customer/' . $perludikirim->linkCustomer->foto) }}"
                      class="mt-2 img-fluid d-block mx-auto">
                  @endif
                  <table class="table mt-3">
                    <thead>
                      <tr>
                        <th scope="col" class='text-center'>No</th>
                        <th scope="col" class='text-center'>Nama Barang</th>
                        <th scope="col" class='text-center'>Kuantitas</th>
                        <th scope="col" class='text-center'>Satuan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($perludikirim->linkOrderItem as $item)
                        <tr>
                          <th scope="row" class='text-center'>{{ $loop->iteration }}</th>
                          <td>{{ $item->linkItem->nama ?? null }}</td>
                          <td>{{ $item->kuantitas ?? null }}</td>
                          <td>{{ $item->linkItem->satuan ?? null }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-success" data-bs-toggle="modal"
                  data-bs-target="#buktiPengiriman{{ $perludikirim->id }}">
                  <span class="iconify fs-3 me-1" data-icon="material-symbols:download-done"></span>Pengiriman Sampai
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="buktiPengiriman{{ $perludikirim->id }}" tabindex="-1"
          aria-labelledby="buktiPengirimanLabel" aria-hidden="true">
          <form method="POST" action="/lapangan/kirimsampai/{{ $perludikirim->id }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="buktiPengirimanLabel">Bukti Pengiriman</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="d-flex gap-4 mb-3">
                    <button type='button' class='btn btn-primary btn_bukti_galeri'>
                      <span class="iconify fs-3 me-1" data-icon="clarity:image-gallery-solid"></span> Galeri
                    </button>
                    <button type='button' class='btn btn-secondary btn_bukti_kamera'>
                      <span class="iconify fs-3 me-1" data-icon="charm:camera"></span>Kamera
                    </button>
                  </div>

                  <div class="bukti_galeri d-none">
                    <img class="img-preview img-fluid mb-3">
                    <label class="form-label">Foto Bukti Pengiriman <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" name="bukti_galeri" onchange="prevImgGalery()">
                  </div>
                  <div class="bukti_kamera d-none">
                    <img class="img-preview img-fluid mb-3">
                    <label class="form-label">Foto Bukti Pengiriman <span class="text-danger">*</span></label>
                    <input type="file" accept="image/*" capture="camera" class="form-control" name="bukti_kamera"
                      onchange="prevImgKamera()">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success btn_submit_bukti" disabled>
                    <span class="iconify fs-3 me-1" data-icon="material-symbols:download-done"></span>Kirim
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      @endforeach
    </div>

    <div class='pengiriman_wrapper sudah_sampai mt-4'>
      @foreach ($sudahsampais as $sudahsampai)
        <div class="list_pengiriman px-2">
          <div class='info-2column'>
            <span class='d-flex'>
              <b>No. Invoice</b>
              <div class='word_wrap'>{{ $sudahsampai->linkInvoice->nomor_invoice ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Cutomer</b>
              <div class='word_wrap'>{{ $sudahsampai->linkCustomer->nama ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Telepon</b>
              <div class='word_wrap'>{{ $sudahsampai->linkCustomer->telepon ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Alamat</b>
              <div class='text-wrap word_wrap'>{{ $sudahsampai->linkCustomer->full_alamat ?? null }}</div>
            </span>
            <span class='d-flex'>
              <b>Waktu Berangkat</b>
              <p class='mb-0 word_wrap'>
                {{ $sudahsampai->linkOrderTrack->waktu_berangkat ? date('j F Y, g:i a', strtotime($sudahsampai->linkOrderTrack->waktu_berangkat)) : '' }}
              </p>
            </span>
          </div>
          <p class='mb-0 detail-pengiriman_link' data-bs-toggle="modal"
            data-bs-target="#detailModal{{ $sudahsampai->id }}">Lihat detail</p>
        </div>

        <div class="modal fade" id="detailModal{{ $sudahsampai->id }}" tabindex="-1"
          aria-labelledby="detailModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="detailModalLabel">Detail Pengiriman</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class='info-2column'>
                  <span class='d-flex'>
                    <b>No. Invoice</b>
                    <p class='mb-0 word_wrap'>{{ $sudahsampai->linkInvoice->nomor_invoice ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Customer</b>
                    <p class='mb-0 word_wrap'>{{ $sudahsampai->linkCustomer->nama ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Telepon</b>
                    <p class='mb-0 word_wrap'>{{ $sudahsampai->linkCustomer->telepon ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Alamat</b>
                    <p class='mb-0 word_wrap'>
                      @if ($sudahsampai->linkCustomer->koordinat ?? null)
                        <a target="_blank"
                          href='https://www.google.com/maps/search/?api=1&query={{ str_replace('@', ',', $sudahsampai->linkCustomer->koordinat) }}'>
                          {{ $sudahsampai->linkCustomer->full_alamat }}
                        </a>
                      @else
                        {{ $sudahsampai->linkCustomer->full_alamat }}
                      @endif
                    </p>
                  </span>
                  <span class='d-flex'>
                    <b>Keterangan Alamat</b>
                    <p class='mb-0 word_wrap'>{{ $sudahsampai->linkCustomer->keterangan_alamat ?? null }}</p>
                  </span>
                  <span class='d-flex'>
                    <b>Waktu Berangkat</b>
                    <p class='mb-0 word_wrap'>
                      {{ $sudahsampai->linkOrderTrack->waktu_berangkat ? date('j F Y, g:i a', strtotime($sudahsampai->linkOrderTrack->waktu_berangkat)) : '' }}
                    </p>
                  </span>
                  <span class='d-flex'>
                    <b>Total Pembayaran</b>
                    <p class='mb-0 word_wrap'>{{ $sudahsampai->linkInvoice->harga_total ?? null }}</p>
                  </span>
                  @if ($sudahsampai->linkCustomer->foto ?? null)
                    <img src="{{ asset('storage/customer/' . $sudahsampai->linkCustomer->foto) }}"
                      class="mt-2 img-fluid d-block mx-auto">
                  @endif

                  <table class="table mt-3">
                    <thead>
                      <tr>
                        <th scope="col" class='text-center'>No</th>
                        <th scope="col" class='text-center'>Nama Barang</th>
                        <th scope="col" class='text-center'>Kuantitas</th>
                        <th scope="col" class='text-center'>Satuan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($sudahsampai->linkOrderItem as $item)
                        <tr>
                          <th scope="row" class='text-center'>{{ $loop->iteration }}</th>
                          <td>{{ $item->linkItem->nama ?? null }}</td>
                          <td>{{ $item->kuantitas ?? null }}</td>
                          <td>{{ $item->linkItem->satuan ?? null }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="modal-footer">
                @if (count($sudahsampai->linkInvoice->linkRetur) == 0 && $sudahsampai->linkOrderTrack->status_enum == '4')
                  <a href="/lapangan/retur/{{ $sudahsampai->id_customer ?? null }}"
                    class="btn btn-warning btn_ajukan_retur">
                    <span class="iconify fs-3 me-1" data-icon="ic:baseline-assignment-return"></span>Ajukan Retur
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
