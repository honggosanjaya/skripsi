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

    #preview {
      width: 100%;
      display: block;
      margin: auto;
    }
  </style>
@endpush

@push('JS')
  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  <script>
    let scanner = new Instascan.Scanner({
      video: document.getElementById('preview'),
      mirror: false,
      backgroundScan: false,
    });

    $('.btn_scan_qr').on('click', function() {
      scanner.addListener('scan', function(content) {
        window.location.replace(content);
      });
      Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        }
      }).catch(function(e) {
        console.log(e);
      });
    });

    $('#scannerModal').on('hidden.bs.modal', function() {
      $('#cariCustomerModal').modal('show');
      scanner.stop();
    })
  </script>
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

    function showCustomers(customer, index) {
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
                    <a href="/salesman/trip/${customer.id}" class="btn btn-success caricst_btn_trip">
                      <span class="iconify me-1" data-icon="bx:trip"></span>Trip
                    </a>
                    <a href="/salesman/order/${customer.id}" class="btn btn-success caricst_btn_order">
                      <span class="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
                    </a>
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
                    <a href="/salesman/trip/${customer.id}" class="btn btn-success caricst_btn_trip">
                      <span class="iconify me-1" data-icon="bx:trip"></span>Trip
                    </a>
                    <a href="/salesman/order/${customer.id}" class="btn btn-success caricst_btn_order">
                      <span class="iconify me-1" data-icon="carbon:ibm-watson-orders"></span>Order
                    </a>
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
            $('.input_cari_customer').addClass('d-none');
            $('.btn_crcst_kembali').removeClass('d-none');
            $('.customer_tdk_ditemukan_msg').addClass('d-none');
            let customers = "";
            response.data.forEach((customer, index) => {
              customers += showCustomers(customer, index);
            });
            $("#accordionFlushExample").html(customers);
          } else {
            $('.customer_tdk_ditemukan_msg').removeClass('d-none');
          }
        }
      })
    });

    $(document).on('click', '.btn_lihat_foto', function() {
      $(this).parents('.accordion-item').find('.foto_customer').toggleClass('d-none');
    });

    $('.btn_crcst_kembali').on('click', function(e) {
      $(this).addClass('d-none');
      $('.input_cari_customer').removeClass('d-none');
      $('#accordionFlushExample').empty();
    });
  </script>

  <script>
    let listRencanaKunjungan;
    let listHistoryPembelian;
    let showRencanaTripModal = true;

    function showRencanas(rencana) {
      return `<tr data-idcustomer=${rencana.id_customer} data-idkunjungan=${rencana.id_rencana} data-idinvoice=${rencana.id_invoice} data-idlp3=${rencana.id_tagihan} class="btn_detail_rencana" data-bs-toggle="modal" data-bs-target="#detailRencanaModal">
        <td>${rencana.nama_customer}</td>
        <td>${rencana.nama_wilayah}</td>
        <td class="status_rencana ${(rencana.status_rencana == '1' || rencana.status_penagihan == '1') ? 'text-success' : 'text-danger'}">
          ${(rencana.status_rencana ?? '') && (rencana.status_rencana == '1' ? 'Sudah Dikunjungi' : 'Belum Dikunjungi')}
          ${(rencana.status_penagihan ?? '') && (rencana.status_penagihan == '1' ? 'Sudah Ditagih' : 'Belum Ditagih')}
        </td>
      </tr>`;
    }

    function getRencanaKunjungan() {
      $('.loader').removeClass('d-none');
      const id_staff = $('input[name="id_staff"]').val()
      const tanggal_kunjungan = $('input[name="tanggal_kunjungan"]').val()
      $.ajax({
        url: window.location.origin + `/api/getrencanakunjungan/${id_staff}`,
        method: "post",
        data: {
          date: tanggal_kunjungan
        },
        success: function(response) {
          $('.loader').addClass('d-none');
          // console.log(response);
          if (response.status == 'success') {
            listRencanaKunjungan = response.data;
            let rencanas = "";
            response.data.forEach((rencana) => {
              rencanas += showRencanas(rencana);
            });
            $(".tbody_rencanakunjungan").html(rencanas);
          }
        }
      })
    }

    $('.btn_rencana_trip').on('click', function() {
      getRencanaKunjungan();
    })

    $('input[name="tanggal_kunjungan"]').on('change', function() {
      getRencanaKunjungan();
    })

    function convertPrice(price) {
      let convertedPrice = 'Rp. ' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      return convertedPrice;
    }

    function getDate(date) {
      const newDate = date.substring(0, 10);
      const results = newDate.split('-');
      return `${results[2]}-${results[1]}-${results[0]}`;
    }

    $(document).on('click', '.btn_detail_rencana', function() {
      const idkunjungan = $(this).attr("data-idkunjungan");
      const idinvoice = $(this).attr("data-idinvoice");
      const idcustomer = $(this).attr("data-idcustomer");

      if (idkunjungan != 'undefined') {
        const result = listRencanaKunjungan.find(obj => {
          return obj.id_rencana == idkunjungan
        })

        let detailrencana = `<div class='info-2column'>
          <span class='d-flex'>
            <b>Nama Toko</b>
            <p class='mb-0 word_wrap'>${result.nama_customer}</p>
          </span>
          <span class='d-flex'>
            <b>Wilayah</b>
            <p class='mb-0 word_wrap'>${result.nama_wilayah}</p>
          </span>
          <span class='d-flex'>
            <b>Estimasi Nominal</b>
            <p class='mb-0 word_wrap'>
              ${(result.estimasi_nominal ?? '') && convertPrice(result.estimasi_nominal)}
            </p>
          </span>
        </div>`;
        $(".detail_rencana_wrapper").html(detailrencana);
        $('#detailRencanaModal .btn_trip').attr("href", `/salesman/trip/${result.id_customer}`);
        $('#detailRencanaModal .btn_estimasi_permintaan').val(result.id_customer);
        $('#detailRencanaModal .btn_estimasi_permintaan').attr('data-idrencanatrip', idkunjungan);
        $('input[name="id_rencana_trip"]').val(idkunjungan);
        $('#detailRencanaModal .btn_sudah_tagih').addClass('d-none');
      }
      if (idinvoice != 'undefined') {
        $.ajax({
          url: window.location.origin + `/api/administrasi/detailpenagihan/${idinvoice}`,
          method: "get",
          success: function(response) {
            $('.loader').addClass('d-none');
            // console.log(response);
            if (response.status == 'success') {
              let detailtagihan = `<div class='info-2column'>
                <span class='d-flex'>
                  <b>No. Invoice</b>
                  <p class='mb-0 word_wrap'>${response.data.invoice.nomor_invoice}</p>
                </span>
                <span class='d-flex'>
                  <b>Customer</b>
                  <p class='mb-0 word_wrap'>${response.data.customer.nama}</p>
                </span>
                <span class='d-flex'>
                  <b>Telepon</b>
                  <p class='mb-0 word_wrap'>${response.data.customer.telepon}</p>
                </span>
                <span class='d-flex'>
                  <b>Alamat</b>
                  <p class='mb-0 word_wrap'>${response.data.customer.full_alamat}</p>
                </span>
                <span class='d-flex'>
                  <b>Total Penagihan</b>
                  <p class='mb-0 word_wrap'>
                    ${(response.data.tagihan ?? '') && convertPrice(response.data.tagihan)}
                  </p>
                </span>
              </div>`;
              $(".detail_rencana_wrapper").html(detailtagihan);
              $('#detailRencanaModal .btn_trip').attr("href", `/salesman/trip/${response.data.customer.id}`);
              $('#detailRencanaModal .btn_estimasi_permintaan').val(response.data.customer.id);
              $('#detailRencanaModal .btn_estimasi_permintaan').attr('data-idrencanatrip', '');
              if (response.data.lp3 == null) {
                $('#detailRencanaModal .btn_sudah_tagih').addClass('d-none');
              } else {
                if (response.data.lp3.status_enum == '-1') {
                  $('#detailRencanaModal .btn_sudah_tagih').val(response.data.lp3.id).removeClass('d-none');
                } else {
                  $('#detailRencanaModal .btn_sudah_tagih').addClass('d-none');
                }
              }
            }
          }
        })
      }

      function showHistoryBeli(data) {
        return `<div class="d-flex py-2 align-items-center justify-content-between border-bottom data_history_beli" data-idinvoice="${data.id}">
          <h6 class='mb-0'>${getDate(data.created_at)}</h6>
          <h6 class='mb-0'>${convertPrice(data.harga_total)}</h6>
          </div>`;
      }
      $('.loader').removeClass('d-none');
      $.ajax({
        url: window.location.origin + `/api/historyPembelian/${idcustomer}`,
        method: "get",
        success: function(response) {
          $('.loader').addClass('d-none');
          // console.log(response);
          if (response.status == 'success') {
            listHistoryPembelian = response.data;
            let historypembelian = `<p class='mb-0 fw-bold'>History Pembelian</p>`;
            response.data.forEach((data) => {
              historypembelian += showHistoryBeli(data);
            });
            $(".history_pembelian").html(historypembelian);
          }
        }
      })
    })

    $('#detailRencanaModal').on('hidden.bs.modal', function() {
      if (showRencanaTripModal) {
        $('#rencanaTripModal').modal('show')
      }
    })

    $('#detailRencanaModal .btn_sudah_tagih').on('click', function(e) {
      const idLp3 = e.target.value;
      $.ajax({
        url: window.location.origin + `/api/lapangan/handlepenagihan/${idLp3}`,
        method: "get",
        success: function(response) {
          $('.loader').addClass('d-none');
          // console.log(response);
          if (response.status == 'success') {
            $('#detailRencanaModal .btn_sudah_tagih').addClass('d-none');
            $(`.btn_detail_rencana[data-idlp3=${idLp3}]`).find('.status_rencana').removeClass('text-danger')
              .addClass('text-success').text('Sudah Ditagih');
          }
        }
      })
    })
  </script>

  <script>
    var isOrder = false;
    var cariCustomerModal = new bootstrap.Modal(document.getElementById('cariCustomerModal'));
    $('.btn_menu_trip').on('click', function() {
      $('.btn_scan_qr').removeClass('d-none');
      cariCustomerModal.show();
      isOrder = false;
    })

    $('.btn_menu_order').on('click', function() {
      $('.btn_scan_qr').addClass('d-none');
      cariCustomerModal.show();
      isOrder = true;
    })

    $(document).on('click', '.accordion-button', function() {
      if (isOrder == false) {
        $('.caricst_btn_trip').removeClass('d-none');
        $('.caricst_btn_order').addClass('d-none');
      } else {
        $('.caricst_btn_trip').addClass('d-none');
        $('.caricst_btn_order').removeClass('d-none');
      }
    })
  </script>

  <script>
    function showHslBeliItem(item) {
      return `<tr>
                <td>${item.link_item.nama}</td>
                <td class='text-center'>${item.kuantitas}</td>
              </tr>`;
    }

    $(document).on('click', '.data_history_beli', function() {
      const idinvoice = $(this).attr("data-idinvoice");
      const result = listHistoryPembelian.find(obj => {
        return obj.id == idinvoice
      })

      let detailHistBeliItem = "";
      result.link_order.link_order_item.forEach((item) => {
        detailHistBeliItem += showHslBeliItem(item);
      });

      let detailHistBeli = `<div>
          <div class='info-2column'>
            <span class='d-flex'>
              <b>No Invoice</b>
              <p class='mb-0 word_wrap'>${result.nomor_invoice}</p>
            </span>
            <span class='d-flex'>
              <b>Tanggal</b>
              <p class='mb-0 word_wrap'>${getDate(result.created_at)}</p>
            </span>
          </div>
          <table class="table">
            <thead>
              <tr>
                <th scope="col" class='text-center'>Nama Item</th>
                <th scope="col" class='text-center'>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              ${detailHistBeliItem}
            </tbody>
          </table>
        </div>`

      $('.modal_detail_rencana_wrapper1').addClass('d-none');
      $('.modal_detail_rencana_wrapper2').removeClass('d-none');
      $('.modal_detail_rencana_wrapper2').html(detailHistBeli);
      $('#detailRencanaModal .modal-footer').addClass('d-none');
      $('#detailRencanaModal .btn-close').addClass('d-none');
      $('#detailRencanaModal .btn-close2').removeClass('d-none');
    })

    $(document).on('click', '.btn-close2', function() {
      $('.modal_detail_rencana_wrapper1').removeClass('d-none');
      $('.modal_detail_rencana_wrapper2').addClass('d-none');
      $('.modal_detail_rencana_wrapper2').html('');
      $('#detailRencanaModal .modal-footer').removeClass('d-none');
      $('#detailRencanaModal .btn-close').removeClass('d-none');
      $('#detailRencanaModal .btn-close2').addClass('d-none');
    })

    function showItems(item, index) {
      return `<tr>
                <td class="align-middle">${item.nama_item}</td>
                <td class="text-center align-middle">${item.rata_pengambilan}</td>
                <td class="text-center align-middle">${item.durasi_rata_pengambilan}</td>
                <td class="align-middle">${convertPrice(item.price)}</td>
              </tr>`;
    }

    $('.btn_estimasi_permintaan').on('click', function(e) {
      let idRencanaTrip = $(this).attr("data-idrencanatrip");
      let urlApi;
      if (idRencanaTrip != undefined && idRencanaTrip != '') {
        urlApi = `/api/salesman/estimasiPermintaanToko/${e.target.value}?idrencanatrip=${idRencanaTrip}`;
        $('.catatanEstimasiBeli').removeClass('d-none');
      } else {
        urlApi = `/api/salesman/estimasiPermintaanToko/${e.target.value}`;
        $('.catatanEstimasiBeli').addClass('d-none');
      }

      $.ajax({
        url: window.location.origin + urlApi,
        method: "get",
        success: function(response) {
          // console.log(response);
          if (response.status == 'success') {
            if (response.catatan) {
              $('textarea[name="catatan_estimasi_pembelian"]').val(response.catatan);
            } else {
              $('textarea[name="catatan_estimasi_pembelian"]').val('');
            }
            if (response.data.length > 0) {
              let items = "";
              response.data.forEach((item, index) => {
                items += showItems(item, index);
              });
              $("#estimasiPembelianModal tbody").html(items);
            } else {
              $("#estimasiPembelianModal tbody").html(`<tr>
                  <td colspan = "4" class = "text-danger text-center">Tidak Ada Data</td> 
                </tr>`);
            }
            showRencanaTripModal = false;
            $('#detailRencanaModal').modal('hide');
            $('#estimasiPembelianModal').modal('show');
            $('#rencanaTripModal').modal('hide');
          }
        }
      })
    });

    $('#estimasiPembelianModal').on('hidden.bs.modal', function() {
      $('#detailRencanaModal').modal('show');
      $('#rencanaTripModal').modal('hide');
      showRencanaTripModal = true;
    });

    $('#estimasiPembelianModal').on('hidden.bs.modal', function() {
      let idRencanaTrip = $('input[name="id_rencana_trip"]').val();
      const catatan = $('textarea[name="catatan_estimasi_pembelian"]').val();
      if (catatan !== undefined && idRencanaTrip !== undefined && idRencanaTrip !== '') {
        $.ajax({
          url: window.location.origin + `/api/salesman/estimasiPermintaanToko/catatan/${idRencanaTrip}`,
          method: "post",
          data: {
            catatan: catatan
          },
          success: function(response) {
            // console.log(response);
          }
        })
      }
    })
  </script>
@endpush

@section('main_content')
  <div class="loader d-none"></div>
  <input type="hidden" value="{{ auth()->user()->id_users }}" name="id_staff">
  <div class="page_container pt-4">
    <h1 class='fs-6 fw-bold mb-3'>Menu untuk Salesman</h1>
    <button class='btn btn-primary btn-lg w-100 btn_menu_trip'>
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
              <button class="btn btn-primary btn_rencana_trip" data-bs-toggle="modal" data-bs-target="#rencanaTripModal">
                <span class="iconify fs-3 me-1" data-icon="flat-color-icons:planner"></span>Rencana Trip
              </button>
              <button class="btn btn-success btn_scan_qr" data-bs-toggle="modal" data-bs-target="#scannerModal">
                <span class="iconify fs-3 me-1" data-icon="bx:qr-scan"></span>Scan QR
              </button>
            </div>

            <form class="mt-4 form_cari_customer">
              <div class="input_cari_customer">
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
              </div>
              <div class="d-flex justify-content-end btn_crcst_kembali d-none">
                <button type="button" class="btn btn-primary">
                  <span class="iconify me-2" data-icon="fe:search"></span>Cari Kembali</button>
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

    <div class="modal fade" id="rencanaTripModal" tabindex="-1" aria-labelledby="rencanaTripModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="rencanaTripModalLabel">Rencana Kunjungan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Tanggal Kunjungan</label>
              <input type='date' class="form-control" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}">
            </div>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col" class='text-center'>Nama Toko</th>
                    <th scope="col" class='text-center'>Wilayah</th>
                    <th scope="col" class='text-center'>Status</th>
                  </tr>
                </thead>
                <tbody class="tbody_rencanakunjungan">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="detailRencanaModal" tabindex="-1" aria-labelledby="detailRencanaModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="detailRencanaModalLabel">Detail Rencana</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <i class="bi bi-x-lg btn-close2 d-none"></i>
          </div>
          <div class="modal-body">
            <div class="modal_detail_rencana_wrapper1">
              <div class="detail_rencana_wrapper">
              </div>
              <div class="history_pembelian mt-4">
              </div>
            </div>
            <div class="modal_detail_rencana_wrapper2 d-none">
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="id_rencana_trip">
            <button class="btn btn-sm btn-primary btn_estimasi_permintaan">Estimasi Permintaan</button>
            <a href="#" class="btn btn-success btn_trip btn-sm">
              <span class="iconify me-1" data-icon="bx:trip"></span>Trip
            </a>
            <button type="button" class="btn btn-purple btn_sudah_tagih btn-sm">
              <span class="iconify me-1" data-icon="icon-park-outline:doc-success"></span>Sudah Ditagih
            </button>
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" aria-label="Close">
              <span class="iconify me-1" data-icon="carbon:close-outline"></span>Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="scannerModal" tabindex="-1" aria-labelledby="scannerModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="scannerModalLabel">Scan QR Code</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <video id="preview"></video>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="estimasiPembelianModal" tabindex="-1" aria-labelledby="estimasiPembelianModalLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="estimasiPembelianModalLabel">Estimasi Pembelian</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-floating mb-3 catatanEstimasiBeli d-none">
              <textarea class="form-control" name="catatan_estimasi_pembelian"></textarea>
              <label for="floatingTextarea">Catatan</label>
            </div>

            <table class="table">
              <thead>
                <tr>
                  <th scope="col" class="text-center">Nama Barang</th>
                  <th scope="col" class="text-center">Rata2 Ambil</th>
                  <th scope="col" class="text-center">Durasi Rata2</th>
                  <th scope="col" class="text-center">Harga Satuan</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
              <span class="iconify me-1" data-icon="carbon:close-outline"></span>Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

    <button class='btn btn-success btn-lg w-100 mt-4 btn_menu_order'>
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