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
        <a class="{{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
          <i class="bi bi-calendar3-event me-2"></i><span>Dashboard</span>
        </a>
      </li>
    </ul>

    <hr class="my-4" />


    <h1 class="mb-3 fs-6">Admin</h1>
    <ul class="p-0">
      <li class="mb-3">
        <a class="{{ Request::is('dashboard/pesanan/*') ? 'active' : '' }}" href="/dashboard/pesanan">
          <i class="bi bi-calendar3-event me-2"></i><span>Pesanan</span>
        </a>
      </li>
      <li class="mb-3">
        <a class="{{ Request::is('dashboard/retur/*') ? 'active' : '' }}" href="/dashboard/retur">
          <i class="bi bi-calendar3-event me-2"></i><span>Retur</span>
        </a>
      </li>
      <li class="mb-3">
        <a class="{{ Request::is('dashboard/produk/*') ? 'active' : '' }}" href="/dashboard/produk">
          <i class="bi bi-calendar3-event me-2"></i><span>Produk</span>
        </a>
      </li>
    </ul>

    <hr class="my-4" />


    <h1 class="mb-3 fs-6">Supervisor</h1>
    <ul class="p-0">
      <li class="mb-3">
        <a class="{{ Request::is('dashboard/pengguna/*') ? 'active' : '' }}" href="/dashboard/pengguna">
          <i class="bi bi-calendar3-event me-2"></i><span>Atur Pengguna</span>
        </a>
      </li>
    </ul>
  </div>
</div>
