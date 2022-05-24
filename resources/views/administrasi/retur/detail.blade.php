<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item" aria-current="page"><a href="/administrasi/retur">Retur</a></li>
  <li class="breadcrumb-item active" aria-current="page">Detail Retur</li>
</ol>
@endsection
@section('main_content')


<div class="container">
  <div class="row mt-3">
      <div class="d-flex flex-row justify-content-between">
        <a href="/administrasi/retur" class="btn btn-primary mx-1"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>
                
      </div>    
  </div>

  <div class="row mt-3">
    <h4>Retur - {{ $retur->no_retur }}</h4>   
</div>

  
  <div>
    <table class="table table-borderless mt-4">
        <tbody>
            <tr>
                <td style="width: 25%"><h5>Tanggal : </h5></td>
                <td>{{ date('d-m-Y', strtotime($retur->created_at)) }}</td>
            </tr>
            <tr>
                <td><h5>Nama Customer : </h5></td>
                <td>{{ $retur->linkCustomer->nama }}</td>
            </tr>
            <tr>
                <td><h5>Alamat : </h5></td>
                <td>{{ $retur->linkCustomer->alamat_utama . ' ' . $retur->linkCustomer->alamat_nomor }}</td>
            </tr>
            <tr>
                <td><h5>Wilayah : </h5></td>
                <td>{{ $wilayah[0] }}</td>
            </tr>
            <tr>
                <td><h5>No Telepon : </h5></td>
                <td>{{ $retur->linkCustomer->telepon }}</td>
            </tr>
            <tr>
                <td><h5>Pengirim : </h5></td>
                <td>{{ $retur->linkStaffPengaju->nama }}</td>
            </tr>
            <tr>
                <td><h5>Admin : </h5></td>
                <td>{{ $administrasi->nama }}</td>
            </tr>
            <tr>
                <td><h5>Nomor Invoice : </h5></td>
                <td>{{ $retur->linkInvoice->nomor_invoice }}</td>
            </tr>
            
        </tbody>
      </table>
  </div>
  

  <div>
    <table class="table table-bordered mt-5">
        <thead>
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Jumlah</th>
            <th scope="col">Satuan Barang</th>
            <th scope="col">Alasan Retur</th>                
          </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->kode_barang }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->kuantitas }}</td>
                    <td>{{ $item->satuan }}</td>
                    <td>{{ $item->alasan }}</td>
                </tr>
            @endforeach
            
        </tbody>
      </table>

      <div class="row">
          <div class="col-2">
            <h5>Metode retur : </h5>
          </div>
          <div class="col-2">
            <select class="form-select" name="tipe_retur">
                <option value="">-- Pilih Tipe Retur --</option>
                @foreach ($tipeReturs as $tipeRetur)
                    <option value="{{ $tipeRetur->id }}" {{ ( $tipeRetur->id === ($retur->tipe_retur)) ? 'selected' : '' }}>
                        {{ $tipeRetur->nama }}
                    </option>
                @endforeach
            </select>
          </div>        
      </div>

      <div class="row">
          <div class="d-flex flex-row justify-content-end">
            <a href="/administrasi/retur/cetak-retur/{{ $retur->no_retur }}" class="btn btn-warning mx-1"><i class="bi bi-download px-1"></i>Unduh Retur Penjualan</a>
            <a href="" class="btn btn-success mx-1">Konfirmasi</a>
          </div>
        
      </div>
  </div>


</div>



@endsection