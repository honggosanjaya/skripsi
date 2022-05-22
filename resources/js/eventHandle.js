window.previewImage = function(){
  
  const image = document.querySelector('#gambar');
  const imgPreview = document.querySelector('.img-preview');

  imgPreview.style.display = 'block';

  const oFReader = new FileReader();
  oFReader.readAsDataURL(image.files[0]);

  oFReader.onload = function(oFREvent){
      imgPreview.src = oFREvent.target.result;
  }

}

if($( "#event_pilih_isian" ).length){
  if($( "#event_pilih_isian" ).val() === 'potongan'){
    $( "#potongan_diskon" ).attr('max',null)
  }
  else{
    $( "#potongan_diskon" ).attr('max',100)
  }
}

$( "#event_pilih_isian" ).change(function() {
    $( "#potongan_diskon" ).val(0)
    if($( "#event_pilih_isian" ).val() === 'potongan'){
      $( "#potongan_diskon" ).attr('max',null)
    }
    else{
      $( "#potongan_diskon" ).attr('max',100)
    }
    
});
