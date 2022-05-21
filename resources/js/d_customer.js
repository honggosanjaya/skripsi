import { getJSON } from 'jquery';


$(document).on('click', '.submit-filter-produk', function(){
  var filter=$("input[name=filter]:checked").val();
  var order=$("input[name=order]:checked").val();
  $.getJSON(window.location.origin+'/api/filterProduk', {filter:filter, order:order}, function(data){
    if(data.status='success')
    {
      $("#list-produk").html(data.html);
      $( ".close-filter-produk" ).trigger( "click" );
    }
    else{
      alert('Data not found')
      $( ".close-filter-produk" ).trigger( "click" );
    }
  });
});
