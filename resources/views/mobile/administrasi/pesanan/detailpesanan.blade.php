@extends('layouts.mainmobile')
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

    <div class="container pt-4" id="detail_pesanan-admn">
      <div class="detail-pesanan-admin_action">
        @if ($order->linkOrderTrack->status_enum)
          <div class="d-flex justify-content-center">
            @if ($order->linkOrderTrack->status_enum == '2')
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-memo" class="btn btn-primary mb-4 me-3"><i
                  class="bi bi-download px-1"></i>Unduh Memo Persiapan Barang</a>
            @endif

            @if ($order->linkOrderTrack->status_enum > '2' && $order->linkOrderTrack->status_enum <= '6')
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-sj" class="btn btn-success mb-4 me-3"><i
                  class="bi bi-download px-1"></i>Unduh Surat Jalan</a>
            @endif

            @if ($order->linkOrderTrack->status_enum > '1' && $order->linkOrderTrack->status_enum <= '6')
              <button class="btn btn_purple btn-unduh-invoice mb-4 me-3" value="{{ $order->id }}">
                <i class="bi bi-download px-1"></i>Unduh Invoice
              </button>
            @endif
          </div>
          <div class="d-flex justify-content-center">
            <button class="btn btn-primary btn-print-pdf d-none mb-4 me-3">
              <span class="iconify fs-4 me-1" data-icon="fluent:print-16-regular"></span>Print
            </button>
            <button class="btn btn-danger btn-close-pdf d-none mb-4">
              <span class="iconify fs-4 me-1" data-icon="ep:circle-close"></span>Tutup Invoice
            </button>
          </div>
        @endif
      </div>

      <div class="informasi-list">
        <span class="d-flex align-items-center"><b>Customer Pemesan</b>
          <span>{{ $order->linkCustomer->nama ?? null }}</span>
        </span>
        <span class="d-flex align-items-center"><b>Nomor Invoice</b>
          <span>{{ $order->linkInvoice->nomor_invoice ?? null }}</span>
        </span>
        @if ($order->linkOrderTrack->status_enum)
          <span class="d-flex align-items-center"><b>Status Pesanan</b>
            @if ($order->linkOrderTrack->status_enum == '-1')
              <span class="text-danger fw-bold">Order ditolak</span>
            @elseif ($order->linkOrderTrack->status_enum == '0')
              <span class="text-success fw-bold">Diajukan customer</span>
            @elseif ($order->linkOrderTrack->status_enum == '1')
              <span class="text-success fw-bold">Diajukan salesman</span>
            @elseif ($order->linkOrderTrack->status_enum == '2')
              <span class="text-success fw-bold">Dikonfirmasi admin</span>
            @elseif ($order->linkOrderTrack->status_enum == '3')
              <span class="text-success fw-bold">Dalam perjalanan</span>
            @elseif ($order->linkOrderTrack->status_enum == '4')
              <span class="text-success fw-bold">Order telah sampai</span>
            @elseif ($order->linkOrderTrack->status_enum == '5')
              <span class="text-success fw-bold">Pembayaran</span>
            @elseif ($order->linkOrderTrack->status_enum == '6')
              <span class="text-success fw-bold">Order selesai</span>
            @endif
          </span>
        @endif
        <span class="d-flex align-items-center"><b>Sales Bersangkutan</b>
          <span>{{ $order->linkStaff->nama ?? null }}</span>
        </span>
        @if ($order->linkInvoice->created_at ?? null)
          <span class="d-flex align-items-center"><b>Tanggal Pesan</b>
            <span>{{ date('d M Y', strtotime($order->linkInvoice->created_at)) }}</span>
          </span>
        @endif
      </div>

      <div class="my-4 d-flex align-items-center justify-content-center">
        @if ($order->linkOrderTrack->status_enum >= '2' && $order->linkOrderTrack->status_enum <= '6')
          <a class="btn btn-warning me-3" href="/administrasi/pesanan/detail/{{ $order->id }}/kapasitas"><i
              class="bi bi-eye-fill p-1"></i>Kapasitas Kendaraan</a>
        @endif

        @if ($order->linkOrderTrack->status_enum >= '3' && $order->linkOrderTrack->status_enum <= '6')
          <a class="btn btn-primary" href="/administrasi/pesanan/detail/{{ $order->id }}/pengiriman">
            <span class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Detail Pengiriman
          </a>
        @endif
      </div>

      @php
        $ttl = 0;
      @endphp

      @foreach ($items as $item)
        <div class="list-mobile">
          <div class="d-flex justify-content-between align-items-start flex-column mb-3">
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
                  <button type="button" class="btn btn-sm btn-warning change-item-btn ms-3">Ubah</button>
                  <button type="submit" class="btn btn-sm btn-success ok-item-btn d-none ms-3">OK</button>
                @endif
              </div>
            </form>
            <span class="text-secondary">{{ $item['original']->linkItem->kode_barang ?? null }}</span>
          </div>

          <div class="informasi-list">
            <span class="d-flex align-items-center"><b>Harga Satuan</b>
              <span>Rp {{ number_format($item['original']->harga_satuan ?? 0, 0, '', '.') }}</span>
            </span>
            <span class="d-flex align-items-center"><b>Kuantitas</b>
              <span>{{ $item['original']->kuantitas ?? null }}</span>
            </span>
            @if ($item['original']->harga_satuan && $item['original']->kuantitas)
              <span class="d-flex align-items-center"><b>Harga Total</b>
                <span>Rp
                  {{ number_format($item['original']->harga_satuan * $item['original']->kuantitas, 0, '', '.') }}</span>
              </span>
            @endif
          </div>
        </div>
        @php
          $ttl += $item['original']->harga_satuan * $item['original']->kuantitas;
        @endphp
      @endforeach

      <div class="informasi-list my-4">
        @if ($order->linkInvoice != null && $order->linkInvoice->id_event != null)
          <span class="d-flex align-items-center"><b>Potongan event {{ $order->linkInvoice->linkEvent->nama }}</b>
            <span>{{ number_format($order->linkInvoice->harga_total - $ttl, 0, '', '.') }}</span>
          </span>
        @endif
        <span class="d-flex align-items-center"><b>Total (Setelah event & diskon tipe cust)</b>
          @if ($order->linkInvoice->harga_total ?? null)
            <span>{{ number_format($order->linkInvoice->harga_total, 0, '', '.') }}</span>
          @else
            <span>menunggu pesanan dikonfirmasi</span>
          @endif
        </span>

        @if ($order->linkOrderTrack->status_enum >= '4')
          <span class="d-flex align-items-center"><b>Total Pembayaran</b>
            <span>{{ number_format($total_bayar, 0, '', '.') }}</span>
          </span>
        @endif

        @if ($order->linkOrderTrack->status_enum == '4')
          <span class="d-flex align-items-center"><b>Sisa Pembayaran</b>
            <span>{{ number_format($order->linkInvoice->harga_total - $total_bayar, 0, '', '.') }}</span>
          </span>
        @endif
      </div>

      @if ($order->linkOrderTrack->status_enum != '-1')
        <hr class="my-4">
        <div class="stepper-wrapper status-track" data-status="{{ $order->linkOrderTrack->status_enum ?? null }}">
          <div class="stepper-item s-0">
            <div class="step-counter">1</div>
            <div class="step-name">
              <strong>order</strong>
              @if ($order->linkOrderTrack->waktu_order ?? null)
                -> {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_order)) }}
              @endif
            </div>

          </div>
          <div class="stepper-item s-1">
            <div class="step-counter">2</div>
            <div class="step-name">
              <strong>sales</strong>
              @if ($order->linkOrderTrack->waktu_diteruskan ?? null)
                -> {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_diteruskan)) }}
              @endif
            </div>
          </div>
          <div class="stepper-item s-2">
            <div class="step-counter">3</div>
            <div class="step-name">
              <strong>dikonfirmasi</strong>
              @if ($order->linkOrderTrack->waktu_dikonfirmasi ?? null)
                -> {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_dikonfirmasi)) }}
              @endif
            </div>
          </div>
          <div class="stepper-item s-3">
            <div class="step-counter">4</div>
            <div class="step-name">
              <strong>dikirim</strong>
              @if ($order->linkOrderTrack->waktu_berangkat ?? null)
                -> {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_berangkat)) }}
              @endif
            </div>
          </div>
          <div class="stepper-item s-4">
            <div class="step-counter">5</div>
            <div class="step-name">
              <strong>sampai</strong>
              @if ($order->linkOrderTrack->waktu_sampai ?? null)
                -> {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_sampai)) }}
              @endif
            </div>
          </div>
          <div class="stepper-item s-5">
            <div class="step-counter">6</div>
            <div class="step-name">
              <strong>pembayaran</strong>
              @if ($pembayaran_terakhir->tanggal ?? null)
                -> {{ date('F j, Y, g:i a', strtotime($pembayaran_terakhir->created_at)) }}
              @endif
            </div>
          </div>
        </div>
        <hr class="my-4">
      @endif

      @if ($order->linkOrderTrack->status_enum == '1')
        <div class="row justify-content-end">
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
          <a class="btn btn-purple-gradient mt-3" href="/administrasi/pesanan/detail/{{ $order->id }}/pengiriman">
            <i class="bi bi-truck me-2"></i>Atur Keberangkatan Pengiriman
          </a>
        </div>
      @elseif($order->linkOrderTrack->status_enum == '4')
        <div class="float-end">
          <a class="btn btn-purple-gradient mt-3" href="/administrasi/pesanan/detail/{{ $order->id }}/pembayaran">
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
