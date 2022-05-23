<html>
<head>
	<title>Invoice</title>
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
        
	</style>
<div class="container">
  <table class="table table-borderless">
    <tr>
        <td style="width: 70%">
            <h5>UD. Mandiri</h5>
            <h6 class="font-weight-normal">Alamat ?</h6>
            <h6 class="font-weight-normal">Nomor Telepon ?</h6>
        </td>
        <td style="width: 30%">
            <h5>Kepada Yth............................................................
                ..................................................................
                ..................................................................
            </h5>
        </td>
    </tr>
  </table>
  <h3 class="mb-4 text-center">Surat Jalan UD. Mandiri</h3>
    	 
	<table class="table table-bordered mt-4">
        <thead>
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>            
            <th scope="col">Kuantitas</th>
            <th scope="col">Keterangan</th>                
          </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->linkItem->kode_barang }}</td>
                    <td>{{ $item->linkItem->nama }}</td>
                    <td>{{ $item->kuantitas }}</td>
                    <td style="border:none"></td>
                </tr>
            @endforeach
            
        </tbody>
      </table>

      
       
        <h5 class="text-right">Malang, {{ $date }}</h5>                          
         
       
                
            

      <table class="table table-borderless text-center mt-5">
        <thead>
          <tr>
            <th scope="col">Penerima,</th>
            <th scope="col">Pengirim,</th>            
            <th scope="col">Mengetahui,</th>                       
          </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding:6%"></td>
                <td style="padding:6%"></td>
                <td style="padding:6%"></td>
            </tr>
            <tr>
                <td>{{ $order->linkCustomer->nama }}</td>
                <td>{{ $pengirim->nama }}</td>
                <td>{{ $mengetahui->nama }}</td>
            </tr>
        </tbody>
      </table>

  
    </div>
</body>
</html>