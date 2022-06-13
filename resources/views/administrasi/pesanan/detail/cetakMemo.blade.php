<html>
<head>
	<title>Memo - {{ $order->linkInvoice->nomor_invoice }}</title>
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
        .indent{
            text-indent: 35px;
            text-align: justify;
        }
        h6{
            font-weight: normal;
        }
	</style>
<div class="container">
  <h4 class="mb-4 text-center">Memo Persiapan Barang UD. Mandiri</h4>
  <br>
  <h5>Nomor : <span class="font-weight-normal">{{ $order->linkInvoice->nomor_invoice }}/{{ date('d-m-Y', strtotime($order->linkOrderTrack->waktu_diteruskan)) }}</span></h5>
  <h5>Hal : <span class="font-weight-normal">Persiapan barang dari nomor invoice {{ $order->linkInvoice->nomor_invoice }}</span></h5>
  <br>
  <h5 class="text-right">Malang, {{ $date }}</h5>
  <br>
  <h6>Kepada Tenaga Pembantu,</h6>
  <p class="indent">Sehubungan dengan customer yang telah memesan barang dengan nomor invoice {{ $order->linkInvoice->nomor_invoice }},
    pada tanggal {{ date('d-m-Y', strtotime($order->linkOrderTrack->waktu_diteruskan)) }},kami meminta kepada saudara untuk mempersiapkan barang-barang yang dipesan untuk dilanjutkan ke bagian pengiriman. 
  </p>
  <p class="indent">
      Berikut detail barang yang perlu disiapkan : 
  </p>
	 
	<table class="table table-bordered mt-4">
        <thead>
          <tr>
            <th scope="col">Kode Barang</th>
            <th scope="col">Nama Barang</th>
            <th scope="col">Jumlah</th>                           
          </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->linkItem->kode_barang }}</td>
                    <td>{{ $item->linkItem->nama }}</td>                    
                    <td>{{ $item->kuantitas }}</td>                    
                </tr>
            @endforeach            
        </tbody>
      </table>
    
    <p class="indent">
        Demikian permintaan kami, atas perhatiannya kami ucapkan terima kasih.
    </p>

    <br><br>
      <p class="text-right mt-4">Mengetahui,</p>
      <br><br><br><br>
      <p class="text-right">{{ $administrasi->nama }}</p>

  
    </div>
</body>
</html>
