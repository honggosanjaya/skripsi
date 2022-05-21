<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')

@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Event</li>
</ol>
@endsection
@section('main_content')
@if(session()->has('addEventSuccess'))
<div id="hideMeAfter3Seconds">
  <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" >
    {{ session('addEventSuccess') }}
    <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
</div>
</div>
            
@endif
@if(session()->has('updateEventSuccess'))
<div id="hideMeAfter3Seconds">
  <div class="alert alert-success alert-dismissible fade show mt-4" role="alert" >
    {{ session('updateEventSuccess') }}
    <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
</div>
</div>
            
@endif
<div class="container">
  <div class="row">
    <div class="col-5">
      <div class="mt-3 search-box">
        <form method="GET" action="/supervisor/event/cari">
          <div class="input-group">
            <input type="text" class="form-control" name="cari" placeholder="Cari Event..."
            value="{{ request('cari') }}">
            <button type="submit" class="btn btn-primary">Cari</button>   
          </div>
          
        </form>    
        
      </div>
    </div>
    <div class="col-4">
      <a href="/supervisor/event/tambah" class="btn btn-primary my-3 py-2">Tambah Event</a>
    </div>
  </div>
</div>


<table class="table">
  <thead>
    <tr>
      <th scope="col">No</th>
      <th scope="col">Kode Event</th>
      <th scope="col">Nama Event</th>
      <th scope="col">Keterangan</th>
      <th scope="col">Diskon</th>
      <th scope="col">Potongan</th>
      <th scope="col">Minimum Pembelian</th>  
      <th scope="col">Tanggal Mulai</th>
      <th scope="col">Tanggal Selesai</th>
      <th scope="col">PIC</th>
      <th scope="col">Status</th>
      <th scope="col">Gambar</th>  
      <th scope="col">Action</th>  
    </tr>
  </thead>
  <tbody>
    @foreach($events as $event)
        <tr>
            <td>{{ ($events->currentPage() - 1) * $events->perPage() + $loop->iteration }}</td>
            <td>{{ $event->kode }}</td>
            <td>{{ $event->nama }}</td>
            <td>{{ $event->keterangan }}</td>
            <td>{{ $event->diskon ?? 0 }}</td>
            <td>{{ $event->potongan ?? 0 }}</td>
            <td>{{ $event->min_pembelian }}</td>
            <td>{{ $event->date_start }}</td>
            <td>{{ $event->date_end }}</td>
            <td>{{ $event->linkStaff->nama }}</td>
            <td>{{ $event->linkStatus->nama }}</td>
            <td>{{ $event->gambar }}</td>
            <td>
                <a href="/supervisor/event/ubah/{{ $event->id }}" class="btn btn-warning">Ubah</a>
            </td>
        </tr>
        
    @endforeach
  </tbody>
</table>

<div class="d-flex flex-row mt-4">
  {{ $events->links() }}
</div>

@endsection