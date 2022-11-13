@extends('layouts.main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item active" aria-current="page">Riwayat Stok Opname</li>
  </ol>
@endsection
@section('main_content')
  @push('CSS')
    <script src="{{ mix('css/administrasi.css') }}"></script>
  @endpush

  <div id="opname" class="px-5 pt-4">
    <h1 class="fs-4 fw-bold">Riwayat Stok Opname</h1>
    <table class="table table-hover table-sm mt-3" id="table">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Tanggal</th>
          <th scope="col" class="text-center">Nama Staff</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($orders as $order)
          <tr>
            <th scope="row" class="text-center">{{ $loop->iteration }}</th>
            <td class="text-center" data-order="{{ date('Y-m-d', strtotime($order->created_at ?? '-')) }}">
              {{ date('d M Y', strtotime($order->created_at ?? '-')) }}
            </td>
            <td class="text-center">{{ $order->linkStaff->nama ?? null }}</td>
            <td class="text-center">
              <a href="/administrasi/stok/opname/riwayat/detail/{{ $order->id }}" class="btn btn-primary">
                <span class="iconify fs-4" data-icon="fluent:apps-list-detail-24-filled"></span>
                Detail
              </a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

  </div>

  {{-- <div class="d-flex flex-row mt-4">
    {{ $orders->links() }}
</div> --}}
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
