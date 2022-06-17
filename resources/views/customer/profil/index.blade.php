@extends('customer.layouts.customerLayouts')

@section('header')
  <header class='header_mobile d-flex justify-content-between align-items-center'>
    <div class="d-flex">
      <a href="/customer">
        <span class="iconify fs-3 text-white me-2" data-icon="eva:arrow-back-fill"></span>
      </a>
      <h1 class="page_title">Saya</h1>
    </div>
  </header>
@endsection

@section('content')
  <div class="container pt-4 profil">
    <div class="d-flex align-items-center">
      @if ($data->foto != null)
        <img class="profil_picture" src="{{ asset('storage/customer/' . $data->foto) }}">
      @else
        <img class="profil_picture" src="{{ asset('images/default_fotoprofil.png') }}">
      @endif
      <div class="ms-3">
        <h1 class="fs-5 fw-bold mb-0">{{ $data->nama }}</h1>
        <h2 class="fs-6">{{ $data->email }}</h2>
      </div>
    </div>

    <div class="riwayat_order p-3 mt-4">
      <div class="d-flex justify-content-between mb-3">
        <h1 class="fs-6 fw-bold">Pesanan Saya</h1>
        <a href="/customer/profil/pesanan/{{ $data->id }}" class="fs-7 text-decoration-none">Lihat Riwayat Pesanan</a>
      </div>

      <div class="riwayat_wrapper">
        <div class="box_wrap">
          <div class="box">
            <span class="iconify" data-icon="bxs:package"></span>
            <div class="order_quantity">
              {{ $order['diajukan'] }}
            </div>
          </div>
          <p class="mb-0 fs-7 text-center fw-bold">Diajukan</p>
        </div>

        <div class="box_wrap">
          <div class="box">
            <span class="iconify" data-icon="line-md:circle-to-confirm-circle-transition"></span>
            <div class="order_quantity">
              {{ $order['dikonfirmasi'] }}
            </div>
          </div>
          <p class="mb-0 fs-7 text-center fw-bold">Dikonfirmasi</p>
        </div>

        <div class="box_wrap">
          <div class="box">
            <span class="iconify" data-icon="ic:outline-local-shipping"></span>
            <div class="order_quantity">
              {{ $order['dikirim'] }}
            </div>
          </div>
          <p class="mb-0 fs-7 text-center fw-bold">Dikirim</p>
        </div>

        <div class="box_wrap">
          <div class="box">
            <span class="iconify" data-icon="la:file-invoice"></span>
            <div class="order_quantity">
              {{ $order['diterima'] }}
            </div>
          </div>
          <p class="mb-0 fs-7 text-center fw-bold">Diterima</p>
        </div>
      </div>

    </div>

    <div class="akun_saya p-3 mt-4">
      <h1 class="fs-6 fw-bold">Akun Saya</h1>
      <div class="card card_menu">
        <ul class="list-group list-group-flush">
          <a href="/customer/profil/detailprofil" class="text-decoration-none single_card">
            <li class="list-group-item d-flex justify-content-between">
              <h1 class="fs-6 fw-bold mb-0">Detail Profil</h1>
              <span class="iconify" data-icon="ant-design:right-outlined"></span>
            </li>
          </a>

          <a href="/customer/profil/ubahpassword" class="text-decoration-none single_card">
            <li class="list-group-item d-flex justify-content-between">
              <h1 class="fs-6 fw-bold mb-0">Ubah Password</h1>
              <span class="iconify" data-icon="ant-design:right-outlined"></span>
            </li>
          </a>

          <a class="logout_link cursor_pointer text-decoration-none single_card">
            <li class="list-group-item">
              <form method="POST" action="{{ route('logout') }}" id="logout_form">
                @csrf
                <div class="d-flex justify-content-between">
                  <h1 class="fs-6 fw-bold mb-0">Logout</h1>
                  <span class="iconify" data-icon="ant-design:right-outlined"></span>
                </div>
              </form>
            </li>
          </a>

        </ul>
      </div>
    </div>
  </div>
@endsection
