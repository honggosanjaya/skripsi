<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Jenis Customer</li>
</ol>
@endsection
@section('main_content')

<div class="container">
  <div class="row">
    <div class="col-5">
      <div class="mt-3 search-box">
        <form method="GET" action="/supervisor/jenis/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Jenis Customer..."
            value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">Cari</button>   
          </div>
          
        </form>    
        
      </div>
    </div>
    <div class="col-4">
      <a href="/supervisor/jenis/tambah" class="btn btn-primary my-3 py-2">Tambah Jenis</a>
    </div>
  </div>
</div>


<table class="table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Nama Jenis Customer</th>
      <th scope="col">Diskon (%)</th>
      <th scope="col">Keterangan</th>
      <th scope="col">Action</th>      
    </tr>
  </thead>
  <tbody>
    @foreach ($jenises as $jenis)
    <tr>
      <th scope="row">{{ $loop->iteration }}</th>
      <td>{{ $jenis->nama }}</td>
      <td>{{ $jenis->diskon }}</td>
      <td>{{ $jenis->keterangan }}</td>
      <td>
        <a href="/supervisor/jenis/ubah/{{ $jenis->id }}" class="btn btn-warning">Ubah</a>
      </td>
      
    </tr>
    @endforeach
  </tbody>
</table>
@endsection