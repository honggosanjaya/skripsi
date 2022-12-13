@extends('layouts.mainmobile')
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="/administrasi/datacustomer">Data Customer</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail</li>
  </ol>
@endsection

@section('main_content')
  <div class="pt-4 container">
    <h3 class="mb-5">Detail Customer</h3>
    <div class="informasi-list mb_big">
      <span class="d-flex align-items-center"><b>Nama Customer</b>
        <span>{{ $customer->nama ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Kode Customer</b>
        <span>{{ $customer->kode_customer ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Jenis Customer</b>
        <span>{{ $customer->linkCustomerType->nama ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Wilayah</b>
        <span>{{ $customer->linkDistrict->nama ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Email</b>
        <span>{{ $customer->email ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Alamat</b>
        <span>{{ $customer->full_alamat ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Koordinat</b>
        <span>{{ $customer->koordinat ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Telepon</b>
        <span>{{ $customer->telepon ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Durasi Kunjungan</b>
        <span>{{ $customer->durasi_kunjungan ?? null . ' hari' }}</span>
      </span>
      <span><b>Counter Effective Call</b>
        {{ $customer->counter_to_effective_call ?? null }}</span>
      <span class="d-flex align-items-center"><b>Tipe Retur</b>
        <span>{{ $customer->tipe_retur ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Limit Pembelian</b>
        <span>{{ $customer->limit_pembelian ?? null }}</span>
      </span>
      <span class="d-flex align-items-center"><b>Pengajuan Limit</b>
        @if ($old_data)
          <span>{{ $old_data['pengajuan_limit_pembelian'] }}</span>
        @else
          <span>{{ $customer->pengajuan_limit_pembelian ?? null }}</span>
        @endif
      </span>

      <span class="d-flex align-items-center"><b>Status Pengajuan</b>
        <span>
          @if ($old_data)
            {{ $old_data['status_limit_pembelian_enum'] == 1
                ? 'Disetujui'
                : ($old_data['status_limit_pembelian_enum'] == -1
                    ? 'Tidak Disetujui'
                    : 'Diajukan') }}
          @else
            @if ($customer->status_limit_pembelian_enum != null)
              {{ $customer->status_limit_pembelian_enum == 1
                  ? 'Disetujui'
                  : ($customer->status_limit_pembelian_enum == -1
                      ? 'Tidak Disetujui'
                      : 'Diajukan') }}
            @endif
          @endif
        </span>
      </span>

      @if ($customer->status_enum != null)
        <span><b>Status</b>
          {{ $customer->status_enum == '1' ? 'Active' : ($customer->status_enum == '0' ? 'Hide' : 'Inactive') }}</span>
      @endif

      <span><b>Foto Tempat Usaha</b>
        @if ($customer->foto ?? null)
          <img src="{{ asset('storage/customer/' . $customer->foto) }}" class="img-preview img-fluid">
        @else
          Tidak ada foto
        @endif
      </span>
    </div>

    <hr class="my-5">
    <h1 class="fs-4">Invoice Customer</h1>

    <div class="table-responsive mt-4">
      <table class="table table-hover table-sm">
        <thead>
          <tr>
            <th scope="col" class="text-center">No</th>
            <th scope="col" class="text-center">Tanggal</th>
            <th scope="col" class="text-center">Invoice</th>
            <th scope="col" class="text-center">Total harga (Rp)</th>
            <th scope="col" class="text-center">Sales</th>
          </tr>
        </thead>
        <tbody>
          @if (count($invoices) == 0)
            <tr>
              <td colspan="5" class="text-center text-danger">Tidak Ada Data</td>
            </tr>
          @endif

          @foreach ($invoices as $invoice)
            <tr>
              <th scope="row" class="text-center">
                {{ ($invoices->currentPage() - 1) * $invoices->perPage() + $loop->iteration }}
              </th>
              <td data-order="{{ date('Y-m-d', strtotime($invoice->created_at ?? null)) }}">
                {{ date('d M Y', strtotime($invoice->created_at ?? null)) }}
              </td>
              <td>
                <a class="text-primary cursor-pointer text-decoration-none" data-bs-toggle="modal"
                  data-bs-target="#invoice{{ $invoice->id }}">
                  {{ $invoice->nomor_invoice ?? null }}
                </a>
              </td>
              <td>{{ $invoice->harga_total ?? null }}</td>
              <td>{{ $invoice->linkOrder->linkStaff->nama ?? null }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @foreach ($invoices as $invoice)
      <div class="modal fade" id="invoice{{ $invoice->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Detail Invoice</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="info-list">
                <span class="d-flex"><b>Nomor invoice</b>
                  {{ $invoice->nomor_invoice ?? null }}</span>
                <span class="d-flex"><b>Tanggal pesan</b>
                  {{ date('d F Y', strtotime($invoice->created_at ?? '-')) }}</span>

                @if ($invoice->linkOrder->linkOrderTrack->status_enum != null)
                  @if ($invoice->linkOrder->linkOrderTrack->status_enum == '1')
                    <span class="d-flex"><b>Status Pesanan</b>Diajukan Salesman</span>
                  @elseif ($invoice->linkOrder->linkOrderTrack->status_enum == '2')
                    <span class="d-flex"><b>Status Pesanan</b>Dikonfirmasi Admin</span>
                  @elseif ($invoice->linkOrder->linkOrderTrack->status_enum == '3')
                    <span class="d-flex"><b>Status Pesanan</b>Dalam Perjalanan</span>
                  @elseif ($invoice->linkOrder->linkOrderTrack->status_enum == '4')
                    <span class="d-flex"><b>Status Pesanan</b>Telah Sampai</span>
                  @elseif ($invoice->linkOrder->linkOrderTrack->status_enum == '5')
                    <span class="d-flex"><b>Status Pesanan</b>Pembayaran</span>
                  @elseif ($invoice->linkOrder->linkOrderTrack->status_enum == '6')
                    <span class="d-flex"><b>Status Pesanan</b>Selesai</span>
                  @endif
                @endif

                <span class="d-flex"><b>Event</b>
                  {{ $invoice->linkEvent->nama ?? null }}</span>

                @if ($invoice->linkEvent != null)
                  @if ($invoice->linkEvent->potongan != null)
                    <span class="d-flex"><b>Potongan Event</b>
                      {{ $invoice->linkEvent->potongan }}</span>
                  @elseif($invoice->linkEvent->diskon != null)
                    <span class="d-flex"><b>Diskon Event</b>
                      {{ $invoice->linkEvent->diskon }} %</span>
                  @endif
                @endif

                <span class="d-flex"><b>Harga Total</b>
                  Rp. {{ number_format($invoice->harga_total ?? 0, 0, '', '.') }}</span>

                <span class="d-flex"><b>Diskon Customer</b>
                  {{ $customer->linkCustomerType->diskon ?? null }} %</span>

                @foreach ($invoiceJatuhTempo as $inv)
                  @if ($inv['id_invoice'] == $invoice->id)
                    <span class="d-flex"><b>Jatuh Tempo</b>
                      {{ date('d M Y', strtotime($inv['tanggalJatuhTempo'])) }}</span>
                  @endif
                @endforeach
              </div>

              <div class="table-responsive mt-4">
                <table class="table table-hover table-sm">
                  <thead>
                    <tr>
                      <th scope="col" class="text-center">No</th>
                      <th scope="col" class="text-center">Nama Barang</th>
                      <th scope="col" class="text-center">Kuantitas</th>
                      <th scope="col" class="text-center">Satuan</th>
                      <th scope="col" class="text-center">Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($invoice->linkOrder->linkOrderItem as $item)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->linkItem->nama ?? null }}</td>
                        <td class="text-center">{{ $item->kuantitas ?? null }}</td>
                        <td class="text-center">{{ $item->linkItem->satuan ?? null }}</td>

                        @if ($customer->tipe_harga ?? null)
                          @if ($customer->tipe_harga == 2 && $item->linkItem->harga2_satuan != null)
                            <td class="text-center">
                              {{ number_format($item->linkItem->harga2_satuan * $item->kuantitas ?? 0, 0, '', '.') }}
                            </td>
                          @elseif ($customer->tipe_harga == 3 && $item->linkItem->harga3_satuan != null)
                            <td class="text-center">
                              {{ number_format($item->linkItem->harga3_satuan * $item->kuantitas ?? 0, 0, '', '.') }}
                            </td>
                          @else
                            <td class="text-center">
                              {{ number_format($item->linkItem->harga1_satuan * $item->kuantitas ?? 0, 0, '', '.') }}
                            </td>
                          @endif
                        @else
                          <td></td>
                        @endif
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    <div class="d-flex flex-row mt-4">
      {{ $invoices->links() }}
    </div>
  </div>
@endsection
