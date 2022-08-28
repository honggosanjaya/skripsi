import { getJSON } from 'jquery';
const Swal = require('sweetalert2')

$(document).on('change', '#event-form input[name=total_harga]', function () {
  let iditem = $(this).data('iditem')
  $('#pengadaan .submit-cart-' + iditem).removeClass('btn-success')
  $('#pengadaan .submit-cart-' + iditem).removeAttr('disabled')
  $('#pengadaan .submit-cart-' + iditem).addClass('btn-primary')
});

if ($('#event-form').length > 0) {
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yyyy = today.getFullYear();
  if (dd < 10) {
    dd = '0' + dd;
  }
  if (mm < 10) {
    mm = '0' + mm;
  }
  today = yyyy + '-' + mm + '-' + dd;
  $('#tanggal_mulai').attr("min", today);
  $('#tanggal_selesai').attr("min", today);

  $(document).on('change', '#tanggal_mulai', function () {
    if ($(this).val() > $('#tanggal_selesai').val()) {
      $('#tanggal_selesai').val($(this).val())
    }
    $('#tanggal_selesai').attr("min", $(this).val());
  });
}


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
      window.location.href = "/supervisor/profil/ubahpassword";
    }
  })
}

// NOTIFIKASI SUPERVISOR
$(".alert_limit").click(function () {
  $(this).toggleClass("active");
  $(".limit_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".limit_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_limit").removeClass("active");
});

$(".alert_opname").click(function () {
  $(this).toggleClass("active");
  $(".opname_notif").toggleClass("m-fadeIn m-fadeOut");
  $(".notif").not(".opname_notif").addClass("m-fadeOut").removeClass("m-fadeIn");
  $(".alert_notif").not(".alert_opname").removeClass("active");
});

