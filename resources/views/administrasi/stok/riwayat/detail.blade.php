@extends('layouts/main')

@section('main_content')
  
  <div class="container">

    
    <table class="table">
      <thead>
        <tr>
          <th scope="col">Kode</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Satuan</th>
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
          <td>Belum</td>
          <td>
            <a href="/administrasi/stok/riwayat/detail/{{ $pengadaan->no_pengadaan }}" class="btn btn-primary">Detail</a>
          </td>
        </tr>
        @endforeach
        <tr>
            <td><a class="btn btn-primary" href="#">Unduh NPB</a></td>
            <td></td>
            <td></td>
            <td>Total</td>
            <td>15000</td>
        </tr>
      </tbody>
    </table>

    <table>
        <tbody>
            <tr>
                <td>No Nota</td>
                <td></td>
                <td>Tanggal</td>
                <td></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td></td>
            </tr>
        </tbody>
    </table>
  </div>
  
  

  
@endsection