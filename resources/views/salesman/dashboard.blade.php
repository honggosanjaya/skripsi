@extends('layouts.mainreact')

@push('CSS')
  <style>
    .accordion img {
      width: 100%;
      margin-top: 0.5rem;
      max-height: 250px;
      object-fit: cover;
      object-position: center;
    }
  </style>
@endpush

@push('JS')
  <script>
    @if (session()->has('successMessage'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('successMessage') }}",
        showConfirmButton: false,
        timer: 1500,
      });
    @endif
  </script>

  <script>
    let urlAsset = "{{ env('MIX_APP_URL') }}";
    const loginPassword = $('input[name="loginPassword"]').val();
    const countt = $('input[name="countt"]').val();

    if (loginPassword == "12345678" && countt == 2) {
      Swal.fire({
        title: 'Anda Menggunakan Password Default',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ubah Password!'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "/salesman/changepassword";
        }
      })
    }

    function showCards(customer, index) {
      if (customer.koordinat) {
        return `<div class="accordion-item">
                <div class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse${index}" aria-expanded="false" aria-controls="flush-collapse${index}">
                    <div class="row">
                    <div class="col-5">
                      <span>${customer.nama} | ${customer.link_customer_type &&
                        customer.link_customer_type.nama}</span>
                    </div>
                    <div class="col-4">
                      <span>${customer.full_alamat}</span>
                    </div>
                    <div class="col-3">
                      <span>${customer.link_district && customer.link_district.nama}</span>
                    </div>
                  </div>
                  </button>
                </div>
                <div id="flush-collapse${index}" class="accordion-collapse collapse py-2" data-bs-parent="#accordionFlushExample">
                    <p class='mb-2 fw-bold'>Keterangan alamat</p>
                  <p class="mb-2 fs-7">${customer.keterangan_alamat == null ? '' : customer.keterangan_alamat}</p>
                  <div class="action d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-primary btn_lihat_foto">
                      <span class="iconify me-1" data-icon="ant-design:picture-outlined"></span>Lihat Foto
                    </button>
                    <a class="btn btn-purple" target="_blank"
                      href="https://www.google.com/maps/search/?api=1&query=${customer.koordinat.replace("@", "," )}">
                      <span class="iconify me-1" data-icon="bxs:map"></span>
                      <span class="mb-0 word_wrap">GPS</span>
                    </a>
                    <a href="/salesman/trip/${customer.id}" class="btn btn-success">Trip</a>
                  </div>
                  <div class="foto_customer d-none">
                  ${customer.foto ? '<img src="'+urlAsset+'/storage/customer/'+customer.foto+'">' 
                    : '<span class="mt-2 d-block text-center">Tidak ada foto</span>'}
                  </div>
                </div>
              </div>`;
      } else {
        return `<div class="accordion-item">
                <div class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse${index}" aria-expanded="false" aria-controls="flush-collapse${index}">
                    <div class="row">
                    <div class="col-5">
                      <span>${customer.nama} | ${customer.link_customer_type &&
                        customer.link_customer_type.nama}</span>
                    </div>
                    <div class="col-4">
                      <span>${customer.full_alamat}</span>
                    </div>
                    <div class="col-3">
                      <span>${customer.link_district && customer.link_district.nama}</span>
                    </div>
                  </div>
                  </button>
                </div>
                <div id="flush-collapse${index}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <p class='mb-2 fw-bold'>Keterangan alamat</p>
                  <p class="mb-2 fs-7">${customer.keterangan_alamat == null ? '' : customer.keterangan_alamat}</p>
                  <div class="action d-flex justify-content-between mt-3">
                    <button type="button" class="btn btn-primary btn_lihat_foto">
                      <span class="iconify me-1" data-icon="ant-design:picture-outlined"></span>Lihat Foto
                    </button>
                    <a href="/salesman/trip/${customer.id}" class="btn btn-success">Trip</a>
                  </div>
                  <div class="foto_customer d-none">
                  ${customer.foto ? '<img src="'+urlAsset+'/storage/customer/'+customer.foto+'">' 
                    : '<span class="mt-2 d-block text-center">Tidak ada foto</span>'}
                    </div>
                </div>
              </div>`;
      }
    }

    $('.form_cari_customer').on('submit', function(e) {
      e.preventDefault();
      const namaCust = $('input[name="nama_customer"]').val();
      const alamatUtama = $('input[name="alamat_utama"]').val();
      $.ajax({
        url: window.location.origin + `/api/cariCustomer`,
        method: "post",
        data: {
          nama: namaCust,
          alamat_utama: alamatUtama,
        },
        success: function(response) {
          if (response.status == 'success') {
            $('.customer_tdk_ditemukan_msg').addClass('d-none');
            let cards = "";
            response.data.forEach((customer, index) => {
              cards += showCards(customer, index);
            });
            $("#accordionFlushExample").html(cards);
          } else {
            $('.customer_tdk_ditemukan_msg').removeClass('d-none');
          }
        }
      })
    })

    $(document).on('click', '.btn_lihat_foto', function() {
      $(this).parents('.accordion-item').find('.foto_customer').toggleClass('d-none');
    })
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <h1 class='fs-6 fw-bold mb-4'>Menu untuk Salesman</h1>
    <button class='btn btn-primary btn-lg w-100' data-bs-toggle="modal" data-bs-target="#cariCustomerModal">
      <span class="iconify fs-4 me-2" data-icon="bx:trip"></span> Trip
    </button>

    <div class="modal fade" id="cariCustomerModal" tabindex="-1" aria-labelledby="cariCustomerModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="cariCustomerModalLabel">Cari Customer</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex justify-content-between">
              <button class="btn btn-primary">
                <span class="iconify fs-3 me-1" data-icon="flat-color-icons:planner"></span>Rencana Trip
              </button>
              <button class="btn btn-success">
                <span class="iconify fs-3 me-1" data-icon="bx:qr-scan"></span>Scan QR
              </button>
            </div>

            <form class="mt-4 form_cari_customer">
              <div class="mb-3">
                <label class="form-label">Nama Customer</label>
                <input type="text" class="form-control" name="nama_customer">
              </div>
              <div class="mb-3">
                <label class="form-label">Alamat Utama</label>
                <input type="text" class="form-control" name="alamat_utama">
              </div>
              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                  <span class="iconify me-2" data-icon="fe:search"></span>Cari</button>
              </div>
            </form>

            <div class="box-list-customer mt-3">
              <small class="d-block text-center text-danger customer_tdk_ditemukan_msg d-none">Data Tidak
                Ditemukan</small>
              <div class="accordion accordion-flush" id="accordionFlushExample">
              </div>
            </div>

            <a href="/salesman/tambahcustomer" class="btn btn-primary w-100 mt-4">
              Masih belum menemukan? <br /> Silahkan tambah baru!
            </a>
          </div>
        </div>
      </div>
    </div>


    {{-- ======== --}}
    <button class='btn btn-success btn-lg w-100 mt-4'>
      <span class="iconify fs-4 me-2" data-icon="carbon:ibm-watson-orders"></span> Order
    </button>

    <a href="/lapangan/reimbursement" class='btn btn-purple btn-lg w-100 mt-4'>
      <span class="iconify fs-3 me-2" data-icon="mdi:cash-sync"></span> Reimbursement
    </a>

    <a href="/salesman/riwayatinvoice" class='btn btn-danger btn-lg w-100 mt-4'>
      <span class="iconify fs-3 me-2" data-icon="fa-solid:file-invoice-dollar"></span> Riwayat Invoice
    </a>

    <a href="/lapangan/penagihan" class='btn btn-info btn-lg w-100 mt-4 text-white'>
      <span class="iconify fs-3 me-2 text-white" data-icon="uil:bill"></span> Penagihan
    </a>

    <a href='/lapangan/jadwal' class='btn btn-primary btn-lg w-100 mt-3'>
      <span class="iconify me-2" data-icon="fa-solid:shipping-fast"></span>
      Pengiriman
    </a>

    <a href='/salesman/itemkanvas' class='btn btn-success btn-lg w-100 mt-3'>
      <span class="iconify fs-3 me-2" data-icon="fluent:tray-item-remove-24-filled"></span>
      Item Kanvas
    </a>

    <input type="hidden" name="loginPassword" value="{{ session('password') }}">
    <input type="hidden" name="countt" value="{{ session('count') }}">
  </div>
@endsection
