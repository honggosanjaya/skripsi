
@extends('customer.layouts.customerLayouts')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <h4>{{ $data->nama }}</h4>
            <h6 class="fw-normal">{{ $data->email }}</h6>
        </div>

        <div class="row border border-1 p-3">
            <h5 class="mb-4">Pesanan Saya</h5>
            <a class="btn btn-primary" href="/customer/profil/pesanan/{{ $data->id }}">Lihat Riwayat Pesanan</a>
        </div>
    </div>
@endsection