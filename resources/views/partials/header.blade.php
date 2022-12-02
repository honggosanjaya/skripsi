<header>
  <div class="breadcrumbs-container">
    @yield('breadcrumbs')
  </div>

  <div class="d-flex justify-content-end align-items-center">
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

    @if (auth()->user()->linkStaff->linkStaffRole->nama == 'supervisor')
      @if (!empty($datadua['lihat_notif_spv']))
        @php
          $jmlh_notif_spv = ($datadua['jml_pengajuan'] ?? 0) + ($datadua['juml_opname'] ?? 0);
        @endphp

        <div class="alert_notif d-flex justify-content-center align-items-center flex-column me-3">
          <i class="bi bi-bell-fill fs-3 mt-2"></i>
          @if (($jmlh_notif_spv ?? 0) > 0)
            <div class="hasnotif_indicator">{{ $jmlh_notif_spv }}</div>
          @endif
        </div>
      @endif
    @endif

    <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
      aria-expanded="false">
      <div class="admin-wrapper d-flex justify-content-between align-items-center">
        @if (auth()->user()->linkStaff->foto_profil ?? null)
          <img src="{{ asset('storage/staff/' . auth()->user()->linkStaff->foto_profil) }}"
            class="profile_picture me-2">
        @else
          <img src="{{ asset('images/default_fotoprofil.png') }}" class="profile_picture me-2">
        @endif

        <div class="active_indicator"></div>
        <div class="ms-2">
          <div class="admin-name fw-bold">
            {{ auth()->user()->linkStaff->nama ?? null }}
          </div>
          <small>{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}</small>
        </div>
      </div>
    </a>

    <ul class="dropdown-menu p-3" aria-labelledby="navbarDropdown">
      <a class="btn btn-success w-100 mb-3 p-1"
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
