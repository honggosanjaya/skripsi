@extends('layouts.mainmobile')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Data Customer</li>
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

  <div class="container pt-4">
    <div class="row justify-content-end">
      <div class="col d-flex justify-content-center">
        <a href="/administrasi/datacustomer/create" class="btn btn-purple-gradient mb-5">
          <span class="iconify fs-3 me-2" data-icon="dashicons:database-add"></span> Tambah Customer
        </a>
      </div>
    </div>

    @foreach ($customers as $customer)
      <div class="list-mobile">
        <div class="d-flex justify-content-between align-items-start mb-4">
          <div>
            <h1 class="fs-5 mb-0 title">{{ $customer->nama ?? null }}</h1>
            @if ($customer->kode_customer ?? null)
              <span class="text-secondary">{{ $customer->kode_customer }}</span> <br>
            @endif
            @if (($customer->telepon ?? null) && ($customer->status_telepon ?? null))
              @if (str_contains($customer->status_telepon, 'WA') || str_contains($customer->status_telepon, 'WhatsApp'))
                <span class="iconify fs-5 me-1" data-icon="logos:whatsapp-icon"></span>
              @endif
            @endif
            <span class="text-secondary">{{ $customer->telepon ?? null }}</span>
          </div>
          <div class="group-value">
            @if ($customer->status_enum != null)
              <div
                class="{{ $customer->status_enum == '1' ? 'bg-success' : ($customer->status_enum == '0' ? 'bg-warning' : 'bg-danger') }}">
                {{ $customer->status_enum == '1' ? 'Active' : ($customer->status_enum == '0' ? 'Hide' : 'Inactive') }}
              </div>
            @endif
            @if ($customer->tipe_harga ?? null)
              <div>
                Harga {{ $customer->tipe_harga }}
              </div>
            @endif
          </div>
        </div>

        <span class="fs-6">{{ $customer->full_alamat ?? null }}</span>

        <div class="action d-flex justify-content-center mt-3">
          <a href="/administrasi/datacustomer/ubah/{{ $customer->id }}" class="btn btn-warning">
            <span class="iconify fs-5 me-1" data-icon="eva:edit-2-fill"></span> Edit
          </a>
          <a href="/administrasi/datacustomer/{{ $customer->id }}/generate-qr" class="btn btn-primary mx-4">
            <span class="iconify fs-5 me-1" data-icon="bx:qr-scan"></span> QR
          </a>
          <a href="/administrasi/datacustomer/{{ $customer->id }}" class="btn btn-purple">
            <span class="iconify fs-4 me-1" data-icon="fluent:apps-list-detail-24-filled"></span>Lihat Detail
          </a>
        </div>
      </div>
    @endforeach

    <div class="mt-5 d-flex justify-content-center">
      {{ $customers->links() }}
    </div>
  </div>
  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
  @endpush
@endsection
