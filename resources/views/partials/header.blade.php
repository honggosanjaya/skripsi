<header>
  <div class="breadcrumbs-container">
    @yield('breadcrumbs')
  </div>

  @if (!empty($datadua['lihat_notif']))
    <div class="alert_trip d-flex justify-content-center align-items-center flex-column me-3">
      <i class="bi bi-bell-fill fs-3"></i>
      <p class="mb-0 fw-bold">Trip</p>
    </div>

    <div class="alert_order d-flex justify-content-center align-items-center flex-column me-3">
      <i class="bi bi-bell-fill fs-3"></i>
      <p class="mb-0 fw-bold">Pesanan</p>
    </div>

    <div class="alert_retur d-flex justify-content-center align-items-center flex-column me-3">
      <i class="bi bi-bell-fill fs-3"></i>
      <p class="mb-0 fw-bold">Retur</p>
    </div>
  @endif

  @if (!empty($datadua['lihat_notif_spv']))
    <div class="alert_limit d-flex justify-content-center align-items-center flex-column me-3">
      <i class="bi bi-bell-fill fs-3"></i>
      <p class="mb-0 fw-bold">Limit Pembelian</p>
    </div>
  @endif

  <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
    aria-expanded="false">
    <div class="admin-wrapper">
      @if (auth()->user()->linkStaff->foto_profil)
        <img src="{{ asset('storage/staff/' . auth()->user()->linkStaff->foto_profil) }}"
          class="profile_picture me-2">
      @else
        <img src="{{ asset('images/default_fotoprofil.png') }}" class="profile_picture me-2">
      @endif

      <div class="active_indicator"></div>
      <div class="ms-2">
        <div class="admin-name fw-bold">
          {{ auth()->user()->linkStaff->nama }}
        </div>
        <small>{{ auth()->user()->linkStaff->linkStaffRole->nama }}</small>
      </div>
    </div>
  </a>
  <ul class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
    <a class="btn btn-success w-100 mb-3 p-1" href="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}/profil">
      <i class="bi bi-person fs-5 me-1"></i> Profil
    </a>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="btn btn-danger d-block w-100"><span class="iconify fs-4 me-1"
          data-icon="ic:round-logout"></span>Log Out</button>
    </form>

    {{-- <a href="/dashboard/profil/ubahpasswordlama/{{ auth()->user()->id }}" class="btn btn-primary d-block w-100 mt-3">Ubah Password</a>
    <a href="/dashboard/profil/ubah/{{ auth()->user()->id }}" class="btn btn-warning d-block w-100 mt-3">Ubah Profil</a> --}}
  </ul>
</header>
