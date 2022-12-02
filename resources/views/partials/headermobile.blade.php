<header class="header-mobile">
  <div class="d-flex align-items-center">
    <span class="hamburger p-2 me-3">
      <i class="bi bi-list hamburger_icon"></i>
    </span>
    <div class="breadcrumbs-container">
      @yield('breadcrumbs')
    </div>
  </div>

  <div class="d-flex justify-content-end">
    @if (!empty($datadua['lihat_notif']))
      @php
        $jmlh_notif = ($datadua['jml_trip'] ?? 0) + ($datadua['jml_order'] ?? 0) + ($datadua['jml_retur'] ?? 0) + ($datadua['jml_pengajuan_limit'] ?? 0) + ($datadua['jml_reimbursement'] ?? 0) + ($datadua['jml_pajak'] ?? 0) + ($datadua['jml_jatuhTempo'] ?? 0);
      @endphp

      <div class="alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3 mt-2"></i>
        @if (($jmlh_notif ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $jmlh_notif }}</div>
        @endif
      </div>
    @endif

    <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
      aria-expanded="false">
      <div class="admin-wrapper">
        @if (auth()->user()->linkStaff->foto_profil ?? null)
          <img src="{{ asset('storage/staff/' . auth()->user()->linkStaff->foto_profil) }}"
            class="profile_picture d-block mx-auto">
        @else
          <img src="{{ asset('images/default_fotoprofil.png') }}" class="profile_picture d-block mx-auto">
        @endif
        <div class="active_indicator"></div>
      </div>
    </a>

    <ul class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
      <div class="admin-name fw-bold text-center">
        {{ auth()->user()->linkStaff->nama ?? null }}
      </div>
      <small class="d-block text-center">{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}</small>

      <a class="btn btn-success w-100 my-3 p-1"
        href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/profil">
        <i class="bi bi-person fs-5 me-1"></i> Profil
      </a>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-danger d-block w-100"><span class="iconify fs-4 me-1"
            data-icon="ic:round-logout"></span>Log Out</button>
      </form>
    </ul>
  </div>
</header>
