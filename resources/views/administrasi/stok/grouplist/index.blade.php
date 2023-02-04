@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok">Stok</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/stok/produk">Produk</a></li>
    <li class="breadcrumb-item active" aria-current="page">Group List</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4">
    <h1 class="fs-4 mb-4">Group List</h1>

    <a href="/administrasi/stok/produk/grouplist/add" class="btn btn-primary me-2">
      <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span> Tambah Group
    </a>
    <div class="table-responsive mt-3">
      <table class="table table-hover table-sm" id="table">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Group Item</th>
            <th scope="col" class="text-center">Value</th>
            <th scope="col" class="text-center">Satuan</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          {{-- {{ dd($items_group) }} --}}
          @foreach ($items_group as $group)
            <tr>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $group[0]->linkItemGroup->nama ?? null }}</td>
              <td class="text-center">{{ $group[0]->value ?? null }}</td>
              <td class="text-center">{{ $group[0]->linkItemGroup->satuan ?? null }}</td>
              <td class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                  data-bs-target="#exampleModal{{ $group[0]->id_group_item }}">
                  <span class="iconify fs-4" data-icon="fluent:apps-list-detail-24-filled"></span> Detail
                </button>

                <div class="modal fade" id="exampleModal{{ $group[0]->id_group_item }}" tabindex="-1"
                  aria-labelledby="exampleModalLabel{{ $group[0]->id_group_item }}" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Group Item</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="info-list">
                          <span class="d-flex"><b>Group Item</b>
                            {{ $group[0]->linkItemGroup->nama ?? null }}</span>
                          <span class="d-flex"><b>Value</b>
                            {{ $group[0]->value ?? null }} {{ $group[0]->linkItemGroup->satuan ?? null }}</span>
                        </div>
                        <div class="table-responsive mt-4">
                          <table class="table table-hover table-sm">
                            <thead>
                              <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col" class="text-center">Item</th>
                                <th scope="col" class="text-center">Value</th>
                                <th scope="col" class="text-center">Satuan</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($group as $item)
                                <tr>
                                  <td class="text-center">{{ $loop->iteration }}</td>
                                  <td>{{ $item->linkItem->nama ?? null }}</td>
                                  <td class="text-center">{{ $item->value_item ?? null }}</td>
                                  <td class="text-center">{{ $item->linkItem->satuan ?? null }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <a href="/administrasi/stok/produk/grouplist/edit/{{ $group[0]->id_group_item }}"
                  class="btn btn-warning">
                  <span class="iconify fs-4" data-icon="ant-design:edit-filled"></span> Edit
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @push('JS')
      <script src="{{ mix('js/administrasi.js') }}"></script>
    @endpush
  @endsection
