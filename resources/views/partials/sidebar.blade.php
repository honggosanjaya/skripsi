<input type="checkbox" id="nav-toggle" />

<div class="sidebar">
  <div class="sidebar-brand">
    <h3 class="mb-0 fw-bold text-white logo">salesMan</h3>
    <label for="nav-toggle">
      <i class="bi bi-list hamburger_icon"></i>
    </label>
  </div>

  <div class="sidebar-menu">
    <h1 class="mb-3 fs-6">Dashboard</h1>
    <ul class="p-0">
      <li class="mb-3">
        <a class="{{ Request::is('administrasi') || Request::is('supervisor') || Request::is('owner') ? 'active' : '' }}"
          href="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}">
          <i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span>
        </a>
      </li>
    </ul>

    @can('administrasi')
      <hr class="my-4" />
      <h1 class="mb-3 fs-6">Administrasi</h1>
      <ul class="p-0 nav-links">
        <li
          class="mb-3 menu-group {{ Request::is('administrasi/kendaraan*') || Request::is('administrasi/datacustomer*') ? 'showMenu' : '' }}">
          <div
            class="icon-link {{ Request::is('administrasi/kendaraan*') || Request::is('administrasi/datacustomer*') ? 'active' : '' }}">
            <a>
              <i class="bi bi-file-earmark-bar-graph me-2"></i>
              <span>Data</span>
            </a>
            <i class="bi bi-chevron-down arrow"></i>
          </div>
          <ul class="sub-menu mt-2">
            <li>
              <a class="{{ Request::is('administrasi/datacustomer*') ? 'active-submenu' : '' }}"
                href="/administrasi/datacustomer">
                <i class="bi bi-people-fill me-2"></i>Customer
              </a>
            </li>
            <li>
              <a class="{{ Request::is('administrasi/kendaraan*') ? 'active-submenu' : '' }}"
                href="/administrasi/kendaraan">
                <i class="bi bi-truck me-2"></i>Kendaraan
              </a>
            </li>
          </ul>
        </li>

        <li
          class="mb-3 menu-group {{ Request::is('administrasi/lp3*') || Request::is('administrasi/rencanakunjungan*') ? 'showMenu' : '' }}">
          <div
            class="icon-link {{ Request::is('administrasi/lp3*') || Request::is('administrasi/rencanakunjungan*') ? 'active' : '' }}">
            <a>
              <i class="bi bi-receipt-cutoff me-2"></i>
              <span>Perencanaan Sales</span>
            </a>
            <i class="bi bi-chevron-down arrow"></i>
          </div>
          <ul class="sub-menu mt-2">
            <li>
              <a class="{{ Request::is('administrasi/lp3*') ? 'active-submenu' : '' }}" href="/administrasi/lp3">
                <i class="bi bi-receipt me-2"></i>LP3
              </a>
            </li>
            <li>
              <a class="{{ Request::is('administrasi/rencanakunjungan*') ? 'active-submenu' : '' }}"
                href="/administrasi/rencanakunjungan">
                <i class="bi bi-person-workspace me-2"></i>Rencana Kunjungan
              </a>
            </li>
          </ul>
        </li>

        <li class="mb-3">
          <a class="{{ Request::is('administrasi/pesanan*') ? 'active' : '' }}" href="/administrasi/pesanan">
            <i class="bi bi-card-list me-2"></i><span>Pesanan</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('administrasi/retur*') ? 'active' : '' }}" href="/administrasi/retur">
            <i class="bi bi-arrow-return-left me-2"></i><span>Retur</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('administrasi/stok*') ? 'active' : '' }}" href="/administrasi/stok">
            <i class="bi bi-box me-2"></i><span>Stok</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('administrasi/reimbursement*') ? 'active' : '' }}" href="/administrasi/reimbursement">
            <i class="bi bi-cash-coin me-2"></i><span>Reimbursement</span>
          </a>
        </li>

        <li class="mb-3">
          <a class="{{ Request::is('administrasi/kas*') ? 'active' : '' }}" href="/administrasi/kas">
            <i class="bi bi-cash-coin me-2"></i><span>Kas</span>
          </a>
        </li>
      </ul>
    @endcan

    @can('supervisor')
      <hr class="my-4" />
      <h1 class="mb-3 fs-6">Supervisor</h1>
      <ul class="p-0 nav-links">
        <li class="mb-3 menu-group">
          <div
            class="icon-link {{ Request::is('supervisor/event*') || Request::is('supervisor/datastaf*') || Request::is('supervisor/datacustomer*') || Request::is('supervisor/wilayah*') || Request::is('supervisor/jenis*') || Request::is('supervisor/cashaccount*') || Request::is('supervisor/category*') || Request::is('supervisor/stokopname*') ? 'active' : '' }}">
            <a>
              <i class="bi bi-file-earmark-bar-graph me-2"></i>
              <span>Data</span>
            </a>
            <i class="bi bi-chevron-down arrow"></i>
          </div>
          <ul class="sub-menu mt-2">
            <li>
              <a class="{{ Request::is('supervisor/event*') ? 'active-submenu' : '' }}" href="/supervisor/event">
                <i class="bi bi-calendar3-event me-2"></i>Event
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/datastaf*') ? 'active-submenu' : '' }}" href="/supervisor/datastaf">
                <i class="bi bi-people-fill me-2"></i>Staf
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/datacustomer*') ? 'active-submenu' : '' }}"
                href="/supervisor/datacustomer">
                <i class="bi bi-wallet2 me-2"></i><span>Customer</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/wilayah*') ? 'active-submenu' : '' }}" href="/supervisor/wilayah">
                <i class="bi bi-geo-alt me-2"></i><span>Wilayah</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/jenis*') ? 'active-submenu' : '' }}" href="/supervisor/jenis">
                <i class="bi bi-person-check me-2"></i><span>Jenis Customer</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/cashaccount*') ? 'active-submenu' : '' }}"
                href="/supervisor/cashaccount">
                <i class="bi bi-cash-coin me-2"></i><span>Cash Account</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/category*') ? 'active-submenu' : '' }}" href="/supervisor/category">
                <i class="bi bi-tags me-2"></i><span>Category Item</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/stokopname*') ? 'active-submenu' : '' }}"
                href="/supervisor/stokopname">
                <i class="bi bi-boxes me-2"></i><span>Stok Opname</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/target*') ? 'active-submenu' : '' }}" href="/supervisor/target">
                <i class="bi bi-bullseye me-2"></i><span>Target</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="mb-3 menu-group">
          <div
            class="icon-link {{ Request::is('supervisor/report/penjualan*') || Request::is('supervisor/report/kinerja*') ? 'active' : '' }}">
            <a>
              <i class="bi bi-newspaper me-2"></i>
              <span>Report</span>
            </a>
            <i class="bi bi-chevron-down arrow"></i>
          </div>
          <ul class="sub-menu mt-2">
            <li>
              <a class="{{ Request::is('supervisor/report/penjualan*') ? 'active-submenu' : '' }}"
                href="/supervisor/report/penjualan">
                <i class="bi bi-cash me-2"></i><span>Penjualan</span>
              </a>
            </li>
            <li>
              <a class="{{ Request::is('supervisor/report/kinerja*') ? 'active-submenu' : '' }}"
                href="/supervisor/report/kinerja">
                <i class="bi bi-graph-up me-2"></i><span>Kinerja Salesman</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    @endcan

    @can('owner')
      <hr class="my-4" />
      <h1 class="mb-3 fs-6">Owner</h1>
      <ul class="p-0 nav-links">
        <li class="mb-3">
          <a class="{{ Request::is('owner/report/penjualan*') ? 'active' : '' }}" href="/owner/report/penjualan">
            <i class="bi bi-cash me-2"></i><span>Penjualan</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('owner/report/kinerja*') ? 'active' : '' }}" href="/owner/report/kinerja">
            <i class="bi bi-graph-up me-2"></i><span>Kinerja Salesman</span>
          </a>
        </li>
        <li class="mb-3">
          <a class="{{ Request::is('owner/datasupervisor*') ? 'active' : '' }}" href="/owner/datasupervisor">
            <i class="bi bi-people-fill me-2"></i><span>Data Supervisor</span>
          </a>
        </li>
      </ul>
    @endcan

  </div>
</div>
