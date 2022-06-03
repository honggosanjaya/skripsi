import { getJSON } from 'jquery';

var filtered = false;

$(document).on('click', '.submit-filter-produk', function () {
  filter()
});

$(document).on('change', 'input[name=quantity]', function () {
  var form = $(this).parent("form").serialize()
  setTimeout(function () {
    $.ajax({
      url: window.location.origin + "/api/customer/order/cart?route=customerOrder",
      method: "POST",
      data: form,
      success: function (data) {
        if (data.status = 'success') {
          if(data.quantityCart!=null&&data.quantityCart!=0){
            $('.cart-quantity').text(data.quantityCart).removeClass( "d-none" )
          }else{
            $('.cart-quantity').text(0).addClass( "d-none" )
          }
          if (filtered == true) {
            filter()
          }
        }
      },
    });
  }, 1500);
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
