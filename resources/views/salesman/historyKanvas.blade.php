@extends('layouts.mainreact')

@push('CSS')
@endpush

@push('JS')
  <script>
    var myModal = new bootstrap.Modal(document.getElementById('detailModal'));

    function showDetail(kanvas, index) {
      return `<tr>
                <td class='text-center'>${index + 1}</td>
                <td class='text-center'>${kanvas[0].link_item.nama}</td>
                <td class='text-center'>${kanvas[0].stok_awal}</td>
                <td class='text-center'>${kanvas[0].sisa_stok}</td>
              </tr>`
    }

    $('.btn_detail_kanvas').on('click', function() {
      const idskanvas = $(this).attr("data-idskanvas");
      const ids = idskanvas.replace(/,/g, '-');
      $('.loader').removeClass('d-none');
      $.ajax({
        url: window.location.origin + `/api/administrasi/getDetailKanvas/${ids}`,
        method: "get",
        success: function(response) {
          // console.log(response);
          if (response.status == 'success') {
            $('.nama_kanvas').text(response.data[0][0].nama);
            if (response.data[0][0].link_staff_pengonfirmasi_pengembalian != null) {
              $('.pengonfirmasi_bawa').text(response.data[0][0].link_staff_pengonfirmasi_pembawaan.nama);
            }
            if (response.data[0][0].link_staff_pengonfirmasi_pengembalian == null) {
              $('.info_pengonfirmasi_kembali').addClass('d-none');
            } else {
              $('.info_pengonfirmasi_kembali').removeClass('d-none');
              $('.pengonfirmasi_kembali').text(response.data[0][0].link_staff_pengonfirmasi_pengembalian.nama);
            }

            let detailkanvas = "";
            response.data.forEach((kanvas, index) => {
              detailkanvas += showDetail(kanvas, index);
            });
            $(".tbody_detailkanvas").html(detailkanvas);
            myModal.show();
            $('.loader').addClass('d-none');
          }
        }
      })
    })
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <div class="loader d-none"></div>

    <div class="table-responsive">
      <table class="table mt-3">
        <thead>
          <tr>
            <th scope="col" class='text-center' style="width: 5%;">No</th>
            <th scope="col" class='text-center' style="width: 25%;">Nama</th>
            <th scope="col" class='text-center' style="width: 25%;">W.Bawa</th>
            <th scope="col" class='text-center' style="width: 25%;">W.Kmbl</th>
            <th scope="col" class='text-center' style="width: 20%;">Status</th>
          </tr>
        </thead>
        <tbody>
          {{-- {{ dd($listkanvas) }} --}}
          @foreach ($listkanvas as $kanvas)
            <tr class="btn_detail_kanvas cursor_pointer" data-idskanvas={{ $kanvas->ids }}>
              <td class="text-center">{{ $loop->iteration }}</td>
              <td>{{ $kanvas->nama ?? null }}</td>
              <td>
                @if ($kanvas->waktu_dibawa ?? null)
                  {{ date('j F Y, G:i', strtotime($kanvas->waktu_dibawa)) }}
                @endif
              </td>
              <td>
                @if ($kanvas->waktu_dikembalikan ?? null)
                  {{ date('j F Y, G:i', strtotime($kanvas->waktu_dikembalikan)) }}
                @endif
              </td>
              <td>
                @if ($kanvas->waktu_dikembalikan ?? null)
                  Sdh Kmbl
                @else
                  Blm Kmbl
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="detailModalLabel">Detail Kanvas</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class='info-2column'>
              <span class='d-flex'>
                <b>Nama</b>
                <p class='mb-0 word_wrap nama_kanvas'></p>
              </span>
              <span class='d-flex'>
                <b>P. pembawaan</b>
                <p class='mb-0 word_wrap pengonfirmasi_bawa'></p>
              </span>
              <span class='d-flex info_pengonfirmasi_kembali'>
                <b>P. pengembalian</b>
                <p class='mb-0 word_wrap pengonfirmasi_kembali'></p>
              </span>

              <div class="table-responsive">
                <table class="table mt-3">
                  <thead>
                    <tr>
                      <th scope="col" class='text-center'>No</th>
                      <th scope="col" class='text-center'>Nama Barang</th>
                      <th scope="col" class='text-center'>Stok Awal</th>
                      <th scope="col" class='text-center'>Sisa Stok</th>
                    </tr>
                  </thead>
                  <tbody class="tbody_detailkanvas">



                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <span class="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
