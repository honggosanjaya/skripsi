// CommonJS
const Swal = require('sweetalert2')
$(document).ready(function () {
  $('.batalkanAksi_btn').click(function () {
    confirm('apakah anda yakin? perubahan akan terbuang');
  })
});

// Swal.fire({
//     title: 'Error!',
//     text: 'Do you want to continue',
//     icon: 'error',
//     confirmButtonText: 'Cool'
//   })