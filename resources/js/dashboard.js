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

// NOTIFIKASI
$(".dropdown-toggle").click(function () {
  $(".alert_notif").removeClass("active");
  $(".all-notification").addClass("m-fadeOut");
  $(".all-notification").removeClass("m-fadeIn");
});

$(".alert_notif").click(function () {
  $(this).toggleClass("active");
  $(".all-notification").toggleClass("m-fadeIn m-fadeOut");
});

$(".filter-notif").click(function () {
  $(this).addClass("active");
  $(".filter-notif").not(this).removeClass("active");
  $(this).blur();

  let type = $(this).data("notif");
  $(`.${type}_notif`).removeClass('d-none');
  $('.notif').not(`.${type}_notif`).addClass('d-none');
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

$(document).ready(function () {
  $(document).on('click', '.menu-group', function (e) {
    $(this).toggleClass("showMenu");
  })

  $(document).on('click', '#nav-toggle', function (e) {
    if ($('#nav-toggle').is(":checked")) {
      $('.menu-group').removeClass("showMenu");
    }
  })
});

$(document).on('click', '.header-mobile .hamburger', function (e) {
  $(".sidebar-mobile .overlay").css("width", "100%");
});

$(document).on('click', '.sidebar-mobile .closebtn', function (e) {
  $(".sidebar-mobile .overlay").css("width", "0%");
});

$(document).on('click', '.menu-group', function (e) {
  if ($('#nav-toggle').is(":checked")) {
    $('.hamburger_icon').click();
  }
})