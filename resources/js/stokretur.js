function stokretur() {
  let iditem = $(this).data('iditem');
  if ($(`#stokretur .select-metodecart-${iditem}`).val() && $(`#stokretur .input-quantitycart-${iditem}`).val() > 0) {
    let objData = {
      id: $("#stokretur .input-idcart-" + iditem).val(),
      kode_barang: $('#stokretur .input-kodecart-' + iditem).val(),
      nama: $('#stokretur .input-namacart-' + iditem).val(),
      satuan: $('#stokretur .input-satuancart-' + iditem).val(),
      stok: $('#stokretur .input-stokcart-' + iditem).val(),
      stok_retur: $('#stokretur .input-stokreturcart-' + iditem).val(),
      quantity: $('#stokretur .input-quantitycart-' + iditem).val(),
      metode: $('#stokretur .select-metodecart-' + iditem).val(),
    }

    $.ajax({
      url: window.location.origin + `/api/administrasi/stok/stokretur/cart?route=stokretur`,
      method: "POST",
      data: objData,
      success: function (response) {
        if (response.status == 'success') {
          $('#stokretur').prepend(`<div id="hideMeAfter3Seconds">
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

$(document).off().on('change', '#stokretur select[name=metode]', stokretur)

$(document).on('change', '#stokretur input[name=quantity]', stokretur)