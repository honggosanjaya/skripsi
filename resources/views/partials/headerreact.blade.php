<header class='header_mobile d-flex justify-content-between align-items-center'>
  @if ($isDashboard ?? null)
    <h1 class='logo text-white'>salesMan</h1>
    <div class='d-flex align-items-center'>
      @if ($isSalesman ?? null)
        <a href="/salesman/history" class="me-3">
          <span class="iconify fs-1 text-white" data-icon="ic:round-history"></span>
        </a>
      @endif

      <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
        aria-expanded="false">
        <div class="sales-wrapper d-flex justify-content-between align-items-center">
          @if (auth()->user()->linkStaff->foto_profil ?? null)
            <img src="{{ asset('storage/staff/' . auth()->user()->linkStaff->foto_profil) }}"
              class="profile_picture me-2">
          @else
            <img src="{{ asset('images/default_fotoprofil.png') }}" class="profile_picture me-2">
          @endif
        </div>
      </a>

      <ul class="dropdown-menu p-2" aria-labelledby="navbarDropdown">
        <div class="d-flex flex-column align-items-center justify-content-center mb-2">
          <span class="fw-bold">
            {{ auth()->user()->linkStaff->nama ?? null }}
          </span>
          <small>{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}</small>
        </div>

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
  @else
    <div class='d-flex align-items-center'>
      @if (session('role') == 'shipper')
        <a href="/shipper" class="btn_redirect_back"><span class="iconify text-white"
            data-icon="eva:arrow-back-fill"></span></a>
      @else
        <a href="{{ URL::previous() }}" class="btn_redirect_back"><span class="iconify text-white"
            data-icon="eva:arrow-back-fill"></span></a>
      @endif
      <h1 class='page_title text-white ms-2'>{{ $page ?? 'Halaman' }}</h1>
    </div>

    @if ($idOrder ?? null)
      <a href="#" class="btn">
        <span class="iconify text-white" data-icon="clarity:shopping-cart-solid"></span>
        {{-- <span class='text-white fw-bold'>{jumlahProdukKeranjang}</span> --}}
      </a>
    @endif
  @endif
</header>
