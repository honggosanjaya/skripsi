import { getJSON } from 'jquery';
const Swal = require('sweetalert2')

$(document).ready(function () {
  $('#table').DataTable({
    fixedHeader: true,
    buttons: [
      'searchPanes'
    ],
    dom: 'Bfrtip',
    "order": [],
  });
});

// $(document).on('change', '#pengadaan input[name=total_harga]', function () {
//   let iditem = $(this).data('iditem')
//   $('#pengadaan .submit-cart-' + iditem).removeClass('btn-success')
//   $('#pengadaan .submit-cart-' + iditem).removeAttr('disabled')
//   $('#pengadaan .submit-cart-' + iditem).addClass('btn-primary')
// });

// $(document).on('change', '#pengadaan input[name=quantity]', function () {
//   let iditem = $(this).data('iditem')
//   $('#pengadaan .submit-cart-' + iditem).removeClass('btn-success')
//   $('#pengadaan .submit-cart-' + iditem).removeAttr('disabled')
//   $('#pengadaan .submit-cart-' + iditem).addClass('btn-primary')
// });

// $(document).on('change', '#opname input[name=jumlah]', function () {
//   let iditem = $(this).data('iditem')
//   $('#opname .submit-cart-' + iditem).removeClass('btn-success')
//   $('#opname .submit-cart-' + iditem).removeAttr('disabled')
//   $('#opname .submit-cart-' + iditem).addClass('btn-primary')
// });

// $(document).on('change', '#opname input[name=keterangan]', function () {
//   let iditem = $(this).data('iditem')
//   $('#opname .submit-cart-' + iditem).removeClass('btn-success')
//   $('#opname .submit-cart-' + iditem).removeAttr('disabled')
//   $('#opname .submit-cart-' + iditem).addClass('btn-primary')
// });

$(document).on('click', '#retur-admin .button-submit', function () {
  if ($('#retur-admin select[name=tipe_retur]').val() == 1) {
    $('#retur-admin .open-modal-retur').click()
  } else {
    Swal.fire({
      title: 'Apakah anda yakin untuk menyimpan data ?',
      showDenyButton: true,
      confirmButtonText: 'Ya',
      denyButtonText: `Tidak`,
    }).then((result) => {
      if (result.isConfirmed) {
        $('#form_submit').submit();

      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info');
      }
    })
  }
});

$(document).on('click', '#retur-admin .button-submit-modal', function () {
  Swal.fire({
    title: 'Apakah anda yakin untuk menyimpan data ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#form_submit').submit();

    } else if (result.isDenied) {
      Swal.fire('Changes are not saved', '', 'info');
    }
  })
});

if ($('.status-track').length > 0) {
  let statustrack = $('.status-track').data('status') + 1
  // for (let index = 19; index <= statustrack; index++) {
  for (let index = -1; index <= statustrack; index++) {
    if (index != statustrack) {
      $('.s-' + index).addClass('completed');

      $('.s-' + index).addClass('completed');
    } else {
      $('.s-' + index).addClass('active');
    }
  }
}

$(document).on('click', '#detail-pesanan-admin .btn_terimaPesanan', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk menerima pesanan ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#terimapesanan').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#detail-pesanan-admin .btn_tolakPesanan', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk melolak pesanan ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#tolakpesanan').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#detail-pesanan-admin .btn_konfirmasikeberangkatan', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk mengonfirmasi keberangkatan pengiriman ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#keberangkatan').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#detail-pesanan-admin .pesanan_selesai', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk mengonfirmasi pesanan selesai ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#pesananselesai').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#data-produk .btn-submit', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk menyimpan data ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#data-form').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#data-customer .btn-submit', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk menyimpan data ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#data-form').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#data-pengadaan .btn-submit', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk menyimpan data ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#data-form').submit();
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

$(document).on('click', '#data-opname .btn-submit', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Apakah anda yakin untuk menyimpan data ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = $('#data-opname .btn-submit').attr('href');

    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
})

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
      window.location.href = "/administrasi/profil/ubahpassword";
    }
  })
}

// NOTIFIKASI ADMIN
$(".alert_retur").click(function () {
  $(this).toggleClass("active");
  $(".retur_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".retur_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_retur").removeClass("active");
});

$(".alert_order").click(function () {
  $(this).toggleClass("active");
  $(".order_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".order_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_order").removeClass("active");
});

$(".alert_trip").click(function () {
  $(this).toggleClass("active");
  $(".trip_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".trip_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_trip").removeClass("active");
});

$(".alert_limit").click(function () {
  $(this).toggleClass("active");
  $(".limit_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".limit_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_limit").removeClass("active");
});

$(".alert_reimbursement").click(function () {
  $(this).toggleClass("active");
  $(".reimbursement_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".reimbursement_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_reimbursement").removeClass("active");
});

$(".alert_pajak").click(function () {
  $(this).toggleClass("active");
  $(".pajak_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".pajak_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_pajak").removeClass("active");
});

$(".alert_jatuhtempo").click(function () {
  $(this).toggleClass("active");
  $(".jatuhtempo_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".jatuhtempo_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_jatuhtempo").removeClass("active");
});


// PENAGIHAN LP3
var count = $("#laporan-penagihan .form-group").children().length;

$(document).on('change', '#laporan-penagihan .select-invoice', function (e) {
  let indicator = $(this);
  $.ajax({
    url: window.location.origin + `/api/administrasi/detailpenagihan/${e.target.value}`,
    method: "GET",
    success: function (data) {
      if (data.status == 'success') {
        indicator.parentsUntil('.form-input').find('.nama-customer').val(data.data.customer.nama);
        indicator.closest('.form-input').find('.jumlah-tagihan').val(data.data.tagihan);
      }
    },
  });
})

$(document).on('click', '#laporan-penagihan .add-form', function (e) {
  count++;
  let value = $(this).parents('#laporan-penagihan .form-input').find('.select-invoice option:selected').val();
  // $('.select-invoice option[value="' + value + '"]').attr("disabled", true);
  $('.select-invoice option[value="' + value + '"]').addClass('disabled-option');
  $('#laporan-penagihan .form-input').last().clone().appendTo('#laporan-penagihan .form-group');
  // $(this).addClass('d-none');
  $('#laporan-penagihan .form-input').find('.remove-form').removeClass('d-none');
  $('#laporan-penagihan .form-input').last().find('.select-invoice').val('');
  $('#laporan-penagihan .form-input').last().find('.nama-customer').val('pilih invoice dulu');
  $('#laporan-penagihan .form-input').last().find('.jumlah-tagihan').val('pilih invoice dulu');
  if (count == 1) {
    $('#laporan-penagihan .form-input').find('.remove-form').addClass('d-none');
  }
})

$(document).on('click', '#laporan-penagihan .remove-form', function (e) {
  count--;
  let value = $(this).parents('#laporan-penagihan .form-input').find('.select-invoice option:selected').val();
  // $('.select-invoice option[value="' + value + '"]').removeAttr("disabled");
  $('.select-invoice option[value="' + value + '"]').removeClass('disabled-option');
  $(this).parents('#laporan-penagihan .form-input').remove();
  // $('#laporan-penagihan .form-input:last').find('.add-form').removeClass('d-none');
  if (count == 1) {
    $('#laporan-penagihan .form-input').find('.remove-form').addClass('d-none');
  }
})


const districtspenagihan = [];
$(document).on('change', '#laporan-penagihan .select-district', function (e) {
  $.ajax({
    url: window.location.origin + `/api/administrasi/selectdistict/${e.target.value}`,
    method: "GET",
    success: function (data) {
      console.log(data.customersInvoice);
      districtspenagihan.push(e.target.value);
      // $('#laporan-penagihan .form-input').not(':first').remove();
      // $('#laporan-penagihan .form-input').last().find('.select-invoice').val('');
      $('.select-invoice option').removeClass('disabled-option');
      $('.select-district option[value="' + e.target.value + '"]').attr("disabled", true);

      if (data.customersInvoice.length > 0) {
        data.customersInvoice.map((customer, index) => {
          countCust++;
          $('.select-invoice option[value="' + customer.link_order[0].link_invoice.id + '"]').addClass('disabled-option');
          $('#laporan-penagihan .form-input').last().clone().appendTo('#laporan-penagihan .form-group');
          $('#laporan-penagihan .form-input').find('.remove-form').removeClass('d-none');
          $('#laporan-penagihan .form-input').last().find('.select-invoice').val(customer.link_order[0].link_invoice.id);

          $.ajax({
            url: window.location.origin + `/api/administrasi/detailpenagihan/${customer.link_order[0].link_invoice.id}`,
            method: "GET",
            success: function (data) {
              if (data.status == 'success') {
                $(`#laporan-penagihan .select-invoice:eq(${index})`).parentsUntil('.form-input').find('.nama-customer').val(data.data.customer.nama);
                $(`#laporan-penagihan .select-invoice:eq(${index})`).closest('.form-input').find('.jumlah-tagihan').val(data.data.tagihan);
              }
            },
          });

          if (countCust == 1) {
            $('#laporan-penagihan .form-input').find('.remove-form').addClass('d-none');
          }
        })

        if (districtspenagihan.length == 1) {
          $('#laporan-penagihan .form-input').first().remove();
        }
      }
    }
  });
});

// ======================
// Rencana Kunjungan
var countCust = $("#perencanaan-kunjungan .form-group").children().length;
$(document).on('click', '#perencanaan-kunjungan .add-form', function (e) {
  countCust++;
  let value = $(this).parents('#perencanaan-kunjungan .form-input').find('.select-customer option:selected').val();
  $('.select-customer option[value="' + value + '"]').attr("readonly", true);
  $('.select-customer option[value="' + value + '"]').addClass('disabled-option');
  $('#perencanaan-kunjungan .form-input').last().clone().appendTo('#perencanaan-kunjungan .form-group');
  $('#perencanaan-kunjungan .form-input').find('.remove-form').removeClass('d-none');
  $('#perencanaan-kunjungan .form-input').last().find('.select-customer').val('');
  if (countCust == 1) {
    $('#perencanaan-kunjungan .form-input').find('.remove-form').addClass('d-none');
  }
})

$(document).on('click', '#perencanaan-kunjungan .remove-form', function (e) {
  countCust--;
  let value = $(this).parents('#perencanaan-kunjungan .form-input').find('.select-customer option:selected').val();
  $('.select-customer option[value="' + value + '"]').removeClass('disabled-option');
  $(this).parents('#perencanaan-kunjungan .form-input').remove();
  if (countCust == 1) {
    $('#perencanaan-kunjungan .form-input').find('.remove-form').addClass('d-none');
  }
})

const districts = [];
$(document).on('change', '#perencanaan-kunjungan .select-district', function (e) {
  $.ajax({
    url: window.location.origin + `/api/administrasi/selectdistict/${e.target.value}`,
    method: "GET",
    success: function (data) {
      districts.push(e.target.value);
      // $('#perencanaan-kunjungan .form-input').not(':first').remove();
      // $('#perencanaan-kunjungan .form-input').last().find('.select-customer').val('');
      $('.select-customer option').removeClass('disabled-option');
      $('.select-district option[value="' + e.target.value + '"]').attr("disabled", true);

      if (data.customers.length > 0) {
        data.customers.map((customer, index) => {
          countCust++;
          $('.select-customer option[value="' + customer.id + '"]').addClass('disabled-option');
          $('#perencanaan-kunjungan .form-input').last().clone().appendTo('#perencanaan-kunjungan .form-group');
          $('#perencanaan-kunjungan .form-input').find('.remove-form').removeClass('d-none');
          $('#perencanaan-kunjungan .form-input').last().find('.select-customer').val(customer.id);
          if (countCust == 1) {
            $('#perencanaan-kunjungan .form-input').find('.remove-form').addClass('d-none');
          }
        })
        if (districts.length == 1) {
          $('#perencanaan-kunjungan .form-input').first().remove();
        }
      }
    }
  });
});


// ===========================

// PDF
$("#detail-pesanan-admin .btn-unduh-invoice").click(function () {
  const idOrder = $('#detail-pesanan-admin .btn-unduh-invoice').val();
  const thisis = $(this);

  $.ajax({
    url: window.location.origin + `/api/administrasi/unduhinvoice/${idOrder}`,
    method: "GET",
    success: function (data) {
      if (data.status == 'success') {
        $(`<div class="position-relative"><iframe id="myFrame"
        src="http://127.0.0.1:8000/administrasi/pesanan/detail/${idOrder}/cetak-invoice#toolbar=0"
        style="margin-top:30px;" frameborder="0" width="100%" height="500px">
      </iframe> <div class="embed-cover"></div></div>`).appendTo("#detail-pesanan-admin .detail-pesanan-admin_action");

        const counter_unduh = data.counter_unduh.toString();
        thisis.children('span').text(counter_unduh);
        $('#detail-pesanan-admin .btn-print-pdf').removeClass('d-none');
        $('#detail-pesanan-admin .btn-close-pdf').removeClass('d-none');
      }
    },
  });
});

$("#detail-pesanan-admin .btn-print-pdf").click(function () {
  const myIframe = document.getElementById("myFrame").contentWindow;
  myIframe.focus();
  myIframe.print();
  return false;
});

$("#detail-pesanan-admin .btn-close-pdf").click(function () {
  Swal.fire({
    title: 'Apakah anda yakin untuk enutup invoice ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: 'Tidak',
  }).then((result) => {
    if (result.isConfirmed) {
      $("#detail-pesanan-admin #myFrame").remove();
      $('#detail-pesanan-admin .btn-print-pdf').addClass('d-none');
      $('#detail-pesanan-admin .btn-close-pdf').addClass('d-none');
    } else if (result.isDenied) {
      Swal.fire('Aksi dibatalkan', '', 'info');
    }
  })
});

$('#detail_pesanan-admn .change-item-btn').click(function () {
  $(this).siblings('.nama-item').addClass('d-none');
  $(this).siblings('.select-alt-item').removeClass('d-none');
})

$(document).on('change', '#detail_pesanan-admn .select-alt-item', function (e) {
  $(this).siblings('.change-item-btn').addClass('d-none');
  $(this).siblings('.ok-item-btn').removeClass('d-none');
});