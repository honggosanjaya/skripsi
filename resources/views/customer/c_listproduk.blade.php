<div class="row">
    @foreach($items as $item)
      <div class="col-6 my-2">
          <div class="card" style="width: 10rem;">
              <img src="..." class="card-img-top" width="100px" height="100px">
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" value="{{ $item->id }}" name="id">
                  <input type="hidden" value="{{ $item->nama }}" name="nama">
                  <input type="hidden" value="{{ $item->harga_satuan }}" name="harga_satuan">
                  <h5 class="card-title">{{ $item->nama }}</h5>
                  <h5 class="card-title">{{ $item->harga_satuan }}/{{ $item->satuan }}</h5>
                  @php
                    $cartItem = \Cart::session(auth()->user()->id.'customerOrder')->get($item->id) ?? null
                  @endphp
                    <input type="number" class="form-control" id="quantity" name="quantity" min="0"
                      value="{{ $cartItem->quantity??null }}">
                </form>
              </div>
            </div>
      </div>
    @endforeach
</div>