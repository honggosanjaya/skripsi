@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
  <li class="breadcrumb-item active" aria-current="page">Riwayat Pengadaan</li>
</ol>
@endsection
@section('main_content')
  
  <div class="container">

    <a class="btn btn-primary mt-4" href="/administrasi/stok"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>

    <div class="row my-3">
      <div class="col-5">
        <div class="mt-3 search-box">
          <form method="GET" action="/administrasi/stok/riwayat/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Nomor Nota..."
              value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>   
            </div>
            
          </form>    
          
        </div>
      </div>
      
    </div>

    <table class="table">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Tanggal</th>
          <th scope="col">No Nota</th>
          <th scope="col">Keterangan</th>
          <th scope="col">Total Harga</th> 
          <th scope="col">Action</th>             
        </tr>
      </thead>
      <tbody>
        @foreach ($pengadaans as $pengadaan)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $pengadaan->created_at }}</td>
          <td>{{ $pengadaan->no_nota }}</td>
          <td>{{ $pengadaan->keterangan }}</td>
          <td>{{ $pengadaan->harga }}</td>
          <td>
            <a href="/administrasi/stok/riwayat/detail/{{ $pengadaan->no_pengadaan }}" class="btn btn-primary">Detail</a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="d-flex flex-row mt-4">
      {{ $pengadaans->links() }}
    </div>
  </div>
  
  

  
@endsection