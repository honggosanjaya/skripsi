<html>
<head>
	<title>Invoice - {{ $order->linkInvoice->nomor_invoice }}</title>
  
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
  <h3 class="mb-4 text-center">Invoice UD. Mandiri</h3>
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td style="width: 55%">
                    <h4 class="mb-0">No Invoice : </h4>
                </td>
                <td style="width: 55%">
                    <h5 class="text-success p-0">{{ $order->linkInvoice->nomor_invoice }}</h5>
                </td>
            </tr>  
            <tr>
                <td style="width: 55%">
                    <h4 class="mb-0">Diterbitkan Kepada : </h4>
                </td>
                <td style="width: 55%">
                    <h5 class="font-weight-normal p-0">{{ $order->linkCustomer->nama }}</h5>
                </td>
            </tr>     
            <tr>
                <td style="width: 55%">
                    <h4 class="mb-4">Alamat: </h4>
                </td>
                <td style="width: 55%">
                    <h5 class="font-weight-normal p-0">{{ $order->linkCustomer->alamat_utama }}</h5>
                </td>
            </tr>  
            <tr>
                <td style="width: 55%">
                    <h4 class="mb-0">Penjual : </h4>
                </td>
                <td style="width: 55%">
                    <h5 class="font-weight-normal p-0">{{ $order->linkStaff->nama }}</h5>
                </td>                             
            </tr>
            <tr>
                <td style="width: 55%">
                    <h4 class="mb-0">Tanggal Pesan : </h4>
                </td>
                <td style="width: 55%">
                    <h5 class="font-weight-normal p-0">{{ date('d M Y', strtotime($order->linkInvoice->created_at)) }}</h5>
                </td>                
            </tr>
            
        </tbody>
    </table>
	 
	<table class="table table-bordered mt-4">
        <thead>
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Harga Satuan</th>
            <th scope="col">Kuantitas</th>
            <th scope="col">Harga Total</th>                
          </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->linkItem->kode_barang }}</td>
                    <td>{{ $item->linkItem->nama }}</td>
                    <td>{{ number_format($item->harga_satuan,0,"",".") }}</td>
                    <td>{{ number_format($item->kuantitas,0,"",".") }}</td>
                    <td>{{ number_format($item->harga_satuan * $item->kuantitas,0,"",".") }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4" class="text-center font-weight-bold">Total : </td>
                <td>{{ number_format($order->linkInvoice->harga_total,0,"",".") }}</td>
            </tr>
        </tbody>
      </table>

      <br><br>
      <p class="text-right mt-4">Mengetahui,</p>
      <br><br><br><br>
      <p class="text-right">{{ $administrasi->nama }}</p>

  
    </div>
</body>
</html>
