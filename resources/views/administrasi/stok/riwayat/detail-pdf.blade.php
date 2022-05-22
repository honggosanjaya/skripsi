<html>
<head>
	<title>Laporan NPB</title>
  
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}
	</style>
  <h5 class="mb-4 text-center">Laporan NPB UD. Mandiri</h5>
  <h6 class="mb-4">No Pengadaan : {{ $detail->no_pengadaan }}</h6>
	 
	<table class="table table-bordered">
    <thead>
      <tr>
        <th scope="col">Kode Barang</th>
        <th scope="col">Nama Barang</th>
        <th scope="col">Jumlah Barang</th>
        <th scope="col">Satuan Barang</th>
        <th scope="col">Total Harga</th>                    
      </tr>
    </thead>
    <tbody>
      @foreach ($pengadaans as $pengadaan)
      <tr>
        <th scope="row">{{ $pengadaan->kode_barang }}</th>
        <td>{{ $pengadaan->nama }}</td>
        <td>{{ $pengadaan->kuantitas }}</td>
        <td>{{ $pengadaan->satuan }}</td>
        <td>{{ $pengadaan->harga_total }}</td>
      </tr>
      @endforeach
      <tr>
          <td></td>
          <td></td>
          <td></td>
          <td class="fw-bold">Total</td>
          <td class="fw-bold">{{ $total_harga->harga }}</td>
      </tr>
    </tbody>
  </table>

  <table class="table">
      <tbody>
          <tr>
              <td scope="col" style="width: 15%">No Nota</td>
              <td style="width: 20%">{{ $detail->no_nota }}</td>
              <td style="width: 10%">Tanggal</td>
              <td>{{ $detail->created_at }}</td>
          </tr>
          <tr>
              <td>Keterangan</td>
              <td colspan="3">{{ $detail->keterangan }}</td>
          </tr>
      </tbody>
  </table>
 
</body>
</html>
