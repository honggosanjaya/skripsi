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

<div id="opname">
    <a href="/administrasi/stok" class="btn btn-primary my-3"><i class="bi-arrow-left"></i> Kembali</a>
    <h1>Riwayat Stok Opname</h1>
  
  
    <table class="table table-hover table-sm mt-3">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col">Tanggal</th>
          <th scope="col">Nama Staff</th>   
          <th scope="col">Action</th>       
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                <td>{{ $order->linkStaff->nama }}</td>
                <td>
                    <a href="/administrasi/stok/opname/riwayat/detail/{{ $order->id }}" class="btn btn-primary">
                        Detail
                    </a>
                </td>
            </tr>
        @endforeach
      </tbody>
    </table>
  
</div>

<div class="d-flex flex-row mt-4">
    {{ $orders->links() }}
   </div>
@endsection
