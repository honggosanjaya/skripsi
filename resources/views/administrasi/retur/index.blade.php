<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Retur</li>
</ol>
@endsection
@section('main_content')
<div class="container">
    <div class="row">
      <div class="col-5">
        <div class="mt-3 search-box">
          <form method="GET" action="/administrasi/retur/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Nomor Retur..."
              value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>   
            </div>
            
          </form>    
          
        </div>
      </div>
      
    </div>

    <table class="table table-bordered mt-4">
        <thead>
          <tr>
            <th scope="col">Nomor Retur</th>
            <th scope="col">Tanggal Retur</th>
            <th scope="col">Nama Customer</th>
            <th scope="col">Alamat</th>
            <th scope="col">Pengirim</th>
            <th scope="col">Status Retur</th>
            <th scope="col">Action</th>      
          </tr>
        </thead>
        <tbody>
            @foreach($returs as $retur)
                <tr>
                    <td>{{ $retur->no_retur }}</td>
                    <td>{{ date('d-m-Y', strtotime($retur->created_at)) }}</td>
                    <td>{{ $retur->linkCustomer->nama }}</td>
                    <td>{{ $retur->linkCustomer->alamat_utama . ' ' . $retur->linkCustomer->alamat_nomor }}</td>
                    <td>{{ $retur->linkStaffPengaju->nama }}</td>
                    <td>{{ $retur->linkStatus->nama }}</td>
                    <td>
                      <a href="/administrasi/retur/{{ $retur->no_retur }}" class="btn btn-primary">Detail</a>
                  </td>
                </tr>
            @endforeach
        </tbody>
      </table>
      
      <div class="d-flex flex-row mt-4">
       {{ $returs->links() }}
      </div>
</div>

@endsection