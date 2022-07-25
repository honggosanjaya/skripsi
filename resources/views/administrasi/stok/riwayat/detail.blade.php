@extends('layouts/main')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/riwayat">Riwayat Pengadaan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection
@section('main_content')
  <div class="pt-4 px-5">
    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th scope="col">Kode</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Satuan</th>
          <th scope="col">Total Harga</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($pengadaans as $pengadaan)
          <tr>
            <th scope="row">{{ $pengadaan->linkItem->kode_barang }}</th>
            <td>{{ $pengadaan->linkItem->nama }}</td>
            <td>{{ number_format($pengadaan->kuantitas, 0, '', '.') }}</td>
            <td>{{ $pengadaan->linkItem->satuan }}</td>
            <td>{{ number_format($pengadaan->harga_total, 0, '', '.') }}</td>
          </tr>
        @endforeach
        <tr>
          <td><a class="btn btn_purple"
              href="/administrasi/stok/riwayat/detail/{{ $detail->no_pengadaan }}/cetak-pdf">Unduh NPB</a></td>
          <td></td>
          <td></td>
          <td class="fw-bold">Total</td>
          <td class="fw-bold">{{ number_format($total_harga->harga, 0, '', '.') }}</td>
        </tr>
      </tbody>
    </table>

    <table class="table">
      <tbody>
        <tr>
          <td scope="col" style="width: 15%"><b>No Nota</b></td>
          <td style="width: 34%">{{ $detail->no_nota }}</td>
          <td style="width: 10%"><b>Tanggal</b></td>
          <td>{{ date('d M Y G:i', strtotime($detail->created_at)) }}</td>
        </tr>
        <tr>
          <td><b>Keterangan</b></td>
          <td colspan="3">{{ $detail->keterangan }}</td>
        </tr>
      </tbody>
    </table>
  </div>
@endsection
