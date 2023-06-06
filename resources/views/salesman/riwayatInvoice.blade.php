@extends('layouts.mainreact')

@push('JS')
  <script>
    $('.form_search [name=date_start]').on('change', function() {
      if ($('.form_search [name=date_start]').val() > $('.form_search [name=date_end]').val()) {
        $('.form_search [name=date_end]').val($('.form_search [name=date_start]').val());
      }
    });

    $('.form_search [name=date_end]').on('change', function() {
      if ($('.form_search [name=date_start]').val() > $('.form_search [name=date_end]').val()) {
        $('.form_search [name=date_end]').val($('.form_search [name=date_start]').val());
      }
    });
  </script>
@endpush

@section('main_content')
  <div class="page_container pt-4">
    <form action="/salesman/riwayatinvoice">
      <div class="mb-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="date_start" value="{{ $date_start ?? null }}" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="date_end" value="{{ $date_end ?? null }}" class="form-control">
      </div>
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-sm px-4">
          <i class="bi bi-search me-2"></i>Cari
        </button>
      </div>
    </form>

    <h6 class="my-4">Jumlah Invoice : {{ count($invoices) }}</h6>
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th scope="col" style="width:45%;">Customer</th>
            <th scope="col" style="width:45%;">Total</th>
            <th scope="col" class='text-center' style="width:10%;">Qty</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($invoices as $invoice)
            <tr data-bs-toggle="modal" data-bs-target="#exampleModal{{ $invoice->id }}">
              <td>{{ $invoice->linkOrder->linkCustomer->nama ?? null }}</td>
              <td>
                @if ($invoice->harga_total ?? null)
                  Rp. {{ number_format($invoice->harga_total ?? 0, 0, '', '.') }}
                @endif
              </td>
              <td class="text-center">
                @if ($invoice->linkOrder->linkOrderItem ?? null)
                  {{ count($invoice->linkOrder->linkOrderItem) }}
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @foreach ($invoices as $invoice)
      <div class="modal fade" id="exampleModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $invoice->nomor_invoice }}</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p class="fw-bold">Barang yang Dipesan</p>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col" class='text-center'>Nama Barang</th>
                      <th scope="col" class='text-center'>Kuantitas</th>
                      <th scope="col" class='text-center'>Harga Satuan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($invoice->linkOrder->linkOrderItem as $item)
                      <tr>
                        <td>{{ $item->linkItem->nama ?? null }}</td>
                        <td>{{ $item->kuantitas ?? null }}</td>
                        <td>
                          @if ($item->harga_satuan ?? null)
                            Rp. {{ number_format($invoice->harga_total ?? 0, 0, '', '.') }}
                          @endif
                        </td>
                      </tr>
                    @endforeach

                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary">
                <span class="iconify fs-3 me-1" data-icon="bi:printer"></span>Cetak
              </button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                <span class="iconify fs-3 me-1" data-icon="carbon:close-outline"></span>Close
              </button>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endsection
