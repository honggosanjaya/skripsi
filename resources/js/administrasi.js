import { getJSON } from 'jquery';
window.bootstrap = require('bootstrap');
const Swal = require('sweetalert2')

$(document).on('change', '#pengadaan input[name=total_harga]', function () {
    let iditem=$(this).data('iditem')
    $('#pengadaan .submit-cart-'+iditem).removeClass('btn-success')
    $('#pengadaan .submit-cart-'+iditem).removeAttr('disabled')
    $('#pengadaan .submit-cart-'+iditem).addClass('btn-primary')
});

$(document).on('change', '#pengadaan input[name=quantity]', function () {
    let iditem=$(this).data('iditem')
    $('#pengadaan .submit-cart-'+iditem).removeClass('btn-success')
    $('#pengadaan .submit-cart-'+iditem).removeAttr('disabled')
    $('#pengadaan .submit-cart-'+iditem).addClass('btn-primary')
});

$(document).on('change', '#opname input[name=jumlah]', function () {
    let iditem=$(this).data('iditem')
    $('#opname .submit-cart-'+iditem).removeClass('btn-success')
    $('#opname .submit-cart-'+iditem).removeAttr('disabled')
    $('#opname .submit-cart-'+iditem).addClass('btn-primary')
});

$(document).on('change', '#opname input[name=keterangan]', function () {
    let iditem=$(this).data('iditem')
    $('#opname .submit-cart-'+iditem).removeClass('btn-success')
    $('#opname .submit-cart-'+iditem).removeAttr('disabled')
    $('#opname .submit-cart-'+iditem).addClass('btn-primary')
});

$(document).on('click', '#retur-admin .button-submit', function () {
    if ($('#retur-admin select[name=tipe_retur]').val()==1) {
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        myModal.show()
    }else{
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
if ($('.status-track').length>0) {
    let statustrack=$('.status-track').data('status')+1
    for (let index = 19; index <= statustrack; index++) {
        if(index!=statustrack){
            $('.s-'+index).addClass('completed');

            $('.s-'+index).addClass('completed');
        }else{
            $('.s-'+index).addClass('active');
        }
    }
}