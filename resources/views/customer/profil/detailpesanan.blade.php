
@extends('customer.layouts.customerLayouts')

@section('content')
<div class="row mt-3">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="diajukanCustomer-tab" href="#diajukanCustomer" data-bs-toggle="tab" data-bs-target="#diajukanCustomer"
          role="tab" aria-controls="diajukanCustomer" aria-selected="true">Diajukan Customer</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="diajukanSalesman-tab" href="#diajukanSalesman" data-bs-toggle="tab" data-bs-target="#diajukanSalesman"
          role="tab" aria-controls="diajukanSalesman" aria-selected="false">Diajukan Salesman</a>
        </li>  
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="dikonfirmasiAdministrasi-tab" href="#dikonfirmasiAdministrasi" data-bs-toggle="tab" data-bs-target="#dikonfirmasiAdministrasi"
            role="tab" aria-controls="dikonfirmasiAdministrasi" aria-selected="false">Dikonfirmasi Administrasi</a>
        </li>  
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="dalamPerjalanan-tab" href="#dalamPerjalanan" data-bs-toggle="tab" data-bs-target="#dalamPerjalanan"
            role="tab" aria-controls="dalamPerjalanan" aria-selected="false">Dalam Perjalanan</a>
        </li>     
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="telahSampai-tab" href="#telahSampai" data-bs-toggle="tab" data-bs-target="#telahSampai"
            role="tab" aria-controls="telahSampai" aria-selected="false">Telah Sampai</a>
        </li> 
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ditolak-tab" href="#ditolak" data-bs-toggle="tab" data-bs-target="#ditolak"
            role="tab" aria-controls="ditolak" aria-selected="false">Ditolak</a>
        </li> 
    </ul>

    
        
</div>
@endsection