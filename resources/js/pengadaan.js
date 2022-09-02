// $(document).on('click', '#pengadaan .btn-submit-pengadaan', function (e) {
//   e.preventDefault();
//   Swal.fire({
//     title: 'Apakah anda yakin untuk menyimpan data ?',
//     showDenyButton: true,
//     confirmButtonText: 'Ya',
//     denyButtonText: `Tidak`,
//   }).then((result) => {
//     if (result.isConfirmed) {
//       $('#pengadaan .form-pengadaan').submit();
//     } else if (result.isDenied) {
//       Swal.fire('Aksi dibatalkan', '', 'info');
//     }
//   })
// })

function pengadaan() {
  let iditem = $(this).data('iditem');
  // console.log('hao1');
  if ($(`#pengadaan .input-totalhargacart-${iditem}`).val() > 0 && $(`#pengadaan .input-quantitycart-${iditem}`).val() > 0) {
    // console.log('haoo2');
    let objData = {
      id: $("#pengadaan .input-idcart-" + iditem).val(),
      kode_barang: $('#pengadaan .input-kodecart-' + iditem).val(),
      nama: $('#pengadaan .input-namacart-' + iditem).val(),
      satuan: $('#pengadaan .input-satuancart-' + iditem).val(),
      max_pengadaan: $('#pengadaan .input-maxpengadaancart-' + iditem).val(),
      harga_satuan: $('#pengadaan .input-hargasatuancart-' + iditem).val(),
      total_harga: $('#pengadaan .input-totalhargacart-' + iditem).val(),
      quantity: $('#pengadaan .input-quantitycart-' + iditem).val(),
    }

    $.ajax({
      url: window.location.origin + `/api/administrasi/stok/pengadaan/cart?route=pengadaan`,
      method: "POST",
      data: objData,
      success: function (response) {
        if (response.status == 'success') {
          $('#pengadaan').prepend(`<div id="hideMeAfter3Seconds">
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            ${response.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>`);
        }
      },
    });
  }
}

$(document).off().on('change', '#pengadaan input[name=total_harga]', pengadaan)

$(document).on('change', '#pengadaan input[name=quantity]', pengadaan)

// $(document).on('click', '#data-pengadaan .btn-sm', function (e) {
//   e.preventDefault();
//   let iditem = $(this).data('iditem');
//   $.ajax({
//     url: window.location.origin + `/api/administrasi/stok/pengadaan/remove?route=pengadaan`,
//     method: "POST",
//     data: {
//       id: iditem
//     },
//     success: function (response) {
//       if (response.status == 'success') {
//         location.reload();
//       }
//     },
//   });
// })