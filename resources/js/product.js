import { getJSON } from 'jquery';

if ($('.produk-tbl').length) {
  $('.status-btn').click(function () {
    var th = $(this)
    $.getJSON('/dashboard/produk/ubahstatus/' + $(this).data('id'), function (dt) {
      if (dt.status == 'aktif') {
        th.removeClass('btn-success');
        th.addClass('btn-danger');
        th.text('Nonaktifkan produk');
        th.parent().siblings('.status-prd').text(dt.status);
        $('.alert').remove();
      }
      else {
        th.addClass('btn-success');
        th.removeClass('btn-danger');
        th.text('Aktifkan produk');
        th.parent().siblings('.status-prd').text(dt.status);
        $('.alert').remove();
      }

      $('.table-produk').before(`
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        ${dt.successMessage}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      `);

    });
  });
}

