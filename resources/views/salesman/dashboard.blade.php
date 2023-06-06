@extends('layouts.mainreact')

@push('JS')
  <script>
    const loginPassword = $('input[name="loginPassword"]').val();
    const countt = $('input[name="countt"]').val();

    if (loginPassword == "12345678" && countt == 2) {
      Swal.fire({
        title: 'Anda Menggunakan Password Default',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ubah Password!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "/salesman/changepassword";
        }
      })
    }
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <h1 class='fs-6 fw-bold mb-4'>Menu untuk Salesman</h1>
    <button class='btn btn-primary btn-lg w-100'>
      <span class="iconify fs-4 me-2" data-icon="bx:trip"></span> Trip
    </button>
    <button class='btn btn-success btn-lg w-100 mt-4'>
      <span class="iconify fs-4 me-2" data-icon="carbon:ibm-watson-orders"></span> Order
    </button>

    <a href="/lapangan/reimbursement" class='btn btn-purple btn-lg w-100 mt-4'>
      <span class="iconify fs-3 me-2" data-icon="mdi:cash-sync"></span> Reimbursement
    </a>

    <a href="/salesman/riwayatinvoice" class='btn btn-danger btn-lg w-100 mt-4'>
      <span class="iconify fs-3 me-2" data-icon="fa-solid:file-invoice-dollar"></span> Riwayat Invoice
    </a>

    <a href="/lapangan/penagihan" class='btn btn-info btn-lg w-100 mt-4 text-white'>
      <span class="iconify fs-3 me-2 text-white" data-icon="uil:bill"></span> Penagihan
    </a>

    <a href='/lapangan/jadwal' class='btn btn-primary btn-lg w-100 mt-3'>
      <span class="iconify me-2" data-icon="fa-solid:shipping-fast"></span>
      Pengiriman
    </a>

    <a href='/salesman/itemkanvas' class='btn btn-success btn-lg w-100 mt-3'>
      <span class="iconify fs-3 me-2" data-icon="fluent:tray-item-remove-24-filled"></span>
      Item Kanvas
    </a>

    <input type="hidden" name="loginPassword" value="{{ session('password') }}">
    <input type="hidden" name="countt" value="{{ session('count') }}">
  </div>
@endsection
