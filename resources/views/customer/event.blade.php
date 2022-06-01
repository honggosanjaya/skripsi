@extends('customer.layouts.customerLayouts')

@section('content')
    <div class="row">
      <div class="col-8">
        <div class="mt-3 search-box">
          <form method="GET" action="/customer/event/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Event..."
                value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>

            </div>

          </form>
        </div>
      </div>
      
    </div>
    
    <div class="container">
        @foreach($events as $event)
        <div class="row border border-2 my-3">
            <h5>{{ $event->nama }}</h5>
            @if($event->diskon != null)
            <h6>Promo Diskon {{ $event->diskon }} %</h6>
            @else
            <h6>Promo Potongan Rp {{ $event->potongan }}</h6>
            @endif
            <h6>{{ date('d F Y', strtotime($event->date_end)) }}</h6>
            {{-- <p class="text-primary fw-bold"><a data-bs-toggle="modal" data-bs-target="#testing">Lihat Detail >>></a></p>             --}}
            <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#event{{ $event->id }}">
            Lihat Detail >>>
          </button>

          <!-- Modal -->
          <div class="modal fade" id="event{{ $event->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">{{ $event->nama }}</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="text-center mb-4">
                    <img src="{{ asset('storage/event/'.$event->gambar) }}" class="img-preview img-fluid"
                    width="350px" height="350px" alt="Gambar tidak tersedia">
                  </div>                  
                  
                  @if($event->diskon != null)
                  <h6>Promo Diskon {{ $event->diskon }} %</h6>
                  @else
                  <h6>Promo Potongan Rp {{ $event->potongan }}</h6>
                  @endif
                  <p>{{ $event->keterangan }}</p>
                  <h6> Berlaku sampai {{ date('d F Y', strtotime($event->date_end)) }}</h6>
                  <p> NB: Tanyakan pada sales, saat sales datang untuk info lebih lanjut</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach

    </div>

    {{ $events->links() }}
@endsection
