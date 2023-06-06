@extends('layouts.mainreact')

@section('main_content')
  <div class="page_container pt-4">
    <h1 class='fs-6 fw-bold'>Menu untuk Salesman</h1>
    <button class='btn btn-primary btn-lg w-100'>
      <span class="iconify fs-4 me-2" data-icon="bx:trip"></span> Trip
    </button>
    <button class='btn btn-success btn-lg w-100 mt-4'>
      <span class="iconify fs-4 me-2" data-icon="carbon:ibm-watson-orders"></span> Order
    </button>

    <a href="/salesman/reimbursement" class='btn btn-purple btn-lg w-100 mt-4'>
      <span class="iconify fs-3 me-2" data-icon="mdi:cash-sync"></span> Reimbursement
    </a>

    <a href="/salesman/historyinvoice" class='btn btn-danger btn-lg w-100 mt-4'>
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
  </div>
@endsection
