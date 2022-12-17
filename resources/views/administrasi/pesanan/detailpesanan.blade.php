@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@push('JS')
  <script src="{{ mix('js/administrasi.js') }}"></script>
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/pesanan">Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Pesanan</li>
  </ol>
@endsection
@section('main_content')
  <div id="detail-pesanan-admin">
    @if (session()->has('successMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('successMessage') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    @if (session()->has('errorMessage'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('errorMessage') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    <div class="container" id="detail_pesanan-admn">
      <div class="row mt-3 detail-pesanan-admin_action">
        <div class="d-flex flex-row justify-content-between">
          @if ($order->linkOrderTrack->status_enum)
            <div>
              @if ($order->linkOrderTrack->status_enum == '2')
                <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-memo" class="btn btn-primary mx-1"><i
                    class="bi bi-download px-1"></i>Unduh Memo Persiapan Barang</a>
              @endif

              @if ($order->linkOrderTrack->status_enum > '2' && $order->linkOrderTrack->status_enum <= '6')
                <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-sj" class="btn btn-success mx-1"><i
                    class="bi bi-download px-1"></i>Unduh Surat Jalan</a>
              @endif

              @if ($order->linkOrderTrack->status_enum > '1' && $order->linkOrderTrack->status_enum <= '6')
                <button class="btn btn_purple mx-1 btn-unduh-invoice" value="{{ $order->id }}">
                  <i class="bi bi-download px-1"></i>Unduh Invoice
                </button>
              @endif

              <button class="btn btn-primary btn-print-pdf d-none mx-1">
                <span class="iconify fs-4 me-1" data-icon="fluent:print-16-regular"></span>Print
              </button>
              <button class="btn btn-danger btn-close-pdf d-none">
                <span class="iconify fs-4 me-1" data-icon="ep:circle-close"></span>Tutup Invoice
              </button>
            </div>
          @endif
        </div>
      </div>

      <div class="row mt-5">
        <div class="col">
          <div class="informasi-list d-flex flex-column">
            <span><b>Customer Pemesan</b> {{ $order->linkCustomer->nama ?? null }}</span>
            <span><b>Nomor Invoice</b> {{ $order->linkInvoice->nomor_invoice ?? null }}</span>
            @if ($order->linkOrderTrack->status_enum)
              <span><b>Status Pesanan</b>
                @if ($order->linkOrderTrack->status_enum == '-1')
                  <p class="text-danger fw-bold d-inline">Order ditolak</p>
                @elseif ($order->linkOrderTrack->status_enum == '0')
                  <p class="text-success fw-bold d-inline">Diajukan customer</p>
                @elseif ($order->linkOrderTrack->status_enum == '1')
                  <p class="text-success fw-bold d-inline">Diajukan salesman</p>
                @elseif ($order->linkOrderTrack->status_enum == '2')
                  <p class="text-success fw-bold d-inline">Dikonfirmasi admin</p>
                @elseif ($order->linkOrderTrack->status_enum == '3')
                  <p class="text-success fw-bold d-inline">Dalam perjalanan</p>
                @elseif ($order->linkOrderTrack->status_enum == '4')
                  <p class="text-success fw-bold d-inline">Order telah sampai</p>
                @elseif ($order->linkOrderTrack->status_enum == '5')
                  <p class="text-success fw-bold d-inline">Pembayaran</p>
                @elseif ($order->linkOrderTrack->status_enum == '6')
                  <p class="text-success fw-bold d-inline">Order selesai</p>
                @endif
              </span>
            @endif
          </div>
        </div>
        <div class="col">
          <div class="informasi-list d-flex flex-column">
            <span><b>Sales Bersangkutan</b> {{ $order->linkStaff->nama ?? null }}</span>
            <span><b>Tanggal Pesan</b> {{ date('d M Y', strtotime($order->linkInvoice->created_at ?? null)) }}</span>
          </div>
          <div class="mt-3">
            @if ($order->linkOrderTrack->status_enum >= '2' && $order->linkOrderTrack->status_enum <= '6')
              <a class="btn btn-warning mt-1 d-inline me-3"
                href="/administrasi/pesanan/detail/{{ $order->id }}/kapasitas"><i
                  class="bi bi-eye-fill p-1"></i>Kapasitas Kendaraan</a>
            @endif

            @if ($order->linkOrderTrack->status_enum >= '3' && $order->linkOrderTrack->status_enum <= '6')
              <a class="btn btn-primary mt-3 d-inline"
                href="/administrasi/pesanan/detail/{{ $order->id }}/pengiriman">
                <span class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Detail Pengiriman
              </a>
            @endif
          </div>
        </div>
      </div>

      <div>
        <table class="table table-bordered mt-4">
          <thead>
            <tr>
              <th scope="col">Kode Barang</th>
              <th scope="col" style="width: 300px">Nama Barang</th>
              <th scope="col">Harga Satuan</th>
              <th scope="col">Kuantitas</th>
              <th scope="col">Harga Total</th>
            </tr>
          </thead>
          <tbody>
            @php
              $ttl = 0;
            @endphp
            @foreach ($items as $item)
              <tr>
                <td>{{ $item['original']->linkItem->kode_barang ?? null }}</td>
                <td>
                  <form action="/administrasi/changeorderitem/{{ $item['original']->id }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="nama-item">{{ $item['original']->linkItem->nama ?? null }}</span>
                      @if ($order->linkOrderTrack->status_enum == '1')
                        <select style="width: 170px" class="form-select select-alt-item d-none" name="id_item_serupa">
                          @foreach ($item['itemSerupa'] as $itm)
                            @if ($itm->id == $item['original']->linkItem->id)
                              <option value="{{ $itm->id }}" selected>{{ $itm->nama }}</option>
                            @else
                              <option value="{{ $itm->id }}">{{ $itm->nama }}</option>
                            @endif
                          @endforeach
                        </select>
                        <button type="button" class="btn btn-warning change-item-btn">Ubah</button>
                        <button type="submit" class="btn btn-success ok-item-btn d-none">OK</button>
                      @endif
                    </div>
                  </form>
                </td>
                <td>{{ number_format($item['original']->harga_satuan ?? 0, 0, '', '.') }}</td>
                <td>{{ $item['original']->kuantitas ?? null }}</td>
                @if ($item['original']->harga_satuan && $item['original']->kuantitas)
                  <td>{{ number_format($item['original']->harga_satuan * $item['original']->kuantitas, 0, '', '.') }}
                  </td>
                @else
                  <td></td>
                @endif
                @php
                  $ttl += $item['original']->harga_satuan * $item['original']->kuantitas;
                @endphp
              </tr>
            @endforeach


            @if ($order->linkInvoice != null && $order->linkInvoice->id_event != null)
              <tr>
                <td colspan="4" class="text-center fw-bold">Potongan event
                  {{ $order->linkInvoice->linkEvent->nama }}
                  : </td>
                <td>{{ number_format($order->linkInvoice->harga_total - $ttl, 0, '', '.') }}</td>
              </tr>
            @endif
            <tr>
              <td colspan="4" class="text-center fw-bold">Total (Setelah event & diskon jenis Cust) : </td>
              @if ($order->linkInvoice->harga_total ?? null)
                <td>{{ number_format($order->linkInvoice->harga_total + $total_retur, 0, '', '.') }}</td>
              @else
                <td>menunggu pesanan dikonfirmasi</td>
              @endif
            </tr>

            @if ($order->linkOrderTrack->status_enum >= '4')
              <tr>
                <td colspan="4" class="text-center fw-bold">Total Pembayaran : </td>
                <td>{{ number_format($total_bayar, 0, '', '.') }}</td>
              </tr>
              <tr>
                <td colspan="4" class="text-center fw-bold">Total Retur : </td>
                <td>{{ number_format($total_retur, 0, '', '.') }}</td>
              </tr>
            @endif

            @if ($order->linkOrderTrack->status_enum == '4')
              <tr>
                <td colspan="4" class="text-center fw-bold">Sisa Pembayaran : </td>
                <td>{{ number_format($order->linkInvoice->harga_total - $total_bayar, 0, '', '.') }}</td>
              </tr>
            @endif
          </tbody>
        </table>

        @if ($order->linkOrderTrack->status_enum != '-1')
          <div class="stepper-wrapper d-flex align-items-end">
            <div class="stepper-item-date ">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_order ?? null)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_order)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_diteruskan ?? null)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_diteruskan)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date ">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_dikonfirmasi ?? null)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_dikonfirmasi)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_berangkat ?? null)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_berangkat)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_sampai ?? null)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_sampai)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($pembayaran_terakhir->tanggal ?? null)
                  {{ date('F j, Y, g:i a', strtotime($pembayaran_terakhir->created_at)) }}
                  {{-- {{ date('F j, Y', strtotime($pembayaran_terakhir->tanggal)) }} --}}
                @endif
              </div>
            </div>
          </div>

          <div class="stepper-wrapper status-track" data-status="{{ $order->linkOrderTrack->status_enum ?? null }}">
            <div class="stepper-item s-0">
              <div class="step-counter">1</div>
              <div class="step-name">order</div>
            </div>
            <div class="stepper-item s-1">
              <div class="step-counter">2</div>
              <div class="step-name">sales</div>
            </div>
            <div class="stepper-item s-2">
              <div class="step-counter">3</div>
              <div class="step-name">dikonfirmasi</div>
            </div>
            <div class="stepper-item s-3">
              <div class="step-counter">4</div>
              <div class="step-name">dikirim</div>
            </div>
            <div class="stepper-item s-4">
              <div class="step-counter">5</div>
              <div class="step-name">sampai</div>
            </div>
            <div class="stepper-item s-5">
              <div class="step-counter">6</div>
              <div class="step-name">pembayaran</div>
            </div>
          </div>
        @endif

        @if ($order->linkOrderTrack->status_enum == '1')
          <div class="row justify-content-end mt-5">
            <div class="col-12">

              <form action="/administrasi/pesanan/setuju/{{ $order->id }}" method="POST" class="d-inline"
                id="terimapesanan">
                @csrf
                <div class="row">
                  <div class="col">
                    <div class="mb-3">
                      <label for="id_vehicle" class="form-label">Pilih Kendaraan</label>
                      <select class="form-select" name="id_vehicle">
                        @foreach ($activeVehicles as $vehicle)
                          <option value="{{ $vehicle->id }}">{{ $vehicle->nama }}</option>
                        @endforeach

                        @foreach ($inactiveVehicles as $vehicle)
                          <option value="{{ $vehicle->id }}" disabled>{{ $vehicle->nama }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn btn-sm btn-success btn_terimaPesanan me-4">
                  <span class="iconify fs-3 me-1" data-icon="akar-icons:double-check"></span>Setuju
                </button>
              </form>

              <form action="/administrasi/pesanan/tolak/{{ $order->id }}" method="POST" id="tolakpesanan"
                class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger btn_tolakPesanan">
                  <span class="iconify fs-3 me-1" data-icon="akar-icons:circle-x"></span>Tolak
                </button>
              </form>

            </div>
          </div>
        @elseif($order->linkOrderTrack->status_enum == '2')
          <div class="float-end">
            <a class="btn btn-primary mt-3" href="/administrasi/pesanan/detail/{{ $order->id }}/pengiriman">
              <i class="bi bi-truck me-2"></i>Atur Keberangkatan Pengiriman
            </a>
          </div>
        @elseif($order->linkOrderTrack->status_enum == '4')
          <div class="float-end">
            @if ($returs ?? null)
              @if (count($returs) > 0)
                <button type="button" class="btn btn-warning mt-3 me-2" data-bs-toggle="modal"
                  data-bs-target="#returModal">
                  <span class="iconify fs-4 me-1" data-icon="tabler:truck-return"></span>Lihat Retur Terkait
                </button>

                <div class="modal fade" id="returModal" tabindex="-1" aria-labelledby="returModalLabel"
                  aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="returModalLabel">Rincian Retur
                          {{ $order->linkInvoice->nomor_invoice ?? null }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        @foreach ($returs as $retur)
                          <div class="detailOrder_rincianRetur">
                            <div>
                              <p class="mb-0">{{ $retur[0]->no_retur ?? null }}</p>
                              @if ($retur[0]->tipe_retur ?? null)
                                <p class="mb-0">Tipe: {{ $retur[0]->tipe_retur == 1 ? 'Potongan' : 'Tukar Guling' }}
                                </p>
                              @endif

                              @php
                                $subtotal_retur = 0;
                              @endphp

                              <div class="table-responsive">
                                <table class="table table-bordered mt-2">
                                  <thead>
                                    <tr>
                                      <th scope="col" class="text-center">Nama Item</th>
                                      <th scope="col" class="text-center">Kuantitas</th>
                                      <th scope="col" class="text-center">Harga Satuan</th>
                                      <th scope="col" class="text-center">Subtotal</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($retur as $dt)
                                      @php
                                        if ($retur[0]->tipe_retur == 1) {
                                            $subtotal_retur += ($dt->kuantitas ?? 0) * ($dt->harga_satuan ?? 0);
                                        }
                                      @endphp
                                      <tr>
                                        <td>{{ $dt->linkItem->nama ?? null }}</td>
                                        <td>{{ $dt->kuantitas ?? null }}</td>
                                        @if ($dt->harga_satuan ?? null)
                                          <td>{{ number_format($dt->harga_satuan, 0, '', '.') }}</td>
                                        @else
                                          <td></td>
                                        @endif
                                        <td class="text-end">
                                          {{ number_format(($dt->kuantitas ?? 0) * ($dt->harga_satuan ?? 0), 0, '', '.') }}
                                        </td>
                                      </tr>
                                    @endforeach
                                    @if ($retur[0]->tipe_retur == 1)
                                      <tr>
                                        <td colspan="3" class="text-center"><b>Total Retur</b></td>
                                        <td class="text-end">{{ number_format($subtotal_retur, 0, '', '.') }}</td>
                                      </tr>
                                    @endif
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            @endif

            <a class="btn btn-primary mt-3" href="/administrasi/pesanan/detail/{{ $order->id }}/pembayaran">
              <i class="bi bi-cash-coin me-2"></i>Pembayaran
            </a>
          </div>
        @elseif($order->linkOrderTrack->status_enum == '5')
          <div class="float-end">
            <form class="form-submit" method="POST" id="pesananselesai"
              action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
              @csrf
              <button type="submit" class="btn btn-success pesanan_selesai mt-4"><span class="iconify fs-3 me-1"
                  data-icon="akar-icons:double-check"></span>Pesanan Selesai</button>
            </form>
          </div>
        @endif
        @if ($order->linkOrderTrack->status_enum == '6')
          <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            Pesanan telah dinyatakan sukses oleh {{ $order->linkOrderTrack->linkStaffPengonfirmasi->nama ?? null }}
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
    window.addEventListener("pageshow", function(event) {
      var historyTraversal = event.persisted || (typeof window.performance != "undefined" && window.performance
        .navigation.type === 2);
      if (historyTraversal) {
        window.location.reload();
      }
    });
  </script>
@endsection
