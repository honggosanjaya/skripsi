@extends('layouts/main')

@section('main_content')
  @if (session()->has('pesanSukses'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('pesanSukses') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <a href="/administrasi/datacustomer/create" class="btn btn-primary">
    Tambah Customer
  </a>

  <div class="table-responsive mt-3">
    <table class="table table-hover table-sm">
      <thead>
        <tr>
          <th scope="col" class="text-center">No</th>
          <th scope="col" class="text-center">Nama</th>
          <th scope="col" class="text-center">Alamat Lengkap</th>
          <th scope="col" class="text-center">Email</th>
          <th scope="col" class="text-center">Telepon</th>
          <th scope="col" class="text-center">Tipe Retur</th>
          <th scope="col" class="text-center">Limit Pembelian</th>
          <th scope="col" class="text-center">Pengajuan Limit Pembelian</th>
          <th scope="col" class="text-center">Status limit</th>
          <th scope="col" class="text-center">Status</th>
          <th scope="col" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($customers as $customer)
          <tr onclick="window.location='/administrasi/datacustomer/{{ $customer->id }}';">
            <th scope="row">{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</th>
            <td>{{ $customer->nama }}</td>
            <td>{{ $customer->full_alamat }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->telepon }}</td>
            <td>{{ $customer->linkReturType->nama ?? null }}</td>
            <td>{{ $customer->limit_pembelian }}</td>
            <td>{{ $customer->pengajuan_limit_pembelian }}</td>
            <td>{{ $customer->linkStatusLimit->nama ?? null }}</td>
            <td>{{ $customer->linkStatus->nama }}</td>
            <td class="text-center">
              <a href="/administrasi/datacustomer/ubah/{{ $customer->id }}" class="btn btn-sm btn-primary mb-2">
                Edit
              </a>

              <form action="/administrasi/datacustomer/ubahstatus/{{ $customer->id }}" method="POST">
                @csrf
                <button type="submit"
                  class="btn btn-sm {{ $customer->linkStatus->nama === 'active' ? 'btn-danger' : 'btn-success' }}">
                  {{ $customer->linkStatus->nama === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    {{ $customers->links() }}
  </div>
@endsection
