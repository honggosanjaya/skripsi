<header class="d-flex justify-content-end">

  <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <div class="admin-wrapper">
      {{-- user profile picture --}}
      <img src="" class="profile_picture me-2">
      {{-- active sign --}}
      <div class="active_indicator"></div>
      <div class="admin-name ms-2">
        <h4 class="mb-0 fs-6">Username Nya</h4>
        <small>Supervisor</small>
      </div>
    </div>
  </a>
  <ul class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
    <button class="btn btn-danger d-block w-100">Log Out</button>
    <a href="/dashboard/profil/ubahpassword" class="btn btn-primary d-block w-100 mt-3">Ubah Password</a>
    <a href="/dashboard/profil/ubah" class="btn btn-warning d-block w-100 mt-3">Ubah Profil</a>
  </ul>

</header>
