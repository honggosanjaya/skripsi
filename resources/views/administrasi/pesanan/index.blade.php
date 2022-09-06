<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Pesanan</li>
  </ol>
@endsection
@section('main_content')
  @if (session()->has('addPesananSuccess'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
        {{ session('addPesananSuccess') }}
        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
      </div>
    </div>
  @endif


  <div class="px-5 pt-4">
    {{-- <a href="/administrasi/lp3" class="btn btn-primary btn_add-relative">
      <span class="iconify fs-4 me-1" data-icon="tabler:report-money"></span> Buat LP3
    </a> --}}

    <table class="table table-hover table-sm mt-4" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama Customer</th>
          <th scope="col" class="text-center">Nama Sales</th>
          <th scope="col" class="text-center">Nomor Invoice</th>
          <th scope="col" class="text-center">Harga Total (Rp)</th>
          <th scope="col" class="text-center">Status Pesanan</th>
          <th scope="col" class="text-center">Tanggal Dibuat</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orders as $order)
          <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $order->linkCustomer->nama ?? null }}</td>
            <td>{{ $order->linkStaff->nama ?? null }}</td>
            <td>{{ $order->linkInvoice->nomor_invoice ?? null }}</td>
            @if ($order->linkInvoice)
              <td>{{ number_format($order->linkInvoice->harga_total ?? 0, 0, '', '.') }}</td>
            @else
              <td></td>
            @endif

            @if ($order->linkOrderTrack->status_enum ?? null)
              <td class="text-capitalize">
                <p class="mb-0 badge badge-order-track-{{ $order->linkOrderTrack->status_enum }}">
                  @if ($order->linkOrderTrack->status_enum == '0')
                    {{ 'Diajukan Customer' }}
                  @elseif ($order->linkOrderTrack->status_enum == '1')
                    {{ 'Diajukan Salesman' }}
                  @elseif ($order->linkOrderTrack->status_enum == '2')
                    {{ 'Dikonfirmasi Admin' }}
                  @elseif ($order->linkOrderTrack->status_enum == '3')
                    {{ 'Dalam Perjalanan' }}
                  @elseif ($order->linkOrderTrack->status_enum == '4')
                    {{ 'Order Telah Sampai' }}
                  @elseif ($order->linkOrderTrack->status_enum == '5')
                    {{ 'Pembayaran' }}
                  @elseif ($order->linkOrderTrack->status_enum == '6')
                    {{ 'Order Selesai' }}
                  @elseif ($order->linkOrderTrack->status_enum == '-1')
                    {{ 'Order Ditolak' }}
                  @endif
                </p>
              </td>
            @endif

            <td>{{ date('d M Y', strtotime($order->created_at ?? '-')) }}</td>
            <td>
              <a href="/administrasi/pesanan/detail/{{ $order->id }}" class="btn btn-primary">
                <span class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span> Detail</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
