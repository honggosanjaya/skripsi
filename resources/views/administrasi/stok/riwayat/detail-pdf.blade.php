<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
 
  
  <div class="container">

    <table class="table table-bordered mt-4">
      <thead>
        <tr>
          <th scope="col">Kode</th>
          <th scope="col">Nama Barang</th>
          <th scope="col">Jumlah</th>
          <th scope="col">Satuan</th>
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
            <td class="fw-bold">{{ $total_harga[0]->harga }}</td>
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
  </div>
  
  

  

</body>
</html>

