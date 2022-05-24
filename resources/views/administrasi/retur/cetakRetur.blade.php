<html>
<head>
	<title>Retur - {{ $retur->no_retur }}</title>
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
        
	</style>
< class="container">
    <div class="row mt-3">
        <h4>Retur - {{ $retur->no_retur }}</h4>   
    </div>
    
      
      
        <table class="table table-borderless mt-4">
            <tbody>
                <tr>
                    <td style="width: 35%"><h5>Tanggal : </h5></td>
                    <td>{{ date('d-m-Y', strtotime($retur->created_at)) }}</td>
                </tr>
                <tr>
                    <td><h5>Nama Customer : </h5></td>
                    <td>{{ $retur->linkCustomer->nama }}</td>
                </tr>
                <tr>
                    <td><h5>Alamat : </h5></td>
                    <td>{{ $retur->linkCustomer->alamat_utama . ' ' . $retur->linkCustomer->alamat_nomor }}</td>
                </tr>
                <tr>
                    <td><h5>Wilayah : </h5></td>
                    <td>{{ $wilayah[0] }}</td>
                </tr>
                <tr>
                    <td><h5>No Telepon : </h5></td>
                    <td>{{ $retur->linkCustomer->telepon }}</td>
                </tr>
                <tr>
                    <td><h5>Pengirim : </h5></td>
                    <td>{{ $retur->linkStaffPengaju->nama }}</td>
                </tr>
                <tr>
                    <td><h5>Admin : </h5></td>
                    <td>{{ $administrasi->nama }}</td>
                </tr>
                <tr>
                    <td><h5>Nomor Invoice : </h5></td>
                    <td>{{ $retur->linkInvoice->nomor_invoice }}</td>
                </tr>
                
            </tbody>
          </table>
      
      
    
    <div>
        <table class="table table-bordered mt-5">
            <thead>
              <tr>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
                <th scope="col">Jumlah</th>
                <th scope="col">Satuan Barang</th>
                <th scope="col">Alasan Retur</th>                
              </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->kode_barang }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kuantitas }}</td>
                        <td>{{ $item->satuan }}</td>
                        <td>{{ $item->alasan }}</td>
                    </tr>
                @endforeach
                
            </tbody>
          </table>
    
          
      </div>

      <table class="table table-borderless text-center mt-5">
        <thead>
          <tr>
            <th scope="col">Penerima,</th>
            <th scope="col"></th>            
            <th scope="col">Pengirim,</th>                       
          </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:5%"></td>
                <td style="padding:5%"></td>
                <td style="padding:5%"></td>
            </tr>
            <tr>
                <td>{{ $retur->linkCustomer->nama ?? "" }}</td>
                <td></td>
                <td>{{ $retur->linkStaffPengaju->nama }}</td>
            </tr>
        </tbody>
      </table>

  
</div>
</body>
</html>
