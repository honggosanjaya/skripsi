@extends('layouts/main')

@section('main_content')
  <div class="limit_notif m-fadeOut p-3">
    @foreach ($customersPengajuanLimit as $customerPengajuanLimit)
      <div class="card_notif">
        <a href="/supervisor/datacustomer/pengajuan/{{ $customerPengajuanLimit->id }}"
          class="text-black text-decoration-none">
          <p class="mb-0 fw-bold">Pengajuan Limit Pembelian</p>
          <p class="mb-0">Pengajuan limit pembeian dari {{ $customerPengajuanLimit->nama }} </p>
        </a>
      </div>
    @endforeach
  </div>


  <script>
    const dropdownLimit = document.querySelector(".alert_limit");
    const notifLimit = document.querySelector(".limit_notif");

    dropdownLimit.addEventListener("click", function() {
      dropdownLimit.classList.toggle('active');
      notifLimit.classList.toggle("m-fadeIn");
      notifLimit.classList.toggle("m-fadeOut");
    });
  </script>
@endsection
