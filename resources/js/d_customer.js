import { getJSON } from 'jquery';

var filtered = false;

$(document).on('click', '.submit-filter-produk', function () {
  filter();
});

$(document).on('click', '.plus-button', function () {
  let oldval = $(this).siblings('#quantity').val();
  let newval;
  if (oldval == '') {
    newval = 1;
  } else {
    newval = parseInt(oldval) + 1;
  }
  $(this).siblings('#quantity').val(newval).trigger('change');
})

$(document).on('click', '.minus-button', function () {
  let oldval = parseInt($(this).siblings('#quantity').val());
  let newval;
  if (oldval == 0) {
    newval = oldval;
  } else {
    newval = parseInt(oldval) - 1;
    $(this).siblings('#quantity').val(newval).trigger('change');
  }
})

$(document).on('change', 'input[name=quantity]', function () {
  var form = $(this).closest("form").serialize()
  $.ajax({
    url: window.location.origin + "/api/customer/order/cart?route=customerOrder",
    method: "POST",
    data: form,
    success: function (data) {
      if (data.status == 'success') {
        if (data.quantityCart != null && data.quantityCart != 0) {
          $('.cart-quantity').text(data.quantityCart).removeClass("d-none")
        } else {
          $('.cart-quantity').text(0).addClass("d-none")
        }
        if (filtered == true) {
          filter();
        }
      }
    },
  });
});

function filter() {
  var filter = $("input[name=filter]:checked").val();
  var order = $("input[name=order]:checked").val();
  $.getJSON(window.location.origin + '/api/filterProduk', { filter: filter, order: order }, function (data) {
    if (data.status = 'success') {
      $("#list-produk").html(data.html);
      $(".close-filter-produk").trigger("click");
      filtered = true;
    }
    else {
      alert('Data not found')
      $(".close-filter-produk").trigger("click");
      filtered = true;
    }
  });
}

const Swal = require('sweetalert2')
$(document).ready(function () {
  $(document).on('click', '.hapus_btn', function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Apakah anda yakin untuk membatalkan pesanan ?',
      showDenyButton: true,
      confirmButtonText: 'Ya',
      denyButtonText: `Tidak`,
    }).then((result) => {
      if (result.isConfirmed) {
        $('.handleHapusKode').submit();
        Swal.fire('Pesanan Dibatalkan !', '', 'success')
      }
    })
  })
});

$(document).ready(function () {
  $('.logout_link').click(function () {
    $('#logout_form').submit();
  })
});

$(document).ready(function () {
  $(document).on('click', '.btn_deleteCart', function (e) {
    if (!confirm('Are you sure?')) {
      e.preventDefault();
    }
  })
});
