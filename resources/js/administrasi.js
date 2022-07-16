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

$(document).on('change', '#pengadaan input[name=total_harga]', function () {
  let iditem = $(this).data('iditem')
  $('#pengadaan .submit-cart-' + iditem).removeClass('btn-success')
  $('#pengadaan .submit-cart-' + iditem).removeAttr('disabled')
  $('#pengadaan .submit-cart-' + iditem).addClass('btn-primary')
});

$(document).on('change', '#pengadaan input[name=quantity]', function () {
  let iditem = $(this).data('iditem')
  $('#pengadaan .submit-cart-' + iditem).removeClass('btn-success')
  $('#pengadaan .submit-cart-' + iditem).removeAttr('disabled')
  $('#pengadaan .submit-cart-' + iditem).addClass('btn-primary')
});

$(document).on('change', '#opname input[name=jumlah]', function () {
  let iditem = $(this).data('iditem')
  $('#opname .submit-cart-' + iditem).removeClass('btn-success')
  $('#opname .submit-cart-' + iditem).removeAttr('disabled')
  $('#opname .submit-cart-' + iditem).addClass('btn-primary')
});

$(document).on('change', '#opname input[name=keterangan]', function () {
  let iditem = $(this).data('iditem')
  $('#opname .submit-cart-' + iditem).removeClass('btn-success')
  $('#opname .submit-cart-' + iditem).removeAttr('disabled')
  $('#opname .submit-cart-' + iditem).addClass('btn-primary')
});

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
        console.log('testing');
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
      console.log('testing');
      $('#form_submit').submit();

    } else if (result.isDenied) {
      Swal.fire('Changes are not saved', '', 'info');
    }
  })
});

if ($('.status-track').length > 0) {
  let statustrack = $('.status-track').data('status') + 1
  for (let index = 19; index <= statustrack; index++) {
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
