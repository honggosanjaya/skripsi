import { getJSON } from 'jquery';

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