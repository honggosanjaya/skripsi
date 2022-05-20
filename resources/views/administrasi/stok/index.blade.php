@extends('layouts/main')

@section('main_content')
  
  <div class="container">

    

    <div class="row mt-3">
      <div class="col-6 d-flex flex-row justify-content-start">
        <div class="row">
          <div class="col-6">
            <h5>Stok Marketing</h5>
          </div>
          <div class="col-6">
            <a href="/administrasi/stok/produk" class="btn btn-primary">List Produk</a>
          </div>
        </div>
        
        
      </div>
      <div class="col-6 ml-auto">
        <a href="/administrasi/stok/riwayat" class="btn btn-primary">Riwayat Pengadaan</a>
        <a href="/administrasi/stok/pengadaan" class="btn btn-primary">Pengadaan</a>
        <a href="#" class="btn btn-primary">Stok Opname</a>
      </div>
    </div>

    <div class="row my-3">
      <div class="col-5">
        <div class="mt-3 search-box">
          <form method="GET" action="/administrasi/stok/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Jenis Customer..."
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
          <th scope="col">Kode Stok</th>
          <th scope="col">Nama</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Satuan</th> 
          <th scope="col">Stok Minimal</th>
          <th scope="col">Stok Maksimal</th>
          <th scope="col">Pengadaan Maksimal</th>
          <th scope="col">Harga</th>
          <th scope="col">Status</th>     
        </tr>
      </thead>
      <tbody>
        @foreach ($items as $item)
        <tr>
          <th scope="row">{{ $loop->iteration }}</th>
          <td>{{ $item->kode_barang }}</td>
          <td>{{ $item->nama }}</td>
          <td>{{ $item->stok }}</td>
          <td>{{ $item->satuan }}</td>
          <td>{{ $item->min_stok }}</td>
          <td>{{ $item->max_stok }}</td>
          <td>{{ $item->max_pengadaan }}</td>
          <td>{{ $item->harga_satuan }}</td>
          <td>masih coba</td>
          
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
  

  
@endsection