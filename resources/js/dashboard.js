// CommonJS
const Swal = require('sweetalert2')
$(document).ready(function () {
  $(document).on('click', '.simpan_btn', function (e) {
    e.preventDefault();
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
  })
});

$(document).ready(function () {
  $(document).on('click', '#event-form .delete_event', function (e) {
    e.preventDefault();
    Swal.fire({
      title: 'Apakah anda yakin untuk menghapus data ?',
      showDenyButton: true,
      confirmButtonText: 'Ya',
      denyButtonText: `Tidak`,
    }).then((result) => {
      if (result.isConfirmed) {
        console.log('testing');
        window.location.href = $('#event-form .delete_event').attr('href');
      } else if (result.isDenied) {
        Swal.fire('Changes are not saved', '', 'info');
      }
    })
  })
});

function myFunction(x) {
  if (x.matches) { // If media query matches
    document.getElementById("nav-toggle").checked = true;
  }
}

var x = window.matchMedia("(max-width: 1000px)")
myFunction(x)
x.addListener(myFunction)


$(document).ready(function () {
  $(document).on('click', '.sidebar .menu-group', function (e) {
    $(this).toggleClass("showMenu");
  })

  $(document).on('click', '#nav-toggle', function (e) {
    if ($('#nav-toggle').is(":checked")) {
      $('.sidebar .menu-group').removeClass("showMenu");
    }
  })
});
