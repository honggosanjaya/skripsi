@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer/profil">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Pesanan Saya</h1>
    </div>
  </header>
@endsection

@section('content')
  <ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="diajukan-tab" href="#diajukan" data-bs-toggle="tab" data-bs-target="#diajukan"
        role="tab" aria-controls="diajukan" aria-selected="true">Diajukan</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="dikonfirmasiAdministrasi-tab" href="#dikonfirmasiAdministrasi" data-bs-toggle="tab"
        data-bs-target="#dikonfirmasiAdministrasi" role="tab" aria-controls="dikonfirmasiAdministrasi"
        aria-selected="false">Dikonfirmasi</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="dalamPerjalanan-tab" href="#dalamPerjalanan" data-bs-toggle="tab"
        data-bs-target="#dalamPerjalanan" role="tab" aria-controls="dalamPerjalanan" aria-selected="false">Dikirim</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="telahSampai-tab" href="#telahSampai" data-bs-toggle="tab" data-bs-target="#telahSampai"
        role="tab" aria-controls="telahSampai" aria-selected="false">Diterima</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="ditolak-tab" href="#ditolak" data-bs-toggle="tab" data-bs-target="#ditolak" role="tab"
        aria-controls="ditolak" aria-selected="false">Ditolak</a>
    </li>
  </ul>


  <div class="tab-content clearfix">
    <div class="tab-pane fade show active" id="diajukan" role="tabpanel" aria-labelledby="diajukan-tab">
      @foreach ($diajukans as $diajukan)
        <div class="card_pesanan" data-bs-toggle="modal" data-bs-target="#order{{ $diajukan->id }}">
          <div class="card_header">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pemesanan:</p>
              <p class="fs-7 mb-0">{{ date('d F Y', strtotime($diajukan->linkOrderTrack->waktu_order)) }}</p>
            </div>
            <div class="badge bg-warning text-black fw-normal">{{ $diajukan->linkOrderTrack->linkStatus->nama ?? null }}
            </div>
          </div>
          @php
            $i = 0;
            $first_item;
            foreach ($diajukan->linkOrderItem as $orderItem) {
                if ($i == 0) {
                    $first_item = $orderItem;
                    $i++;
                }
            }
          @endphp
          <div class="card_body mt-2">
            <div class="d-flex">
              @if ($first_item->linkItem->gambar)
                <img src="{{ asset('storage/item/' . $first_item->linkItem->gambar) }}" class="img-fluid img_riwayat">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_riwayat">
              @endif
              <div class="ms-3">
                <p class="mb-0 fw-bold">{{ $first_item->linkItem->nama }}</p>
                <p class="mb-0 fs-7">{{ $first_item->kuantitas }} barang</p>
              </div>
            </div>

            @if ($diajukan->linkOrderItem->count() - 1 > 0)
              <p class="fs-7 mb-0">+ {{ $diajukan->linkOrderItem->count() - 1 }} item lainnya</p>
            @endif
          </div>

          <div class="modal fade" id="order{{ $diajukan->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Detail Pesanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="info-pesanan">
                    <span><b>Tanggal pesan</b>
                      {{ date('d F Y', strtotime($diajukan->linkOrderTrack->waktu_order)) }}</span>
                    <span><b>Status pesanan</b>{{ $diajukan->linkOrderTrack->linkStatus->nama ?? null }}</span>
                    @if ($diajukan->linkOrderTrack->estimasi_waktu_pengiriman)
                      <span><b>Estimasi pengiriman</b>{{ $diajukan->linkOrderTrack->estimasi_waktu_pengiriman }}
                        hari</span>
                    @endif
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center">Nama Barang</th>
                          <th scope="col" class="text-center">Kuantitas</th>
                          <th scope="col" class="text-center">Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($diajukan->linkOrderItem as $orderItem)
                          <tr>
                            <td>{{ $orderItem->linkItem->nama }}</td>
                            <td>{{ $orderItem->kuantitas }} x
                              {{ number_format($orderItem->harga_satuan, 0, '', '.') }}</td>
                            <td>{{ number_format($orderItem->kuantitas * $orderItem->harga_satuan, 0, '', '.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="tab-pane" id="dikonfirmasiAdministrasi" role="tabpanel"
      aria-labelledby="dikonfirmasiAdministrasi-tab">
      @foreach ($dikonfirmasiAdministrasis as $dikonfirmasiAdministrasi)
        <div class="card_pesanan" data-bs-toggle="modal" data-bs-target="#order{{ $dikonfirmasiAdministrasi->id }}">
          <div class="card_header">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pemesanan:</p>
              <p class="fs-7 mb-0">
                {{ date('d F Y', strtotime($dikonfirmasiAdministrasi->linkOrderTrack->waktu_order)) }}</p>
            </div>
            <div class="badge bg-warning text-black fw-normal">
              {{ $dikonfirmasiAdministrasi->linkOrderTrack->linkStatus->nama ?? null }}</div>
          </div>
          @php
            $i = 0;
            $first_item;
            foreach ($dikonfirmasiAdministrasi->linkOrderItem as $orderItem) {
                if ($i == 0) {
                    $first_item = $orderItem;
                    $i++;
                }
            }
          @endphp
          <div class="card_body mt-2">
            <div class="d-flex">
              @if ($first_item->linkItem->gambar)
                <img src="{{ asset('storage/item/' . $first_item->linkItem->gambar) }}"
                  class="img-fluid img_riwayat">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_riwayat">
              @endif
              <div class="ms-3">
                <p class="mb-0 fw-bold">{{ $first_item->linkItem->nama }}</p>
                <p class="mb-0 fs-7">{{ $first_item->kuantitas }} barang</p>
              </div>
            </div>

            @if ($diajukan->linkOrderItem->count() - 1 > 0)
              <p class="fs-7 mb-0">+ {{ $diajukan->linkOrderItem->count() - 1 }} item lainnya</p>
            @endif
          </div>

          <div class="modal fade" id="order{{ $dikonfirmasiAdministrasi->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Detail Pesanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="info-pesanan">
                    <span><b>Tanggal pesan</b>
                      {{ date('d F Y', strtotime($dikonfirmasiAdministrasi->linkOrderTrack->waktu_order)) }}</span>
                    <span><b>Status
                        pesanan</b>{{ $dikonfirmasiAdministrasi->linkOrderTrack->linkStatus->nama ?? null }}</span>
                    <span><b>Invoice</b>{{ $dikonfirmasiAdministrasi->linkInvoice->nomor_invoice }}</span>
                    <span><b>Kode Event</b>{{ $dikonfirmasiAdministrasi->linkInvoice->linkEvent->kode ?? '-' }}</span>
                    @if ($dikonfirmasiAdministrasi->linkInvoice->linkEvent && $dikonfirmasiAdministrasi->linkInvoice->linkEvent->diskon != null)
                      <span><b>Diskon</b>{{ $dikonfirmasiAdministrasi->linkInvoice->linkEvent->diskon }}%</span>
                    @elseif($dikonfirmasiAdministrasi->linkInvoice->linkEvent && $dikonfirmasiAdministrasi->linkInvoice->linkEvent->potongan != null)
                      <span><b>Potongan</b>Rp.
                        {{ number_format($dikonfirmasiAdministrasi->linkInvoice->linkEvent->potongan, 0, '', '.') }}</span>
                    @endif
                    @if ($dikonfirmasiAdministrasi->linkOrderTrack->estimasi_waktu_pengiriman)
                      <span><b>Estimasi
                          pengiriman</b>{{ $dikonfirmasiAdministrasi->linkOrderTrack->estimasi_waktu_pengiriman }}
                        hari</span>
                    @endif
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center">Nama Barang</th>
                          <th scope="col" class="text-center">Kuantitas</th>
                          <th scope="col" class="text-center">Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($dikonfirmasiAdministrasi->linkOrderItem as $orderItem)
                          <tr>
                            <td>{{ $orderItem->linkItem->nama }}</td>
                            <td>{{ $orderItem->kuantitas }} x
                              {{ number_format($orderItem->harga_satuan, 0, '', '.') }}</td>
                            <td>{{ number_format($orderItem->kuantitas * $orderItem->harga_satuan, 0, '', '.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="tab-pane" id="dalamPerjalanan" role="tabpanel" aria-labelledby="dalamPerjalanan-tab">
      @foreach ($dalamPerjalanans as $dalamPerjalanan)
        <div class="card_pesanan" data-bs-toggle="modal" data-bs-target="#order{{ $dalamPerjalanan->id }}">
          <div class="card_header">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pemesanan:</p>
              <p class="fs-7 mb-0">{{ date('d F Y', strtotime($dalamPerjalanan->linkOrderTrack->waktu_order)) }}</p>
            </div>
            <div class="badge bg-warning text-black fw-normal">
              {{ $dalamPerjalanan->linkOrderTrack->linkStatus->nama ?? null }}</div>
          </div>
          @php
            $i = 0;
            $first_item;
            foreach ($dalamPerjalanan->linkOrderItem as $orderItem) {
                if ($i == 0) {
                    $first_item = $orderItem;
                    $i++;
                }
            }
          @endphp
          <div class="card_body mt-2">
            <div class="d-flex">
              @if ($first_item->linkItem->gambar)
                <img src="{{ asset('storage/item/' . $first_item->linkItem->gambar) }}"
                  class="img-fluid img_riwayat">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_riwayat">
              @endif
              <div class="ms-3">
                <p class="mb-0 fw-bold">{{ $first_item->linkItem->nama }}</p>
                <p class="mb-0 fs-7">{{ $first_item->kuantitas }} barang</p>
              </div>
            </div>

            @if ($dalamPerjalanan->linkOrderItem->count() - 1 > 0)
              <p class="fs-7 mb-0">+ {{ $dalamPerjalanan->linkOrderItem->count() - 1 }} item lainnya</p>
            @endif
          </div>

          <div class="modal fade" id="order{{ $dalamPerjalanan->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Detail Pesanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="info-pesanan">
                    <span><b>Tanggal pesan</b>
                      {{ date('d F Y', strtotime($dalamPerjalanan->linkOrderTrack->waktu_order)) }}</span>
                    <span><b>Status pesanan</b>{{ $dalamPerjalanan->linkOrderTrack->linkStatus->nama ?? null }}</span>
                    <span><b>Invoice</b>{{ $dalamPerjalanan->linkInvoice->nomor_invoice }}</span>
                    <span><b>Kode Event</b>{{ $dalamPerjalanan->linkInvoice->linkEvent->kode ?? '-' }}</span>
                    @if ($dalamPerjalanan->linkInvoice->linkEvent && $dalamPerjalanan->linkInvoice->linkEvent->diskon != null)
                      <span><b>Diskon</b>{{ $dalamPerjalanan->linkInvoice->linkEvent->diskon }}%</span>
                    @elseif($dalamPerjalanan->linkInvoice->linkEvent && $dalamPerjalanan->linkInvoice->linkEvent->potongan != null)
                      <span><b>Potongan</b>Rp.
                        {{ number_format($dalamPerjalanan->linkInvoice->linkEvent->potongan, 0, '', '.') }}</span>
                    @endif
                    @if ($dalamPerjalanan->linkOrderTrack->estimasi_waktu_pengiriman)
                      <span><b>Estimasi
                          pengiriman</b>{{ $dalamPerjalanan->linkOrderTrack->estimasi_waktu_pengiriman }} hari</span>
                    @endif
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center">Nama Barang</th>
                          <th scope="col" class="text-center">Kuantitas</th>
                          <th scope="col" class="text-center">Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($dalamPerjalanan->linkOrderItem as $orderItem)
                          <tr>
                            <td>{{ $orderItem->linkItem->nama }}</td>
                            <td>{{ $orderItem->kuantitas }} x
                              {{ number_format($orderItem->harga_satuan, 0, '', '.') }}</td>
                            <td>{{ number_format($orderItem->kuantitas * $orderItem->harga_satuan, 0, '', '.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="tab-pane" id="telahSampai" role="tabpanel" aria-labelledby="telahSampai-tab">
      @foreach ($telahSampais as $telahsampai)
        <div class="card_pesanan" data-bs-toggle="modal" data-bs-target="#order{{ $telahsampai->id }}">
          <div class="card_header">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pemesanan:</p>
              <p class="fs-7 mb-0">{{ date('d F Y', strtotime($telahsampai->linkOrderTrack->waktu_order)) }}</p>
            </div>
            <div class="badge bg-warning text-black fw-normal">
              {{ $telahsampai->linkOrderTrack->linkStatus->nama ?? null }}
            </div>
          </div>
          @php
            $i = 0;
            $first_item;
            foreach ($telahsampai->linkOrderItem as $orderItem) {
                if ($i == 0) {
                    $first_item = $orderItem;
                    $i++;
                }
            }
          @endphp
          <div class="card_body mt-2">
            <div class="d-flex">
              @if ($first_item->linkItem->gambar)
                <img src="{{ asset('storage/item/' . $first_item->linkItem->gambar) }}"
                  class="img-fluid img_riwayat">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_riwayat">
              @endif
              <div class="ms-3">
                <p class="mb-0 fw-bold">{{ $first_item->linkItem->nama }}</p>
                <p class="mb-0 fs-7">{{ $first_item->kuantitas }} barang</p>
              </div>
            </div>

            @if ($telahsampai->linkOrderItem->count() - 1 > 0)
              <p class="fs-7 mb-0">+ {{ $telahsampai->linkOrderItem->count() - 1 }} item lainnya</p>
            @endif
          </div>

          <div class="modal fade" id="order{{ $telahsampai->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Detail Pesanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="info-pesanan">
                    <span><b>Tanggal pesan</b>
                      {{ date('d F Y', strtotime($telahsampai->linkOrderTrack->waktu_order)) }}</span>
                    <span><b>Status pesanan</b>{{ $telahsampai->linkOrderTrack->linkStatus->nama ?? null }}</span>
                    <span><b>Invoice</b>{{ $telahsampai->linkInvoice->nomor_invoice }}</span>
                    <span><b>Kode Event</b>{{ $telahsampai->linkInvoice->linkEvent->kode ?? '-' }}</span>
                    @if ($telahsampai->linkInvoice->linkEvent && $telahsampai->linkInvoice->linkEvent->diskon != null)
                      <span><b>Diskon</b>{{ $telahsampai->linkInvoice->linkEvent->diskon }}%</span>
                    @elseif($telahsampai->linkInvoice->linkEvent && $telahsampai->linkInvoice->linkEvent->potongan != null)
                      <span><b>Potongan</b>Rp.
                        {{ number_format($telahsampai->linkInvoice->linkEvent->potongan, 0, '', '.') }}</span>
                    @endif
                    @if ($telahsampai->linkOrderTrack->estimasi_waktu_pengiriman)
                      <span><b>Estimasi
                          pengiriman</b>{{ $telahsampai->linkOrderTrack->estimasi_waktu_pengiriman }} hari</span>
                    @endif
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center">Nama Barang</th>
                          <th scope="col" class="text-center">Kuantitas</th>
                          <th scope="col" class="text-center">Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($telahsampai->linkOrderItem as $orderItem)
                          <tr>
                            <td>{{ $orderItem->linkItem->nama }}</td>
                            <td>{{ $orderItem->kuantitas }} x
                              {{ number_format($orderItem->harga_satuan, 0, '', '.') }}</td>
                            <td>{{ number_format($orderItem->kuantitas * $orderItem->harga_satuan, 0, '', '.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <div class="tab-pane" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
      @foreach ($ditolaks as $ditolak)
        <div class="card_pesanan" data-bs-toggle="modal" data-bs-target="#order{{ $ditolak->id }}">
          <div class="card_header">
            <div>
              <p class="fs-7 mb-0 fw-bold">Tanggal pemesanan:</p>
              <p class="fs-7 mb-0">{{ date('d F Y', strtotime($ditolak->linkOrderTrack->waktu_order)) }}</p>
            </div>
            <div class="badge bg-warning text-black fw-normal">
              {{ $ditolak->linkOrderTrack->linkStatus->nama ?? null }}</div>
          </div>

          @php
            $i = 0;
            $first_item;
            foreach ($ditolak->linkOrderItem as $orderItem) {
                if ($i == 0) {
                    $first_item = $orderItem;
                    $i++;
                }
            }
          @endphp

          <div class="card_body mt-2">
            <div class="d-flex">
              @if ($first_item->linkItem->gambar)
                <img src="{{ asset('storage/item/' . $first_item->linkItem->gambar) }}"
                  class="img-fluid img_riwayat">
              @else
                <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_riwayat">
              @endif
              <div class="ms-3">
                <p class="mb-0 fw-bold">{{ $first_item->linkItem->nama }}</p>
                <p class="mb-0 fs-7">{{ $first_item->kuantitas }} barang</p>
              </div>
            </div>

            @if ($ditolak->linkOrderItem->count() - 1 > 0)
              <p class="fs-7 mb-0">+ {{ $ditolak->linkOrderItem->count() - 1 }} item lainnya</p>
            @endif
          </div>

          <div class="modal fade" id="order{{ $ditolak->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Detail Pesanan</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="info-pesanan">
                    <span><b>Tanggal pesan</b>
                      {{ date('d F Y', strtotime($ditolak->linkOrderTrack->waktu_order)) }}</span>
                    <span><b>Status pesanan</b>{{ $ditolak->linkOrderTrack->linkStatus->nama ?? null }}</span>
                    <span><b>Invoice</b>{{ $ditolak->linkInvoice->nomor_invoice }}</span>
                    <span><b>Kode Event</b>{{ $ditolak->linkInvoice->linkEvent->kode ?? '-' }}</span>
                    @if ($ditolak->linkInvoice->linkEvent && $ditolak->linkInvoice->linkEvent->diskon != null)
                      <span><b>Diskon</b>{{ $ditolak->linkInvoice->linkEvent->diskon }}%</span>
                    @elseif($ditolak->linkInvoice->linkEvent && $ditolak->linkInvoice->linkEvent->potongan != null)
                      <span><b>Potongan</b>Rp.
                        {{ number_format($ditolak->linkInvoice->linkEvent->potongan, 0, '', '.') }}</span>
                    @endif
                    @if ($ditolak->linkOrderTrack->estimasi_waktu_pengiriman)
                      <span><b>Estimasi
                          pengiriman</b>{{ $ditolak->linkOrderTrack->estimasi_waktu_pengiriman }} hari</span>
                    @endif
                  </div>
                  <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center">Nama Barang</th>
                          <th scope="col" class="text-center">Kuantitas</th>
                          <th scope="col" class="text-center">Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($ditolak->linkOrderItem as $orderItem)
                          <tr>
                            <td>{{ $orderItem->linkItem->nama }}</td>
                            <td>{{ $orderItem->kuantitas }} x
                              {{ number_format($orderItem->harga_satuan, 0, '', '.') }}</td>
                            <td>{{ number_format($orderItem->kuantitas * $orderItem->harga_satuan, 0, '', '.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@endsection
