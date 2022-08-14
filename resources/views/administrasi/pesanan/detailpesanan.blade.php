<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
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
    @if (session()->has('addPesananSuccess'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
          {{ session('addPesananSuccess') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif

    @if (session()->has('pesanError'))
      <div id="hideMeAfter3Seconds">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('pesanError') }}
          <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
        </div>
      </div>
    @endif


    <div class="container">
      <div class="row mt-3">
        <div class="d-flex flex-row justify-content-between">

          <div>
            {{-- @if ($order->linkOrderTrack->status > 20 && $order->linkOrderTrack->status < 25)
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-memo" class="btn btn-primary mx-1"><i
                  class="bi bi-download px-1"></i>Unduh Memo Persiapan Barang</a>
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-invoice" class="btn btn_purple mx-1"><i
                  class="bi bi-download px-1"></i>Unduh Invoice</a>
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-sj" class="btn btn-success mx-1"><i
                  class="bi bi-download px-1"></i>Unduh Surat Jalan</a>
            @endif --}}
            @if ($order->linkOrderTrack->status == 21)
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-memo" class="btn btn-primary mx-1"><i
                  class="bi bi-download px-1"></i>Unduh Memo Persiapan Barang</a>
            @endif
            @if ($order->linkOrderTrack->status > 21 && $order->linkOrderTrack->status < 25)
              <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-sj" class="btn btn-success mx-1"><i
                  class="bi bi-download px-1"></i>Unduh Surat Jalan</a>
            @endif
            @if ($order->linkOrderTrack->status > 20 && $order->linkOrderTrack->status < 25)
              {{-- @php
                $counter_unduh = $order->linkInvoice->counter_unduh ?? null;
                $max_unduh = $order->linkInvoice->max_unduh ?? null;
              @endphp --}}
              @if ($order->linkInvoice->counter_unduh < $order->linkInvoice->max_unduh)
                <a href="/administrasi/pesanan/detail/{{ $order->id }}/cetak-invoice" class="btn btn_purple mx-1"><i
                    class="bi bi-download px-1"></i>Unduh Invoice
                  {{ '(' . $order->linkInvoice->counter_unduh . '/' . $order->linkInvoice->max_unduh . ')' }}</a>
              @else
                <button class="btn btn_purple mx-1" disabled><i class="bi bi-download px-1"></i>Unduh Invoice
                  {{ '(' . $order->linkInvoice->counter_unduh . '/' . $order->linkInvoice->max_unduh . ')' }}</button>
              @endif
              {{-- <h1>{{ $order->linkInvoice->counter_unduh }} </h1> --}}
            @endif
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col">
          <div class="informasi-list d-flex flex-column">
            <span><b>Customer Pemesan</b> {{ $order->linkCustomer->nama }}</span>
            <span><b>Nomor Invoice</b> {{ $order->linkInvoice->nomor_invoice ?? null }}</span>
            <span><b>Status Pesanan</b>
              @if ($order->linkOrderTrack->status == 25)
                <p class="text-danger fw-bold d-inline">{{ $order->linkOrderTrack->linkStatus->nama }}</p>
              @else
                <p class="text-success fw-bold d-inline">{{ $order->linkOrderTrack->linkStatus->nama }}</p>
              @endif
            </span>
          </div>
        </div>
        <div class="col">
          <div class="informasi-list d-flex flex-column">
            <span><b>Sales Bersangkutan</b> {{ $order->linkStaff->nama ?? null }}</span>
            <span><b>Tanggal Pesan</b> {{ date('d M Y', strtotime($order->linkInvoice->created_at ?? '-')) }}</span>
          </div>
          <div class="mt-3">
            @if ($order->linkOrderTrack->status > 20 && $order->linkOrderTrack->status < 25)
              <a class="btn btn-warning mt-1 d-inline me-3"
                href="/administrasi/pesanan/detail/{{ $order->id }}/kapasitas"><i
                  class="bi bi-eye-fill p-1"></i>Kapasitas Kendaraan</a>
            @endif

            @if ($order->linkOrderTrack->status >= 22 && $order->linkOrderTrack->status < 25)
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
              <th scope="col">Nama Barang</th>
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
                <td>{{ $item->linkItem->kode_barang ?? null }}</td>
                <td>{{ $item->linkItem->nama ?? null }}</td>
                <td>{{ number_format($item->harga_satuan, 0, '', '.') }}</td>
                <td>{{ $item->kuantitas }}</td>
                <td>{{ number_format($item->harga_satuan * $item->kuantitas, 0, '', '.') }}</td>
                @php
                  $ttl += $item->harga_satuan * $item->kuantitas;
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
                <td>{{ number_format($order->linkInvoice->harga_total, 0, '', '.') }}</td>
              @else
                <td>menunggu pesanan dikonfirmasi</td>
              @endif
            </tr>
          </tbody>
        </table>

        @if ($order->linkOrderTrack->status != 25)
          <div class="stepper-wrapper d-flex align-items-end">
            <div class="stepper-item-date ">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_order)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_order)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_diteruskan)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_diteruskan)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date ">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_dikonfirmasi)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_dikonfirmasi)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_berangkat)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_berangkat)) }}
                @endif
              </div>
            </div>
            <div class="stepper-item-date">
              <div class="step-name">
                @if ($order->linkOrderTrack->waktu_sampai)
                  {{ date('F j, Y, g:i a', strtotime($order->linkOrderTrack->waktu_sampai)) }}
                @endif
              </div>
            </div>
          </div>
          <div class="stepper-wrapper status-track" data-status="{{ $order->linkOrderTrack->status }}">
            <div class="stepper-item s-19">
              <div class="step-counter">1</div>
              <div class="step-name">order</div>
            </div>
            <div class="stepper-item s-20">
              <div class="step-counter">2</div>
              <div class="step-name">sales</div>
            </div>
            <div class="stepper-item s-21">
              <div class="step-counter">3</div>
              <div class="step-name">dikonfirmasi</div>
            </div>
            <div class="stepper-item s-22">
              <div class="step-counter">4</div>
              <div class="step-name">dikirim</div>
            </div>
            <div class="stepper-item s-23">
              <div class="step-counter">5</div>
              <div class="step-name">sampai</div>
            </div>
          </div>
        @endif

        @if ($order->linkOrderTrack->status == 20)
          <div class="float-end">
            <form action="/administrasi/pesanan/setuju/{{ $order->id }}" method="POST" class="d-inline"
              id="terimapesanan">
              @csrf
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
        @elseif($order->linkOrderTrack->status == 21)
          <div class="float-end">
            <a class="btn btn-primary mt-3" href="/administrasi/pesanan/detail/{{ $order->id }}/pengiriman">
              <i class="bi bi-truck me-2"></i>Atur Keberangkatan Pengiriman
            </a>
          </div>
        @elseif($order->linkOrderTrack->status == 23)
          <div class="float-end">
            <form class="form-submit" method="POST" id="pesananselesai"
              action="/administrasi/pesanan/detail/{{ $order->id }}/dikirimkan">
              @csrf
              <button type="submit" class="btn btn-success pesanan_selesai mt-4"><span class="iconify fs-3 me-1"
                  data-icon="akar-icons:double-check"></span>Pesanan Selesai</button>
            </form>
          </div>
        @endif
        @if ($order->linkOrderTrack->status == 24)
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
