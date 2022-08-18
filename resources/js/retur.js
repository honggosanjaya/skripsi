// import { getJSON } from 'jquery';

// const showAlert = (data) => {
//   $('.table-retur').before(`
//   <div class="alert alert-success alert-dismissible fade show" role="alert">
//     ${data.successMessage}
//     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//   </div>
//   `);
// }

// $('.statusRetur-btn').click(function () {
//   let ths = $(this);

//   if ($('.statusRetur-btn').text() == 'Terima') {
//     $.getJSON(`/dashboard/retur/terimaRetur/${$(this).data('id')}`, function (data) {
//       $('.alert').remove();
//       ths.parent().siblings(".status_retur").text('Disetujui');
//       ths.removeClass('btn-success');
//       ths.addClass('btn-danger');
//       ths.text('Tolak');
//       showAlert(data);
//     });
//   } else {
//     $.getJSON(`/dashboard/retur/tolakRetur/${$(this).data('id')}`, function (data) {
//       $('.alert').remove();
//       ths.parent().siblings(".status_retur").text('Ditolak');
//       ths.removeClass('btn-danger');
//       ths.addClass('btn-success');
//       ths.text('Terima');
//       showAlert(data);
//     });
//   }
// });

