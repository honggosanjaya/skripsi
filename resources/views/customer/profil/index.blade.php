
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

        <a href="/customer/profil/detailprofil" class="link-style">
        <div class="row border border-1 p-2 mt-4">            
            <div class="col-11">
                <h5>Detail Profil</h5>
            </div>
            <div class="col-1">
                <i class="bi bi-chevron-right"></i>
            </div>            
        </div>
        </a>

        <a href="/customer/profil/ubahpassword" class="link-style">
        <div class="row border border-1 p-2">
            <div class="col-11">
                <h5>Ganti Password</h5>
            </div>
            <div class="col-1">
                <i class="bi bi-chevron-right"></i>
            </div>
        </div>
        </a>

    </div>
@endsection