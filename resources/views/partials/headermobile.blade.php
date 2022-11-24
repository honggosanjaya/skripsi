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
      <div class="alert_trip alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_trip'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_trip'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold">Trip</small>
      </div>

      <div class="alert_order alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_order'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_order'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold">Pesanan</small>
      </div>

      <div class="alert_retur alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_retur'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_retur'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold">Retur</small>
      </div>

      <div class="alert_limit alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_pengajuan_limit'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_pengajuan_limit'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold">Limit</small>
      </div>

      <div class="alert_reimbursement alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_reimbursement'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_reimbursement'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold">Reimburs</small>
      </div>

      <div class="alert_pajak alert_notif d-flex justify-content-center align-items-center flex-column me-3">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_pajak'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_pajak'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold text-center">Pajak<br>Kendaraan</small>
      </div>

      <div class="alert_jatuhtempo alert_notif d-flex justify-content-center align-items-center flex-column me-5">
        <i class="bi bi-bell-fill fs-3"></i>
        @if (($datadua['jml_jatuhTempo'] ?? 0) > 0)
          <div class="hasnotif_indicator">{{ $datadua['jml_jatuhTempo'] }}</div>
        @endif
        <small class="d-block mb-0 fw-bold text-center">Jatuh<br>Tempo</small>
      </div>
    @endif

    @if (auth()->user()->linkStaff->linkStaffRole->nama == 'supervisor')
      @if (!empty($datadua['lihat_notif_spv']))
        <div class="alert_limit alert_notif d-flex justify-content-center align-items-center flex-column me-3">
          <i class="bi bi-bell-fill fs-3"></i>
          @if (($datadua['jml_pengajuan'] ?? 0) > 0)
            <div class="hasnotif_indicator">{{ $datadua['jml_pengajuan'] }}</div>
          @endif
          <small class="d-block mb-0 fw-bold text-center">Limit<br>Pembelian</small>
        </div>
        <div class="alert_opname alert_notif d-flex justify-content-center align-items-center flex-column me-5">
          <i class="bi bi-bell-fill fs-3"></i>
          @if (($datadua['juml_opname'] ?? 0) > 0)
            <div class="hasnotif_indicator">{{ $datadua['juml_opname'] }}</div>
          @endif
          <small class="d-block mb-0 fw-bold text-center">Stok<br>Opname</small>
        </div>
      @endif
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
