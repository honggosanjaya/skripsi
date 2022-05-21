<div class="row">
    @foreach($items as $item)
    <div class="col-6 my-2">
        <div class="card" style="width: 10rem;">
            <img src="..." class="card-img-top" width="100px" height="100px">
            <div class="card-body">
              <h5 class="card-title">{{ $item->nama }}</h5>
              <h5 class="card-title">{{ $item->harga_satuan }}/{{ $item->satuan }}</h5>
              
              <button class="btn btn-primary btn-square py-1 px-2">-</button>
              <input type="number" class="col-4">
              <button class="btn btn-primary btn-square py-1 px-2">+</button>
            </div>
          </div>
    </div>
    @endforeach
</div>