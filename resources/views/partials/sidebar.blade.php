<input type="checkbox" id="nav-toggle" />

<div class="sidebar">
  <div class="sidebar-brand">
    <h3 class="mb-0 fw-bold text-white">LOGO</h3>
    <label for="nav-toggle">
      <i class="bi bi-list hamburger_icon"></i>
    </label>
  </div>

  <div class="sidebar-menu">
    <h1 class="mb-3 fs-6">Dashboard</h1>
    <ul class="p-0">
      <li class="mb-3">
        <a class="{{ Request::is('dashboard') ? 'active' : '' }}"
          href="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}">
          <i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span>
        </a>
      </li>
    </ul>

    @can('administrasi')
      <hr class="my-4" />
      <h1 class="mb-3 fs-6">Administrasi</h1>
      <ul class="p-0">
        <li class="mb-3">
          <a class="{{ Request::is('administrasi/datacustomer*') ? 'active' : '' }}" href="/administrasi/datacustomer">
            <i class="bi bi-people-fill me-2"></i><span>Data Customer</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('dashboard/pesanan*') ? 'active' : '' }}" href="/dashboard/pesanan">
            <i class="bi bi-card-list me-2"></i><span>Pesanan</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('dashboard/retur*') ? 'active' : '' }}" href="/dashboard/retur">
            <i class="bi bi-arrow-return-left me-2"></i><span>Retur</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-cash me-2"></i><span>Penjualan</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('administrasi/stok*') ? 'active' : '' }}" href="/administrasi/stok">
            <i class="bi bi-box me-2"></i><span>Stok</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-truck me-2"></i><span>Kendaraan</span>
          </a>
        </li>
      </ul>
    @endcan

    @can('supervisor')
      <hr class="my-4" />
      <h1 class="mb-3 fs-6">Supervisor</h1>
      <ul class="p-0">
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-calendar3-event me-2"></i><span id="testing">Event</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('dashboard/pengguna*') ? 'active' : '' }}" href="/dashboard/pengguna">
            <i class="bi bi-people-fill me-2"></i><span>Data Staf</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-cash me-2"></i><span>Penjualan</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-graph-up me-2"></i><span>Kinerja Salesman</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-wallet2 me-2"></i><span>Limit Pembelian</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-geo-alt me-2"></i><span>Wilayah</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('supervisor/jenis*') ? 'active' : '' }}" href="/supervisor/jenis">
            <i class="bi bi-person-check me-2"></i><span>Jenis Customer</span>
          </a>
        </li>
      </ul>
    @endcan

    @can('owner')
      <hr class="my-4" />
      <h1 class="mb-3 fs-6">Owner</h1>
      <ul class="p-0">
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-cash me-2"></i><span>Penjualan</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-graph-up me-2"></i><span>Kinerja Salesman</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="" href="">
            <i class="bi bi-people-fill me-2"></i><span>Data Supervisor</span>
          </a>
        </li>
      </ul>
    @endcan

  </div>
</div>
