function opname() {
  let iditem = $(this).data('iditem');

  if ($(`#opname .input-jumlahcart-${iditem}`).val() && $(`#opname .input-keterangancart-${iditem}`).val()) {
    let objData = {
      id: $("#opname .input-idcart-" + iditem).val(),
      nama: $('#opname .input-namacart-' + iditem).val(),
      quantity: $('#opname .input-quantitycart-' + iditem).val(),
      kode_barang: $('#opname .input-kodecart-' + iditem).val(),
      harga_satuan: $('#opname .input-hargasatuancart-' + iditem).val(),
      jumlah: $('#opname .input-jumlahcart-' + iditem).val(),
      keterangan: $('#opname .input-keterangancart-' + iditem).val(),
    }

    $('#opname .loading-indicator').removeClass('d-none');
    $.ajax({
      url: window.location.origin + `/api/administrasi/stok/opname/cart?route=opname`,
      method: "POST",
      data: objData,
      success: function (response) {
        $('#opname .loading-indicator').addClass('d-none');
        if (response.status == 'success') {
          const stokBaru = parseInt($('#opname .input-quantitycart-' + iditem).val()) + parseInt($('#opname .input-jumlahcart-' + iditem).val());
          $('#opname .stok-baru-' + iditem).text(stokBaru);

          $('#opname').prepend(`<div id="hideMeAfter3Seconds">
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

$(document).off().on('change', '#opname input[name=jumlah]', opname)
$(document).on('change', '#opname input[name=keterangan]', opname)