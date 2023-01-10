@extends('layouts/main')
@push('CSS')
  <link href=" {{ mix('css/administrasi.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/administrasi">Dashboard</a></li>
    <li class="breadcrumb-item active" aria-current="page">Laporan Excel</li>
  </ol>
@endsection

@section('main_content')
  @if (session()->has('successMessage'))
    <div id="hideMeAfter3Seconds">
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('successMessage') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  @endif

  <div class="px-5 pt-4">
    <h1 class="fs-5 mb-4">Laporan - Laporan</h1>

    <button type="button" class="btn btn-primary me-3 mt-3" data-bs-toggle="modal" data-bs-target="#aktivitasPenjualan">
      <i class="bi bi-download px-1 me-1"></i>Aktivitas Penjualan
    </button>

    <button type="button" class="btn btn-primary me-3 mt-3" data-bs-toggle="modal" data-bs-target="#penjualanBersih">
      <i class="bi bi-download px-1 me-1"></i>Rekap Penjualan Bersih
    </button>

    <button type="button" class="btn btn_purple me-3 mt-3" data-bs-toggle="modal" data-bs-target="#rincianKas">
      <i class="bi bi-download px-1 me-1"></i>Rincian Kas
    </button>

    <button type="button" class="btn btn_purple me-3 mt-3" data-bs-toggle="modal" data-bs-target="#penerimaanPelanggan">
      <i class="bi bi-download px-1 me-1"></i>Rekap Penerimaan Pelanggan
    </button>

    <button type="button" class="btn btn-success me-3 mt-3" data-bs-toggle="modal" data-bs-target="#analisaPenjualan">
      <i class="bi bi-download px-1 me-1"></i>Analisa Penjualan Pelanggan
    </button>

    <button type="button" class="btn btn-success me-3 mt-3" data-bs-toggle="modal" data-bs-target="#piutangUmur">
      <i class="bi bi-download px-1 me-1"></i>Piutang & Umur Piutang
    </button>

    <button type="button" class="btn btn-warning me-3 mt-3" data-bs-toggle="modal" data-bs-target="#labaRugi">
      <i class="bi bi-download px-1 me-1"></i>Laba Rugi
    </button>

    <button type="button" class="btn btn-warning me-3 mt-3" data-bs-toggle="modal" data-bs-target="#aktivitasKunjungan">
      <i class="bi bi-download px-1 me-1"></i>Laporan Aktivitas Kunjungan
    </button>

    <div class="modal fade" id="aktivitasPenjualan" tabindex="-1" aria-labelledby="aktivitasPenjualanLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="aktivitasPenjualanLabel">Laporan Aktivitas Penjualan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateStartPenjualanSales" class="form-control"
                      value="{{ date('Y-m-01') }}">
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date End</label>
                    <input type="date" name="dateEndPenjualanSales" class="form-control" value="{{ date('Y-m-t') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="penjualan-sales" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="penjualanBersih" tabindex="-1" aria-labelledby="penjualanBersihLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="penjualanBersihLabel">Laporan Penjualan Bersih</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateStartPenjualanBersih" class="form-control"
                      value="{{ date('Y-m-01') }}">
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date End</label>
                    <input type="date" name="dateEndPenjualanBersih" class="form-control"
                      value="{{ date('Y-m-t') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="penjualan-bersih" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="rincianKas" tabindex="-1" aria-labelledby="rincianKasLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="rincianKasLabel">Rincian Kas</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateStartRincianKas" class="form-control"
                      value="{{ date('Y-m-01') }}">
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date End</label>
                    <input type="date" name="dateEndRincianKas" class="form-control" value="{{ date('Y-m-t') }}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <select class="form-select" name="kas">
                      @foreach ($bukuKas as $kas)
                        <option value="{{ $kas->id }}">{{ $kas->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="rincian-kas" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="penerimaanPelanggan" tabindex="-1" aria-labelledby="penerimaanPelangganLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="penerimaanPelangganLabel">Rekap Penerimaan Pelanggan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateStartPenerimaanPelanggan" class="form-control"
                      value="{{ date('Y-m-01') }}">
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date End</label>
                    <input type="date" name="dateEndPenerimaanPelanggan" class="form-control"
                      value="{{ date('Y-m-t') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="penerimaan-pelanggan" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="penjualanPelanggan" tabindex="-1" aria-labelledby="penjualanPelangganLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="penjualanPelangganLabel">Analisa Penjualan Pelanggan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="analisa-penjualan" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="piutangUmur" tabindex="-1" aria-labelledby="piutangUmurLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="piutangUmurLabel">Piutang & Umur Piutang</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateStartPiuangUmur" class="form-control"
                      value="{{ date('Y-m-01') }}">
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date End</label>
                    <input type="date" name="dateEndPiuangUmur" class="form-control" value="{{ date('Y-m-t') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="piutang-umur" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="labaRugi" tabindex="-1" aria-labelledby="labaRugiLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="labaRugiLabel">Laba Rugi</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateStartLabaRugi" class="form-control" value="{{ date('Y-m-01') }}">
                  </div>
                </div>
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date End</label>
                    <input type="date" name="dateEndLabaRugi" class="form-control" value="{{ date('Y-m-t') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="laba-rugi" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="aktivitasKunjungan" tabindex="-1" aria-labelledby="aktivitasKunjunganLabel"
      aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="aktivitasKunjunganLabel">Laporan Aktivitas Kunjungan</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form action="" method="get">
            <div class="modal-body">
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Date Start</label>
                    <input type="date" name="dateTrip" class="form-control" value="{{ date('Y-m-d') }}">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Nama Sales</label>
                    <select class="form-select" name="salesmanAktivitasKunjungan">
                      @foreach ($staff as $sales)
                        <option value="{{ $sales->id }}">{{ $sales->nama }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-success download-report" data-excel="aktivitas-kunjungan" type="button">
                <i class="bi bi-download px-1 me-1"></i>Download
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>


  @push('JS')
    <script src="{{ mix('js/administrasi.js') }}"></script>
    <script>
      $(".download-report").click(function() {
        let data = $("form").serialize();
        const url = new URL(`https://example.com?${data}`);
        const params = new URLSearchParams(url.search);
        params.delete('_token');

        const data_id = $(this).data('id');
        if (data_id !== undefined) {
          params.append('id', data_id);
        }

        location.href = window.location.origin + "/administrasi/excel/" + $(this).data('excel') + "?" + params
          .toString()
      });
    </script>
  @endpush
@endsection
