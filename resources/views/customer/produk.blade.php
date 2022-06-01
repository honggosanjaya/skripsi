@extends('customer.layouts.customerLayouts')

@section('content')
    <div class="row">
      <div class="col-8">
        <div class="mt-3 search-box">
          <form method="GET" action="/customer/produk/cari">
            <div class="input-group">
              <input type="text" class="form-control" name="cari" placeholder="Cari Produk..."
                value="{{ request('cari') }}">
              <button type="submit" class="btn btn-primary">Cari</button>

            </div>

          </form>
        </div>
      </div>
      <div class="col-4 mt-2">
        <a href="/filterProduk">

        </a>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="bi bi-funnel-fill fs-3"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filterprice" value="price" checked>
                    <label class="form-check-label" for="filterprice">
                      price
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="filter" id="filternama" value="nama">
                    <label class="form-check-label" for="filternama">
                      nama
                    </label>
                  </div>
                  <hr style="display: block">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="order" id="asc" value="asc" checked>
                    <label class="form-check-label" for="asc">
                      ASC
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="order" id="desc" value="desc">
                    <label class="form-check-label" for="desc">
                      DESC
                    </label>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-filter-produk"
                  data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit-filter-produk">Save changes</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="list-produk">
      @include('customer.c_listproduk')
    </div>
@endsection
