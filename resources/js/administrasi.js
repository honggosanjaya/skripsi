import { getJSON } from 'jquery';
const Swal = require('sweetalert2');

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

// ====================== LP3 ======================
var count = $("#laporan-penagihan .form-group").children().length;
let isHapusSemuaBtnClicked = false;

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
  $('.select-invoice option[value="' + value + '"]').addClass('disabled-option');
  $('#laporan-penagihan .form-input').last().clone().appendTo('#laporan-penagihan .form-group');
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
  $('.select-invoice option[value="' + value + '"]').removeClass('disabled-option');
  $(this).parents('#laporan-penagihan .form-input').remove();
  if (count == 1) {
    $('#laporan-penagihan .form-input').find('.remove-form').addClass('d-none');
  }
})

$(document).on('change', '#laporan-penagihan .select-invoice', function (e) {
  console.log('val', e.target.value);
  $('.select-invoice option').removeClass('disabled-option');
  const elements = document.querySelectorAll('#laporan-penagihan .select-invoice');
  Array.from(elements).forEach((element, index) => {
    $('.select-invoice option[value="' + element.value + '"]').addClass('disabled-option');
  });
})

const districtspenagihan = [];
let isNoInvoice = false;
$(document).on('change', '#laporan-penagihan .select-district', function (e) {
  $('.spinner-border').removeClass('d-none');
  $.ajax({
    url: window.location.origin + `/api/administrasi/selectdistict/${e.target.value}`,
    method: "GET",
    success: function (data) {
      console.log(data);
      $('.spinner-border').addClass('d-none');
      districtspenagihan.push(e.target.value);
      $('.select-invoice option').removeClass('disabled-option');
      $('.select-district option[value="' + e.target.value + '"]').attr("disabled", true);

      if (data.customersInvoice.length == 0) {
        isNoInvoice = true;
      }

      if (data.customersInvoice.length > 0) {
        data.customersInvoice.map((customer) => {
          // countCust++;
          customer.link_order.map((order) => {
            if (order.link_order_track.waktu_sampai != null) {
              $('.select-invoice option[value="' + order.link_invoice.id + '"]').addClass('disabled-option');
              $('#laporan-penagihan .form-input').last().clone().appendTo('#laporan-penagihan .form-group');
              $('#laporan-penagihan .form-input').find('.remove-form').removeClass('d-none');
              $('#laporan-penagihan .form-input').last().find('.select-invoice').val(order.link_invoice.id);

              $('.spinner-border').removeClass('d-none');
              $.ajax({
                url: window.location.origin + `/api/administrasi/detailpenagihan/${order.link_invoice.id}`,
                method: "GET",
                success: function (data) {
                  $('.spinner-border').addClass('d-none');
                  if (data.status == 'success') {
                    $("#laporan-penagihan .select-invoice").each(function () {
                      if ($(this).val() == order.link_invoice.id) {
                        $(this).parentsUntil('.form-input').find('.nama-customer').val(data.data.customer.nama);
                        $(this).closest('.form-input').find('.jumlah-tagihan').val(data.data.tagihan);
                      }
                    });
                  }
                },
              });
            }
          })
        })

        const firstSelect = $('.select-invoice').first().val();
        if (isNoInvoice && firstSelect == null || districtspenagihan.length == 1 && firstSelect == null || isHapusSemuaBtnClicked && firstSelect == null) {
          $('#laporan-penagihan .form-input').first().remove();
        }
        isNoInvoice = false;
      }
    }
  });
});

$(document).on('click', '#laporan-penagihan .delete-all', function (e) {
  isHapusSemuaBtnClicked = true;
  Swal.fire({
    title: 'Apakah anda yakin membatalkan pembuatan LP3 ?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya!'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#laporan-penagihan .form-input').not(':first').remove();
      $('#laporan-penagihan .form-input:first').find('.select-invoice').prop('selectedIndex', 0);
      $('#laporan-penagihan .form-input:first').find('.select-invoice option').removeClass('disabled-option');

      $('#laporan-penagihan .form-input:first').find('.nama-customer').val('pilih invoice dulu');
      $('#laporan-penagihan .form-input:first').find('.jumlah-tagihan').val('pilih invoice dulu');

      $('#laporan-penagihan .select-district').prop('selectedIndex', 0);
      $('#laporan-penagihan .select-district option').removeAttr('disabled');
    }
  })
})
// ====================== END LP3 ======================

//  ====================== RENCANA KUNJUNGAN ======================
var countCust = $("#perencanaan-kunjungan .form-group").children().length;
$(document).on('click', '#perencanaan-kunjungan .add-form', function (e) {
  countCust++;
  let value = $(this).parents('#perencanaan-kunjungan .form-input').find('.select-customer option:selected').val();
  $('.select-customer option[value="' + value + '"]').attr("readonly", true);
  $('.select-customer option[value="' + value + '"]').addClass('disabled-option');
  $('#perencanaan-kunjungan .form-input').last().clone().appendTo('#perencanaan-kunjungan .form-group');
  $('#perencanaan-kunjungan .form-input').find('.remove-form').removeClass('d-none');
  $('#perencanaan-kunjungan .form-input').last().find('.select-customer').val('');
  $('#perencanaan-kunjungan .form-input').last().find('.input-estimasi-nominal').val('');
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

$(document).on('change', '#perencanaan-kunjungan .select-customer', function (e) {
  $('.select-customer option').removeClass('disabled-option');
  const elements = document.querySelectorAll('#perencanaan-kunjungan .select-customer');
  Array.from(elements).forEach((element, index) => {
    $('.select-customer option[value="' + element.value + '"]').addClass('disabled-option');
  });
})

const districts = [];
let isNoCustomer = false;
let isDeleteAll = false;
$(document).on('change', '#perencanaan-kunjungan .select-district', function (e) {
  $('.spinner-border').removeClass('d-none');
  $.ajax({
    url: window.location.origin + `/api/administrasi/selectdistict/${e.target.value}`,
    method: "GET",
    success: function (data) {
      $('.spinner-border').addClass('d-none');
      districts.push(e.target.value);
      $('.select-customer option').removeClass('disabled-option');
      $('.select-district option[value="' + e.target.value + '"]').attr("disabled", true);

      if (data.customers.length == 0) {
        isNoCustomer = true;
      }

      if (data.customers.length > 0) {
        data.customers.map((customer) => {
          countCust++;
          $('.select-customer option[value="' + customer.id + '"]').addClass('disabled-option');
          $('#perencanaan-kunjungan .form-input').last().clone().appendTo('#perencanaan-kunjungan .form-group');
          $('#perencanaan-kunjungan .form-input').find('.remove-form').removeClass('d-none');
          $('#perencanaan-kunjungan .form-input').last().find('.select-customer').val(customer.id);
          if (countCust == 1) {
            $('#perencanaan-kunjungan .form-input').find('.remove-form').addClass('d-none');
          }
        })

        const firstSelect = $('.select-customer').first().val();
        if (isNoCustomer && firstSelect == null || districts.length == 1 && firstSelect == null || isDeleteAll && firstSelect == null) {
          $('#perencanaan-kunjungan .form-input').first().remove();
        }
        isNoCustomer = false;
      }
    }
  });
});

$(document).on('click', '#perencanaan-kunjungan .delete-all', function (e) {
  isDeleteAll = true;
  Swal.fire({
    title: 'Apakah anda yakin membatalkan pembuatan perencanaan kunjungan ?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya!'
  }).then((result) => {
    if (result.isConfirmed) {
      $('#perencanaan-kunjungan .form-input').not(':first').remove();
      $('#perencanaan-kunjungan .form-input:first').find('.select-customer').prop('selectedIndex', 0);
      $('#perencanaan-kunjungan .form-input:first').find('.select-customer option').removeClass('disabled-option');
      $('#perencanaan-kunjungan .select-district').prop('selectedIndex', 0);
      $('#perencanaan-kunjungan .select-district option').removeAttr('disabled');
    }
  })
})
// ====================== END RENCANA KUNJUNGAN ======================

// =========================== PDF ===========================
$("#detail-pesanan-admin .btn-unduh-invoice").click(function () {
  const idOrder = $('#detail-pesanan-admin .btn-unduh-invoice').val();
  $('#myFrame').remove();

  $(`<div class="position-relative"><iframe id="myFrame"
        src="${window.location.origin}/administrasi/pesanan/detail/${idOrder}/cetak-invoice#toolbar=0">
      </iframe> <div class="embed-cover"></div></div>`).appendTo("#detail-pesanan-admin .detail-pesanan-admin_action");

  setTimeout(() => {
    $('#detail-pesanan-admin .btn-print-pdf').removeClass('d-none');
    $('#detail-pesanan-admin .btn-close-pdf').removeClass('d-none');
  }, 3000)
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
  $(this).siblings('.nama-item').toggleClass('d-none');
  $(this).siblings('.select-alt-item').toggleClass('d-none');
  if ($('.nama-item').hasClass("d-none")) {
    $(this).text('Batal');
  } else {
    $(this).text('Ubah');
  }
})

$(document).on('change', '#detail_pesanan-admn .select-alt-item', function (e) {
  $(this).siblings('.change-item-btn').addClass('d-none');
  $(this).siblings('.ok-item-btn').removeClass('d-none');
});


// KANVAS
var countItem = $("#kanvas .form-group").children().length;
$(document).on('click', '#kanvas .add-form', function (e) {
  countItem++;
  $('#kanvas .form-input').last().clone().appendTo('#kanvas .form-group');
  $('#kanvas .form-input').find('.remove-form').removeClass('d-none');
  $('#kanvas .remove-all-form').removeClass('d-none');
  $('#kanvas .form-input').last().find('.select-item').val('');
  $('#kanvas .form-input').last().find('.jumlah_item').val('');
  if (countItem == 1) {
    $('#kanvas .remove-all-form').addClass('d-none');
    $('#kanvas .form-input').find('.remove-form').addClass('d-none');
  }
})

$(document).on('click', '#kanvas .remove-form', function (e) {
  countItem--;
  $(this).parents('#kanvas .form-input').remove();
  if (countItem == 1) {
    $('#kanvas .remove-all-form').addClass('d-none');
    $('#kanvas .form-input').find('.remove-form').addClass('d-none');
  }
})

$(document).on('click', '#kanvas .remove-all-form', function (e) {
  Swal.fire({
    title: 'Apakah anda yakin untuk menghapus seluruh item ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#kanvas .form-input').not(':first').remove();
      $("#kanvas .select-item").val('').change();
      $("#kanvas .jumlah_item").val('');
    } else if (result.isDenied) {
      Swal.fire('Changes are not saved', '', 'info');
    }
  })
})

$(document).on('click', '#kanvas .detail_trigger', function (e) {
  let idkanvas = $(this).data('idkanvas');
  $('#kanvas .table_body').empty();
  $('#kanvas .loading-indicator').removeClass('d-none');
  $.ajax({
    url: window.location.origin + `/api/administrasi/getDetailKanvas/${idkanvas}`,
    method: "GET",
    success: function (response) {
      $('#kanvas .loading-indicator').addClass('d-none');
      response.data.map((dt, index) => {
        let singlerow =
          `
        <tr>
          <td> ${index + 1} </td>
          <td> ${dt[0].link_item.nama} </td>
          <td> ${dt[0].stok_awal} </td>
          <td> ${dt[0].sisa_stok} </td>
        </tr>                  
        `
        $('#kanvas .table_body').append(singlerow);
      })
    },
  });
})

$(document).on('change', '#kanvas .select-history-kanvas', function (e) {
  const namaKanvas = $("#kanvas .select-history-kanvas option:selected").text();
  $('.hidden-nama-kanvas').val(namaKanvas);
  $('#kanvas .loading-indicator').removeClass('d-none');
  $.ajax({
    url: window.location.origin + `/api/administrasi/getDetailKanvas/${e.target.value}`,
    method: "GET",
    success: function (response) {
      console.log(response.data);
      $('#kanvas .loading-indicator').addClass('d-none');
      $('#kanvas .form-input').not(':first').remove();

      for (let i = 0; i < response.data.length - 1; i++) {
        $('#kanvas .form-input').last().clone().appendTo('#kanvas .form-group');
        $('#kanvas .form-input').find('.remove-form').removeClass('d-none');
        $('#kanvas .remove-all-form').removeClass('d-none');
        $('#kanvas .form-input').last().find('.select-item').val('');
        $('#kanvas .form-input').last().find('.jumlah_item').val('');
      }

      response.data.map((dt, index) => {
        $('#kanvas .select-item').eq(index).val(dt[0].link_item.id).change();
        $('#kanvas .jumlah_item').eq(index).val(dt[0].stok_awal);
      })
    },
  });
})

$(document).on('click', '#kanvas .btn-submit', function () {
  const idSales = $('#kanvas #id_staff_yang_membawa').val();

  Swal.fire({
    title: 'Apakah anda yakin untuk menyimpan data ?',
    showDenyButton: true,
    confirmButtonText: 'Ya',
    denyButtonText: `Tidak`,
  }).then((result) => {
    if (result.isConfirmed) {
      $('#kanvas .loading-indicator').removeClass('d-none');
      $.ajax({
        url: window.location.origin + `/api/administrasi/checkSalesHasKanvas/${idSales}`,
        method: "GET",
        success: function (response) {
          if (response.status == 'error') {
            $('#kanvas .loading-indicator').addClass('d-none');
            $("#kanvas").prepend(
              `<div id="hideMeAfter3Seconds">
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                </div>`
            )
          } else if (response.status == 'success') {
            $('#kanvas .loading-indicator').addClass('d-none');
            $('#form_submit').submit();
          }
        },
      });
    } else if (result.isDenied) {
      Swal.fire('Changes are not saved', '', 'info');
    }
  })
});


// === MULTIPLE SELECT ===
let countMultiple = 0;

function addMultipleSelect(idSelector) {
  let value = $(this).parents(`${idSelector} .form-input`).find('.select-multiple option:selected').val();
  $('.select-multiple option[value="' + value + '"]').addClass('disabled-option');
  $(`${idSelector} .form-input`).last().clone().appendTo(`${idSelector} .form-group`);
  $(`${idSelector} .form-input`).find('.remove-form').removeClass('d-none');
  $(`${idSelector} .form-input`).last().find('.select-multiple').val('');
  $(`${idSelector} .form-input`).last().find('input').val('');
}

function changeMultipleSelect(idSelector) {
  $('.select-multiple option').removeClass('disabled-option');
  const elements = document.querySelectorAll(`${idSelector} .select-multiple`);
  Array.from(elements).forEach((element, index) => {
    $('.select-multiple option[value="' + element.value + '"]').addClass('disabled-option');
  });
}

$(document).on('click', '#multiple-select .add-form', function (e) {
  countMultiple++;
  addMultipleSelect('#multiple-select');
  if (countMultiple == 0) {
    $(`${idSelector} .form-input`).find('.remove-form').addClass('d-none');
  }
});

$(document).on('click', '#multiple-select .remove-form', function (e) {
  countMultiple--;
  let value = $(this).parents('#multiple-select .form-input').find('.select-multiple option:selected').val();
  $('.select-multiple option[value="' + value + '"]').removeClass('disabled-option');
  $(this).parents('#multiple-select .form-input').remove();
  if (countMultiple == 0) {
    $('#multiple-select .form-input').find('.remove-form').addClass('d-none');
  }
});

$(document).on('change', '#multiple-select .select-multiple', function (e) {
  changeMultipleSelect('#multiple-select');
});