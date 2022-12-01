@extends('layouts/main')
@section('breadcrumbs')

  @if (auth()->user()->linkStaff->linkStaffRole->nama ?? null)
    @if (auth()->user()->linkStaff->linkStaffRole->nama == 'administrasi')
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/tripsales">Trip
            Sales</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Cek Koordinat</li>
      </ol>
    @else
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item"><a
            href="/{{ auth()->user()->linkStaff->linkStaffRole->nama ?? null }}/report/koordinattrip">Koordinat Trip</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Cek Koordinat</li>
      </ol>
    @endif
  @endif


@endsection
@section('main_content')
  <div id="cek-koordinat" class="pt-4 px-4">
    <h1 class="fs-4 mb-4">Perbandingan Koordinat Lokasi Customer dan Sales</h1>
    <div id="map"></div>

    @if ($trip->koordinat == $trip->linkCustomer->koordinat)
      <small class="text-success text-center d-block">Lokasi customer dan kunjungan sales sama</small>
    @endif

    <hr class="my-4">
    <h1 class="fs-4">Detail Trip</h1>
    <div class="informasi-list">
      <span><b>Nama sales</b> {{ $trip->linkStaff->nama ?? null }}</span>
      <span><b>Nama customer</b> {{ $trip->linkCUstomer->nama ?? null }}</span>
      @if ($trip->waktu_masuk ?? null)
        <span><b>Waktu masuk</b>
          {{ date('j F Y, g:i a', strtotime($trip->waktu_masuk)) }}
        </span>
      @endif
      @if ($trip->waktu_keluar ?? null)
        <span><b>Waktu keluar</b>
          {{ date('j F Y, g:i a', strtotime($trip->waktu_keluar)) }}
        </span>
      @endif
      @if ($trip->status_enum ?? null)
        <span><b>Status</b>{{ $trip->status_enum == '1' ? ' Trip' : ' Effective Call' }}</span>
        @if ($trip->status_enum == '1')
          <span><b>Alasan penolakan</b> {{ $trip->alasan_penolakan ?? null }}</span>
        @endif
      @endif
    </div>


    @push('JS')
      <script>
        var map;
        const sites = {!! json_encode($datas) !!};
        if (sites.koordinatCustomer == null) {
          sites.koordinatCustomer = '0@0';
        }
        const [latitudeCustomer, longitudeCustomer] = sites.koordinatCustomer.split('@');
        const [latitudeTrip, longitudeTrip] = sites.koordinatTrip.split('@');

        function initMap() {
          if (sites.koordinatCustomer != '0@0') {
            const center = {
              lat: Number(latitudeCustomer),
              lng: Number(longitudeCustomer)
            };

            const options = {
              zoom: 15,
              scaleControl: true,
              center: center
            };

            map = new google.maps.Map(document.getElementById('map'), options);
          } else {
            const center = {
              lat: Number(latitudeTrip),
              lng: Number(longitudeTrip)
            };

            const options = {
              zoom: 15,
              scaleControl: true,
              center: center
            };

            map = new google.maps.Map(document.getElementById('map'), options);
          }


          if (latitudeCustomer == latitudeTrip && longitudeCustomer == longitudeTrip) {
            const customersales = {
              lat: Number(latitudeCustomer),
              lng: Number(longitudeCustomer)
            };

            var mk1 = new google.maps.Marker({
              position: customersales,
              map: map,
              title: 'Lokasi Customer dan Sales',
              label: {
                text: "Lokasi Customer dan Sales",
                color: "white",
                fontWeight: "bold",
                fontSize: "16px"
              }
            });
          } else {
            if (sites.koordinatCustomer != '0@0') {
              const customer = {
                lat: Number(latitudeCustomer),
                lng: Number(longitudeCustomer)
              };

              var mk1 = new google.maps.Marker({
                position: customer,
                map: map,
                title: 'Lokasi Customer',
                label: {
                  text: "Lokasi Customer",
                  color: "white",
                  fontWeight: "bold",
                  fontSize: "16px"
                }
              });
            }

            if (sites.koordinatTrip != '0@0') {
              const sales = {
                lat: Number(latitudeTrip),
                lng: Number(longitudeTrip)
              };

              var mk2 = new google.maps.Marker({
                position: sales,
                map: map,
                title: 'Lokasi Sales',
                label: {
                  text: "Lokasi Sales",
                  color: "white",
                  fontWeight: "bold",
                  fontSize: "16px"
                }
              });
            }
          }
        }
      </script>

      <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap">
      </script>
    @endpush
  @endsection
