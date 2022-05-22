<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Kendaraan</li>
</ol>
@endsection
@section('main_content')
@if(session()->has('addKendaraanSuccess'))
<div id="hideMeAfter3Seconds">
  <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" >
    {{ session('addKendaraanSuccess') }}
    <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
</div>
</div>
            
@endif

@if(session()->has('updateKendaraanSuccess'))
<div id="hideMeAfter3Seconds">
  <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" >
    {{ session('updateKendaraanSuccess') }}
    <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
</div>
</div>
            
@endif

<div class="container">
  <div class="row">
    <div class="col-5">
      <div class="mt-3 search-box">
        <form method="GET" action="/administrasi/kendaraan/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Kendaraan..."
            value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">Cari</button>   
          </div>
          
        </form>    
        
      </div>
    </div>
    <div class="col-4">
      <a href="/administrasi/kendaraan/tambah" class="btn btn-primary my-3 py-2">Tambah Kendaraan</a>
    </div>
  </div>
</div>


<table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Plat Nomor Kendaraan</th>
      <th scope="col">Nama Kendaraan</th>
      <th scope="col">Kapasitas Volume (Cm3)</th>
      <th scope="col">Kapasitas Harga (Rp)</th>
      <th scope="col">Action</th>      
    </tr>
  </thead>
  <tbody>
    @foreach ($vehicles as $vehicle)
        <tr>
            <td>{{ ($vehicles->currentPage() - 1) * $vehicles->perPage() + $loop->iteration }}</td>
            <td>{{ $vehicle->kode_kendaraan }}</td>
            <td>{{ $vehicle->nama }}</td>
            <td>{{ number_format($vehicle->kapasitas_volume,0,"",".") }}</td>
            <td>{{ number_format($vehicle->kapasitas_harga,0,"",".") }}</td>
            <td>
                <a href="/administrasi/kendaraan/ubah/{{ $vehicle->id }}" class="btn btn-warning">Ubah</a>
            </td>
        </tr>
    @endforeach
  </tbody>
</table>

<div class="d-flex flex-row mt-4">
 {{ $vehicles->links() }}
</div>

@endsection