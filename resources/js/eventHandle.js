window.previewImage = function(){
  
  const image = document.querySelector('#foto_profil');
  const imgPreview = document.querySelector('.img-preview');

  imgPreview.style.display = 'block';

  const oFReader = new FileReader();
  oFReader.readAsDataURL(image.files[0]);

  oFReader.onload = function(oFREvent){
      imgPreview.src = oFREvent.target.result;
  }

}