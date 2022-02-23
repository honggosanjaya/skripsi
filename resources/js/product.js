import { getJSON } from 'jquery';
if($('.produk-tbl').length){
    $('.status-btn').click(function() {
        // alert($(this).data('id'));
        var th=$(this)
        $.getJSON('/dashboard/produk/ubahstatus/'+$(this).data('id'), function(dt){
            if(dt.status=='aktif')
            {
                th.removeClass( 'btn-success' )
                th.addClass( 'btn-danger' )
                th.text( 'Nonaktifkan produk' )
                
                th.parent().siblings( ".status-prd" ).text( dt.status )
            }
            else{
                th.addClass( 'btn-success' )
                th.removeClass( 'btn-danger' )
                th.text( 'Aktifkan produk' )
                
                th.parent().siblings( ".status-prd" ).text( dt.status )
            }
        });
    });
}

