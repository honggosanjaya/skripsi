
@extends('customer.layouts.customerLayouts')

@section('content')
<div class="mt-3">
  <a href="/customer/profil" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>
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

    <!-- Bagian Diajukan Customer -->
    <div class="tab-content clearfix">
        <div class="tab-pane fade show active" id="diajukanCustomer" role="tabpanel" aria-labelledby="diajukanCustomer-tab">
            <h3 class="mt-3">List Pesanan</h3>   
          <div class="container">      
          @foreach($diajukanCustomers as $diajukanCustomer)
            <div class="row border border-1">
                <h3>{{ $diajukanCustomer->linkOrderItem[0]->linkItem->nama }}</h3>  
                <h5 class="fw-normal">{{ date('d F Y', strtotime($diajukanCustomer->linkOrderTrack->waktu_order)) }}</h5> 
                <button type="button" class="btn btn-primary w-50 my-2 mx-2" data-bs-toggle="modal" data-bs-target="#order{{ $diajukanCustomer->id }}">
                    Lihat Detail >>>
                </button>
        
                  <!-- Modal -->
                  <div class="modal fade" id="order{{ $diajukanCustomer->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ $diajukanCustomer->id }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                          
                          <div class="row">
                            <div class="col-5">
                                <h5>Tanggal Pesan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ date('d F Y', strtotime($diajukanCustomer->linkOrderTrack->waktu_order)) }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Barang yang dibeli : </h5>
                            </div>                            
                          </div>
                          <table class="table table-bordered">
                              <thead>
                                  <th>Kode Barang</th>
                                  <th>Nama Barang</th>
                                  <th>Jumlah</th>
                                  <th>Harga Satuan</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                  @foreach($diajukanCustomer->linkOrderItem as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->linkItem->kode_barang }}</td>
                                        <td>{{ $orderItem->linkItem->nama }}</td>
                                        <td>{{ $orderItem->kuantitas }}</td>
                                        <td>{{ $orderItem->harga_satuan }}</td>
                                        <td>{{ number_format($orderItem->kuantitas*$orderItem->harga_satuan,0,"",".") }}</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>           
            </div>                 
            
          @endforeach
        </div>       
            
        </div>

        <!-- Bagian Diajukan Salesman -->
        <div class="tab-pane" id="diajukanSalesman" role="tabpanel" aria-labelledby="diajukanSalesman-tab">
          <h3 class="mt-3">List Pesanan</h3>   
          <div class="container">      
          @foreach($diajukanSalesmans as $diajukanSalesman)
            <div class="row border border-1">
                <h3>{{ $diajukanSalesman->linkInvoice->nomor_invoice }}</h3>  
                <h5 class="fw-normal">{{ $diajukanSalesman->linkOrderItem[0]->linkItem->nama }}</h5>   
                <h5 class="fw-normal">Rp {{ number_format($diajukanSalesman->linkInvoice->harga_total,0,"",".") }}</h5>
                <h5 class="fw-normal">{{ date('d F Y', strtotime($diajukanSalesman->linkOrderTrack->waktu_dikonfirmasi)) }}</h5> 
                <button type="button" class="btn btn-primary w-50 my-2 mx-2" data-bs-toggle="modal" data-bs-target="#order{{ $diajukanSalesman->id }}">
                    Lihat Detail >>>
                </button>
        
                  <!-- Modal -->
                  <div class="modal fade" id="order{{ $diajukanSalesman->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ $diajukanSalesman->linkInvoice->nomor_invoice }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                          <div class="row">
                              <div class="col-5">
                                  <h5>Nomor Invoice : </h5>
                              </div>
                              <div class="col-5">
                                <h5 class="fw-normal">{{ $diajukanSalesman->linkInvoice->nomor_invoice }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Tanggal Diajukan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ date('d F Y', strtotime($diajukanSalesman->linkOrderTrack->waktu_dikonfirmasi)) }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Diajukan oleh : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $diajukanSalesman->linkStaff->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Event yang berlangsung : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $diajukanSalesman->linkInvoice->linkEvent->nama ?? "-" }}</h5>
                            </div>
                          </div>
                          @if($diajukanSalesman->linkInvoice->linkEvent && $diajukanSalesman->linkInvoice->linkEvent->diskon!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Diskon : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $diajukanSalesman->linkInvoice->linkEvent->diskon }}</h5>
                            </div>
                          </div>
                          @elseif($diajukanSalesman->linkInvoice->linkEvent && $diajukanSalesman->linkInvoice->linkEvent->potongan!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Potongan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $diajukanSalesman->linkInvoice->linkEvent->potongan }}</h5>
                            </div>
                          </div>
                          @endif
                          <div class="row">
                            <div class="col-5">
                                <h5>Harga Total : </h5>
                            </div> 
                            <div class="col-5">
                                <h5 class="fw-normal">{{ number_format($diajukanSalesman->linkInvoice->harga_total,0,"",".") }}</h5>
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Barang yang dibeli : </h5>
                            </div>                            
                          </div>
                          <table class="table table-bordered">
                              <thead>
                                  <th>Kode Barang</th>
                                  <th>Nama Barang</th>
                                  <th>Jumlah</th>
                                  <th>Harga Satuan</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                  @foreach($diajukanSalesman->linkOrderItem as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->linkItem->kode_barang }}</td>
                                        <td>{{ $orderItem->linkItem->nama }}</td>
                                        <td>{{ $orderItem->kuantitas }}</td>
                                        <td>{{ $orderItem->harga_satuan }}</td>
                                        <td>{{ number_format($orderItem->kuantitas*$orderItem->harga_satuan,0,"",".") }}</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>           
            </div>     
            
            
          @endforeach
        </div>        
            
        </div>

        <!-- Bagian Dikonfirmasi Administrasi -->
        <div class="tab-pane" id="dikonfirmasiAdministrasi" role="tabpanel" aria-labelledby="dikonfirmasiAdministrasi-tab">
          <h3 class="mt-3">List Pesanan</h3>   
          <div class="container">      
          @foreach($dikonfirmasiAdministrasis as $dikonfirmasiAdministrasi)
            <div class="row border border-1">
                <h3>{{ $dikonfirmasiAdministrasi->linkInvoice->nomor_invoice }}</h3>  
                <h5 class="fw-normal">{{ $dikonfirmasiAdministrasi->linkOrderItem[0]->linkItem->nama }}</h5>   
                <h5 class="fw-normal">Rp {{ number_format($dikonfirmasiAdministrasi->linkInvoice->harga_total,0,"",".") }}</h5>
                <h5 class="fw-normal">{{ date('d F Y', strtotime($dikonfirmasiAdministrasi->linkOrderTrack->waktu_dikonfirmasi)) }}</h5> 
                <button type="button" class="btn btn-primary w-50 my-2 mx-2" data-bs-toggle="modal" data-bs-target="#order{{ $dikonfirmasiAdministrasi->id }}">
                    Lihat Detail >>>
                </button>
        
                  <!-- Modal -->
                  <div class="modal fade" id="order{{ $dikonfirmasiAdministrasi->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ $dikonfirmasiAdministrasi->linkInvoice->nomor_invoice }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                          <div class="row">
                              <div class="col-5">
                                  <h5>Nomor Invoice : </h5>
                              </div>
                              <div class="col-5">
                                <h5 class="fw-normal">{{ $dikonfirmasiAdministrasi->linkInvoice->nomor_invoice }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Tanggal : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ date('d F Y', strtotime($dikonfirmasiAdministrasi->linkOrderTrack->waktu_dikonfirmasi)) }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Dikonfirmasi oleh : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dikonfirmasiAdministrasi->linkOrderTrack->linkStaffPengonfirmasi[0]->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Event yang berlangsung : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dikonfirmasiAdministrasi->linkInvoice->linkEvent->nama ?? "-" }}</h5>
                            </div>
                          </div>                          
                          @if($dikonfirmasiAdministrasi->linkInvoice->linkEvent && $dikonfirmasiAdministrasi->linkInvoice->linkEvent->diskon!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Diskon : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dikonfirmasiAdministrasi->linkInvoice->linkEvent->diskon }}</h5>
                            </div>
                          </div>
                          @elseif($dikonfirmasiAdministrasi->linkInvoice->linkEvent && $dikonfirmasiAdministrasi->linkInvoice->linkEvent->potongan!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Potongan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dikonfirmasiAdministrasi->linkInvoice->linkEvent->potongan }}</h5>
                            </div>
                          </div>
                          @endif
                          <div class="row">
                            <div class="col-5">
                                <h5>Harga Total : </h5>
                            </div> 
                            <div class="col-5">
                                <h5 class="fw-normal">{{ number_format($dikonfirmasiAdministrasi->linkInvoice->harga_total,0,"",".") }}</h5>
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Barang yang dibeli : </h5>
                            </div>                            
                          </div>
                          <table class="table table-bordered">
                              <thead>
                                  <th>Kode Barang</th>
                                  <th>Nama Barang</th>
                                  <th>Jumlah</th>
                                  <th>Harga Satuan</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                  @foreach($dikonfirmasiAdministrasi->linkOrderItem as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->linkItem->kode_barang }}</td>
                                        <td>{{ $orderItem->linkItem->nama }}</td>
                                        <td>{{ $orderItem->kuantitas }}</td>
                                        <td>{{ $orderItem->harga_satuan }}</td>
                                        <td>{{ number_format($orderItem->kuantitas*$orderItem->harga_satuan,0,"",".") }}</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>           
            </div>     
            
            
          @endforeach
        </div>
        </div>

        <!-- Bagian Dalam Perjalanan -->
        <div class="tab-pane" id="dalamPerjalanan" role="tabpanel" aria-labelledby="dalamPerjalanan-tab">
          <h3 class="mt-3">List Pesanan</h3>   
          <div class="container">      
          @foreach($dalamPerjalanans as $dalamPerjalanan)
            <div class="row border border-1">
                <h3>{{ $dalamPerjalanan->linkInvoice->nomor_invoice }}</h3>  
                <h5 class="fw-normal">{{ $dalamPerjalanan->linkOrderItem[0]->linkItem->nama }}</h5>   
                <h5 class="fw-normal">Rp {{ number_format($dalamPerjalanan->linkInvoice->harga_total,0,"",".") }}</h5>
                <h5 class="fw-normal">{{ date('d F Y', strtotime($dalamPerjalanan->linkOrderTrack->waktu_dikonfirmasi)) }}</h5> 
                <button type="button" class="btn btn-primary w-50 my-2 mx-2" data-bs-toggle="modal" data-bs-target="#order{{ $dalamPerjalanan->id }}">
                    Lihat Detail >>>
                </button>
        
                  <!-- Modal -->
                  <div class="modal fade" id="order{{ $dalamPerjalanan->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ $dalamPerjalanan->linkInvoice->nomor_invoice }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                          <div class="row">
                              <div class="col-5">
                                  <h5>Nomor Invoice : </h5>
                              </div>
                              <div class="col-5">
                                <h5 class="fw-normal">{{ $dalamPerjalanan->linkInvoice->nomor_invoice }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Tanggal Dikirim : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ date('d F Y', strtotime($dalamPerjalanan->linkOrderTrack->waktu_berangkat)) }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Dikonfirmasi oleh : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dalamPerjalanan->linkOrderTrack->linkStaffPengonfirmasi[0]->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Dikirim oleh : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dalamPerjalanan->linkOrderTrack->linkStaffPengirim[0]->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Event yang berlangsung : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dalamPerjalanan->linkInvoice->linkEvent->nama ?? "-" }}</h5>
                            </div>
                          </div>
                          @if($dalamPerjalanan->linkInvoice->linkEvent && $dalamPerjalanan->linkInvoice->linkEvent->diskon!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Diskon : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dalamPerjalanan->linkInvoice->linkEvent->diskon }}</h5>
                            </div>
                          </div>
                          @elseif($dalamPerjalanan->linkInvoice->linkEvent && $dalamPerjalanan->linkInvoice->linkEvent->potongan!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Potongan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $dalamPerjalanan->linkInvoice->linkEvent->potongan }}</h5>
                            </div>
                          </div>
                          @endif
                          <div class="row">
                            <div class="col-5">
                                <h5>Harga Total : </h5>
                            </div> 
                            <div class="col-5">
                                <h5 class="fw-normal">{{ number_format($dalamPerjalanan->linkInvoice->harga_total,0,"",".") }}</h5>
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Barang yang dibeli : </h5>
                            </div>                            
                          </div>
                          <table class="table table-bordered">
                              <thead>
                                  <th>Kode Barang</th>
                                  <th>Nama Barang</th>
                                  <th>Jumlah</th>
                                  <th>Harga Satuan</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                  @foreach($dalamPerjalanan->linkOrderItem as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->linkItem->kode_barang }}</td>
                                        <td>{{ $orderItem->linkItem->nama }}</td>
                                        <td>{{ $orderItem->kuantitas }}</td>
                                        <td>{{ $orderItem->harga_satuan }}</td>
                                        <td>{{ number_format($orderItem->kuantitas*$orderItem->harga_satuan,0,"",".") }}</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>           
            </div>     
            
            
          @endforeach
        </div>         
            
        </div>

        <!-- Bagian Telah Sampai -->
        <div class="tab-pane" id="telahSampai" role="tabpanel" aria-labelledby="telahSampai-tab">
          <h3 class="mt-3">List Pesanan</h3>   
          <div class="container">      
          @foreach($telahSampais as $telahSampai)
            <div class="row border border-1">
                <h3>{{ $telahSampai->linkInvoice->nomor_invoice }}</h3>  
                <h5 class="fw-normal">{{ $telahSampai->linkOrderItem[0]->linkItem->nama }}</h5>   
                <h5 class="fw-normal">Rp {{ number_format($telahSampai->linkInvoice->harga_total,0,"",".") }}</h5>
                <h5 class="fw-normal">{{ date('d F Y', strtotime($telahSampai->linkOrderTrack->waktu_dikonfirmasi)) }}</h5> 
                <button type="button" class="btn btn-primary w-50 my-2 mx-2" data-bs-toggle="modal" data-bs-target="#order{{ $telahSampai->id }}">
                    Lihat Detail >>>
                </button>
        
                  <!-- Modal -->
                  <div class="modal fade" id="order{{ $telahSampai->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ $telahSampai->linkInvoice->nomor_invoice }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                          <div class="row">
                              <div class="col-5">
                                  <h5>Nomor Invoice : </h5>
                              </div>
                              <div class="col-5">
                                <h5 class="fw-normal">{{ $telahSampai->linkInvoice->nomor_invoice }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Tanggal Sampai : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ date('d F Y', strtotime($telahSampai->linkOrderTrack->waktu_sampai)) }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Dikonfirmasi oleh : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $telahSampai->linkOrderTrack->linkStaffPengonfirmasi[0]->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Dikirim oleh : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $telahSampai->linkOrderTrack->linkStaffPengirim[0]->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Event yang berlangsung : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $telahSampai->linkInvoice->linkEvent->nama ?? "-" }}</h5>
                            </div>
                          </div>
                          @if($telahSampai->linkInvoice->linkEvent && $telahSampai->linkInvoice->linkEvent->diskon!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Diskon : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $telahSampai->linkInvoice->linkEvent->diskon }}</h5>
                            </div>
                          </div>
                          @elseif($telahSampai->linkInvoice->linkEvent && $telahSampai->linkInvoice->linkEvent->potongan!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Potongan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $telahSampai->linkInvoice->linkEvent->potongan }}</h5>
                            </div>
                          </div>
                          @endif
                          <div class="row">
                            <div class="col-5">
                                <h5>Harga Total : </h5>
                            </div> 
                            <div class="col-5">
                                <h5 class="fw-normal">{{ number_format($telahSampai->linkInvoice->harga_total,0,"",".") }}</h5>
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Barang yang dibeli : </h5>
                            </div>                            
                          </div>
                          <table class="table table-bordered">
                              <thead>
                                  <th>Kode Barang</th>
                                  <th>Nama Barang</th>
                                  <th>Jumlah</th>
                                  <th>Harga Satuan</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                  @foreach($telahSampai->linkOrderItem as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->linkItem->kode_barang }}</td>
                                        <td>{{ $orderItem->linkItem->nama }}</td>
                                        <td>{{ $orderItem->kuantitas }}</td>
                                        <td>{{ $orderItem->harga_satuan }}</td>
                                        <td>{{ number_format($orderItem->kuantitas*$orderItem->harga_satuan,0,"",".") }}</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>           
            </div>     
            
            
          @endforeach
        </div>       
            
        </div>

        <!-- -->
        <div class="tab-pane" id="ditolak" role="tabpanel" aria-labelledby="ditolak-tab">
            <h3 class="mt-3">List Pesanan</h3>   
          <div class="container">      
          @foreach($ditolaks as $ditolak)
            <div class="row border border-1">
                <h3>{{ $ditolak->linkInvoice->nomor_invoice }}</h3>  
                <h5 class="fw-normal">{{ $ditolak->linkOrderItem[0]->linkItem->nama }}</h5>   
                <h5 class="fw-normal">Rp {{ number_format($ditolak->linkInvoice->harga_total,0,"",".") }}</h5>
                <h5 class="fw-normal">{{ date('d F Y', strtotime($ditolak->linkOrderTrack->waktu_dikonfirmasi)) }}</h5> 
                <button type="button" class="btn btn-primary w-50 my-2 mx-2" data-bs-toggle="modal" data-bs-target="#order{{ $ditolak->id }}">
                    Lihat Detail >>>
                </button>
        
                  <!-- Modal -->
                  <div class="modal fade" id="order{{ $ditolak->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">{{ $ditolak->linkInvoice->nomor_invoice }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                          <div class="row">
                              <div class="col-5">
                                  <h5>Nomor Invoice : </h5>
                              </div>
                              <div class="col-5">
                                <h5 class="fw-normal">{{ $ditolak->linkInvoice->nomor_invoice }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Tanggal : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ date('d F Y', strtotime($ditolak->linkOrderTrack->waktu_dikonfirmasi)) }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Status : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal text-danger">{{ $ditolak->linkOrderTrack->linkStatus->nama }}</h5>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Event yang berlangsung : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $ditolak->linkInvoice->linkEvent->nama ?? "-" }}</h5>
                            </div>
                          </div>
                          @if($ditolak->linkInvoice->linkEvent && $ditolak->linkInvoice->linkEvent->diskon!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Diskon : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $ditolak->linkInvoice->linkEvent->diskon }}</h5>
                            </div>
                          </div>
                          @elseif($ditolak->linkInvoice->linkEvent && $ditolak->linkInvoice->linkEvent->potongan!=null)
                          <div class="row">
                            <div class="col-5">
                                <h5>Potongan : </h5>
                            </div>
                            <div class="col-5">
                              <h5 class="fw-normal">{{ $ditolak->linkInvoice->linkEvent->potongan }}</h5>
                            </div>
                          </div>
                          @endif
                          <div class="row">
                            <div class="col-5">
                                <h5>Harga Total : </h5>
                            </div> 
                            <div class="col-5">
                                <h5 class="fw-normal">{{ number_format($ditolak->linkInvoice->harga_total,0,"",".") }}</h5>
                            </div>                            
                          </div>
                          <div class="row">
                            <div class="col-5">
                                <h5>Barang yang dibeli : </h5>
                            </div>                            
                          </div>
                          <table class="table table-bordered">
                              <thead>
                                  <th>Kode Barang</th>
                                  <th>Nama Barang</th>
                                  <th>Jumlah</th>
                                  <th>Harga Satuan</th>
                                  <th>Total</th>
                              </thead>
                              <tbody>
                                  @foreach($ditolak->linkOrderItem as $orderItem)
                                    <tr>
                                        <td>{{ $orderItem->linkItem->kode_barang }}</td>
                                        <td>{{ $orderItem->linkItem->nama }}</td>
                                        <td>{{ $orderItem->kuantitas }}</td>
                                        <td>{{ $orderItem->harga_satuan }}</td>
                                        <td>{{ number_format($orderItem->kuantitas*$orderItem->harga_satuan,0,"",".") }}</td>
                                    </tr>
                                  @endforeach
                              </tbody>
                          </table>

                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                  </div>           
            </div>     
            
            
          @endforeach
        </div>         
            
        </div>  
    
        
    </div>
</div>
@endsection