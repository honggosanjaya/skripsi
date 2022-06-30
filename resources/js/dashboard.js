// CommonJS
const Swal = require('sweetalert2')
$(document).ready(function () {
  $(document).on('click', '.simpan_btn', function (e) {
    e.preventDefault();
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
  })
});

$(document).ready(function () {
  $(document).on('click', '#event-form .delete_event', function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Apakah anda yakin untuk menghapus data ?',
      showDenyButton: true,
      confirmButtonText: 'Ya',
      denyButtonText: `Tidak`,
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('testing');
        window.location.href = $('#event-form .delete_event').attr('href');
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info');
      }
    })
  })
});

function myFunction(x) {
  if (x.matches) { // If media query matches
    document.getElementById("nav-toggle").checked = true;
  }
}

var x = window.matchMedia("(max-width: 1000px)")
myFunction(x)
x.addListener(myFunction)

$(document).ready(function () {
  $(document).on('click', '.btn_terimaPesanan', function (e) {
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
});

$(document).ready(function () {
  $(document).on('click', '.btn_tolakPesanan', function (e) {
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
});

$(document).ready(function () {
  $(document).on('click', '.btn_konfirmasikeberangkatan', function (e) {
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
});

$(document).ready(function () {
  $(document).on('click', '.pesanan_selesai', function (e) {
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
});
