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
          @if ($customer->tipe_harga == '3' && $item->harga3_satuan)
            <input type="hidden" value="{{ $item->harga3_satuan }}" name="harga_satuan">
          @elseif($customer->tipe_harga == '2' && $item->harga2_satuan)
            <input type="hidden" value="{{ $item->harga2_satuan }}" name="harga_satuan">
          @else
            <input type="hidden" value="{{ $item->harga1_satuan }}" name="harga_satuan">
          @endif
          <h1 class="fs-6 fw-bold">{{ $item->nama }}</h1>

          <h2 class="fs-6 mb-0 text-decoration-line-through">
            Rp.
            {{ $customer->tipe_harga == '3' && $item->harga3_satuan
                ? number_format($item->harga3_satuan, 0, '', '.')
                : ($customer->tipe_harga == '2' && $item->harga2_satuan
                    ? number_format($item->harga2_satuan, 0, '', '.')
                    : number_format($item->harga1_satuan, 0, '', '.')) }}
          </h2>
          <h2 class="mb-0 fs-6 text-danger">
            Rp.
            {{ $customer->tipe_harga == '3' && $item->harga3_satuan
                ? number_format(
                    $item->harga3_satuan - ($item->harga3_satuan * $customer->linkCustomerType->diskon) / 100,
                    0,
                    '',
                    '.',
                )
                : ($customer->tipe_harga == '2' && $item->harga2_satuan
                    ? number_format(
                        $item->harga2_satuan - ($item->harga2_satuan * $customer->linkCustomerType->diskon) / 100,
                        0,
                        '',
                        '.',
                    )
                    : number_format(
                        $item->harga1_satuan - ($item->harga1_satuan * $customer->linkCustomerType->diskon) / 100,
                        0,
                        '',
                        '.',
                    )) }}
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
