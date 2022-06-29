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

