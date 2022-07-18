<div class="card_wrapper">
  @foreach ($items as $item)
    <div class="card">
      @if ($item->gambar)
        <img src="{{ asset('storage/item/' . $item->gambar) }}" class="img-fluid img_card">
      @else
        <img src="{{ asset('images/default_produk.png') }}" class="img-fluid img_card">
      @endif
      <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" value="{{ $item->id }}" name="id">
          <input type="hidden" value="{{ $item->nama }}" name="nama">
          <input type="hidden" value="{{ $item->harga_satuan - ($item->harga_satuan * $customer->linkCustomerType->diskon) / 100 }}" name="harga_satuan">
          <h1 class="fs-6 fw-bold">{{ $item->nama }}</h1>

          <h2 class="fs-6 mb-0 text-decoration-line-through">
            Rp. {{ number_format($item->harga_satuan, 0, '', '.') }}
          </h2>
          <h2 class="mb-0 fs-6 text-danger">
            Rp.
            {{ number_format(
                $item->harga_satuan - ($item->harga_satuan * $customer->linkCustomerType->diskon) / 100,
                0,
                '',
                '.',
            ) }}
            /
            {{ $item->satuan }}
          </h2>
          @php
            $cartItem = \Cart::session(auth()->user()->id . 'customerOrder')->get($item->id) ?? null;
          @endphp
          <div class="d-flex justify-content-between mt-2">
            <input type="button" value="-" class="minus-button btn btn-sm btn-primary d-inline me-2"
              for="quantity">
            <input type="number" class="form-control" id="quantity" name="quantity" min="0"
              value="{{ $cartItem->quantity ?? null }}">
            <input type="button" value="+" class="plus-button btn btn-sm btn-primary d-inline ms-2"
              for="quantity">
          </div>
        </form>
      </div>
    </div>
  @endforeach
</div>
