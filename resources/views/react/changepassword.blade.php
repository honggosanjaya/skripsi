@extends('layouts.mainreact')

@push('JS')
  <script>
    $(".btn_password_lama").on("click", function() {
      const idStaff = $('input[name="idStaff"]').val();
      const oldPassword = $('input[name="password_lama"]').val();
      $('.loader').removeClass('d-none');

      $.ajax({
        url: window.location.origin + `/api/checkpassword/${idStaff}`,
        method: "POST",
        data: {
          'old_password': oldPassword,
        },
        success: function(response) {
          $('.loader').addClass('d-none');
          if (response.status == 'success') {
            $('.show_first').addClass('d-none');
            $('.show_whenvalid').removeClass('d-none');
          } else {
            Swal.fire({
              icon: 'error',
              title: 'gagal...',
              text: 'password minimal 8 karakter',
              timer: 1500,
              showConfirmButton: false
            });
          }
        },
        error: function(error) {
          $('.loader').addClass('d-none');
          Swal.fire({
            icon: 'error',
            title: 'gagal...',
            text: 'password lama tidak sesuai',
            timer: 1500,
            showConfirmButton: false
          });
        }
      })
    });

    $(".btn_password_baru").on("click", function() {
      const idStaff = $('input[name="idStaff"]').val();
      const newPassword = $('input[name="password_baru"]').val();
      const confirmNewPassword = $('input[name="konfirmasi_password_baru"]').val();
      $('.loader').removeClass('d-none');

      $.ajax({
        url: window.location.origin + `/api/changepassword/${idStaff}`,
        method: "POST",
        data: {
          'new_password': newPassword,
          'confirm_newpassword': confirmNewPassword,
        },
        success: function(response) {
          $('.loader').addClass('d-none');
          if (response.status == 'success') {
            Swal.fire({
              icon: 'success',
              title: 'berhasil...',
              text: 'Password Berhasil Diubah',
              timer: 1500,
              showConfirmButton: false
            });
          } else {
            console.log('ok');
            Swal.fire({
              icon: 'error',
              title: 'gagal...',
              text: 'konfirmasi password baru tidak sama',
              timer: 1500,
              showConfirmButton: false
            });
          }
        },
        error: function(error) {
          $('.loader').addClass('d-none');
          Swal.fire({
            icon: 'error',
            title: 'Error',
            timer: 1500,
            showConfirmButton: false
          });
        }
      })
    });
  </script>
@endpush


@section('main_content')
  <div class="page_container pt-4">
    <div class="loader d-none"></div>

    <div class="show_first">
      <input type="hidden" value="{{ auth()->user()->id_users }}" name="idStaff">
      <div class="mb-3">
        <label class="form-label">Password Lama</label>
        <input type="password" class="form-control" name="password_lama" placeholder="masukkan password lama">
      </div>

      <button class="btn btn-primary w-100 btn_password_lama mt-5">Submit</button>
    </div>

    <div class="show_whenvalid d-none">
      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" class="form-control" name="password_baru" placeholder="masukkan password baru">
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" class="form-control" name="konfirmasi_password_baru"
          placeholder="masukkan konfirmasi password baru">
      </div>

      <button class="btn btn-primary w-100 btn_password_baru mt-5">Submit</button>
    </div>

  </div>
@endsection
