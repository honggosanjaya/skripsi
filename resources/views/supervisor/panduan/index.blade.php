@extends('layouts/main')

@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Panduan</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 px-5">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="kinerja-tab" data-bs-toggle="tab" data-bs-target="#kinerja-tab-pane"
          type="button" role="tab" aria-controls="kinerja-tab-pane" aria-selected="true">Kinerja Sales</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="penjualan-tab" data-bs-toggle="tab" data-bs-target="#penjualan-tab-pane"
          type="button" role="tab" aria-controls="penjualan-tab-pane" aria-selected="false">Penjualan</button>
      </li>
    </ul>

    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="kinerja-tab-pane" role="tabpanel" aria-labelledby="kinerja-tab"
        tabindex="0">
        <h1 class="fs-5 my-3">Panduan Perhitungan Laporan Kinerja Salesman</h1>
        <p>Laporan kinerja salesman mencakup jumlah kunjungan, jumlah effective call, jumlah customer baru, dan total
          penjualan yang didapatkan</p>
        <p>Total penjualan ini kalau kami tidak salah berdasarkan invoice, sehingga mendapatkan pengurangan dari diskon
          tipe
          customer dan diskon event</p>
      </div>

      <div class="tab-pane fade" id="penjualan-tab-pane" role="tabpanel" aria-labelledby="penjualan-tab" tabindex="0">
        <h1 class="fs-5 my-3">Panduan Perhitungan Laporan Penjualan</h1>
        <p>Laporan Penjualan didapatkan dari data pesanan yang sudah dikirimkan, sudah dibayarkan, dan sudah selesai</p>
      </div>
    </div>
  </div>
@endsection
