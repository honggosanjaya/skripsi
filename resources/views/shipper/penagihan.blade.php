@extends('layouts.mainreact')

@push('JS')
  <script>
    function convertPrice(price) {
      let convertedPrice = 'Rp. ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return convertedPrice;
    }

    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));

    $(".btn_sudah_ditagih").on("click", function(e) {
      $.ajax({
        url: window.location.origin + `/api/lapangan/handlepenagihan/${e.target.value}`,
        method: "get",
        success: function(response) {
          if (response.status == 'success') {
            Swal.fire({
              icon: 'success',
              title: 'berhasil...',
              text: response.message,
            }).then((result) => {
              if (result.isConfirmed) {
                myModal.hide();
                $(`.data_lp3[data-idinvoice = ${response.id_invoice}]`).find('.status_penagihan').html(
                  "<p class='text-success mb-0'>Sudah Tagih</p>");
                $(`.data_lp3[data-idinvoice = ${response.id_invoice}]`).attr('data-sudahtagih', '1');
              }
            })
          }
        }
      })
    });


    $('.data_lp3').on("click", function() {
      const idInvoice = $(this).attr("data-idinvoice");
      const sudahTagih = $(this).attr("data-sudahtagih");
      $.ajax({
        url: window.location.origin + `/api/administrasi/detailpenagihan/${idInvoice}`,
        method: "get",
        success: function(response) {
          // console.log(response.data);
          $('.info-2column .no_invoice').text(response.data.invoice.nomor_invoice);
          $('.info-2column .nama_customer').text(response.data.customer.nama);
          $('.info-2column .telepon_customer').text(response.data.customer.telepon);
          $('.info-2column .alamat_customer').text(response.data.customer.full_alamat);
          $('.info-2column .total_penagihan').text(convertPrice(response.data.tagihan));
          // if (response.data.lp3 !== null) {
          if (sudahTagih == '-1') {
            $('.btn_sudah_ditagih').removeClass('d-none');
            $('.btn_sudah_ditagih').val(response.data.lp3.id);
          } else {
            $('.btn_sudah_ditagih').addClass('d-none');
          }
          myModal.show();
        }
      })
    });
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col" class='text-center'>Nama Toko</th>
            <th scope="col" class='text-center'>Wilayah</th>
            <th scope="col" class='text-center'>Tanggal</th>
            <th scope="col" class='text-center'>Status</th>
          </tr>
        </thead>
        <tbody>
          @if ($tagihans->count() > 0)
            @foreach ($tagihans as $data)
              <tr class="data_lp3 cursor_pointer" data-idinvoice={{ $data->linkInvoice->id }}
                data-sudahtagih={{ $data->status_enum }}>
                <td>{{ $data->linkInvoice->linkOrder->linkCustomer->nama ?? null }}</td>
                <td>{{ $data->linkInvoice->linkOrder->linkCustomer->linkDistrict->nama ?? null }}</td>
                <td>
                  @if ($data->tanggal ?? null)
                    {{ date('d F Y', strtotime($data->tanggal)) }}
                  @endif
                </td>
                <td class="status_penagihan">
                  @if (($data->status_enum ?? null) == '1')
                    <p class='text-success mb-0'>Sudah Tagih</p>
                  @else
                    <p class='text-danger mb-0'>Belum Tagih</p>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="5" class="text-danger text-center">Tidak ada data</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>


    <div class="modal fade" tabindex="-1" id="detailModal" aria-labelledby="detailModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="detailModalLabel">Detail Penagihan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class='info-2column'>
              <span class='d-flex'>
                <b>No. Invoice</b>
                <p class='mb-0 word_wrap no_invoice'></p>
              </span>
              <span class='d-flex'>
                <b>Customer</b>
                <p class='mb-0 word_wrap nama_customer'></p>
              </span>
              <span class='d-flex'>
                <b>Telepon</b>
                <p class='mb-0 word_wrap telepon_customer'></p>
              </span>
              <span class='d-flex'>
                <b>Alamat</b>
                <p class='mb-0 word_wrap alamat_customer'> </p>
              </span>
              <span class='d-flex'>
                <b>Total Penagihan</b>
                <p class='mb-0 word_wrap total_penagihan'> </p>
              </span>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success btn_sudah_ditagih d-none">
              <span class="iconify fs-3 me-1" data-icon="icon-park-outline:doc-success"></span>Sudah Ditagih
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
