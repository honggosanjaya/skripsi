@extends('layouts/main')

@section('main_content')
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Alasan Penolakan retur</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-floating">
            <textarea class="form-control" placeholder="Masukkan alasan penolakan" style="height: 100px"></textarea>
            <label>Alasan</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3 search-box">
    <i class="bi bi-search"></i>
    <input type="text" class="form-control " placeholder="Cari Retur...">
  </div>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">id retur</th>
        <th scope="col">nama item</th>
        <th scope="col">nama toko</th>
        <th scope="col">nama sales</th>
        <th scope="col">jumlah</th>
        <th scope="col">alasan</th>
        <th scope="col">status retur</th>
        <th scope="col">tindakan selanjutnya</th>
        <th scope="col">ditangani oleh</th>
        <th scope="col">aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($returs as $retur)
        <tr>
          <td>{{ $retur->id }}</td>
          <td>{{ $retur->linkItem->nama_barang }}</td>
          <td>{{ $retur->linkToko->nama }}</td>
          <td>{{ $retur->linkSales->nama }}i</td>
          <td>{{ $retur->quantity }}</td>
          <td>{{ $retur->alasan }}</td>
          @if ($retur->status == '-1')
            <td>Ditolak</td>
          @elseif ($retur->status == '0')
            <td>Belum melakukan tindakan</td>
          @else
            <td>Disetujui</td>
          @endif

          @if ($retur->tindakan_selanjutnya == '0')
            <td>Tukar barang</td>
          @else
            <td>Lainnya</td>
          @endif

          <td>{{ $nama_admin }}</td>

          @if ($retur->status == '-1')
            <td>
              <button class="btn btn-success">Terima</button>
            </td>
          @elseif ($retur->status == '0')
            <td>
              <button class="btn btn-success">Terima</button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Tolak
              </button>
            </td>
          @else
            <td>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Tolak
              </button>
            </td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
