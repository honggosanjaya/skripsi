@extends('layouts/main')

@section('main_content')

<div id="report">
    <form action="/owner/kinerja" method="post">
        @csrf
        <div class="row">
            <div class="col-7">
                <div class="input-group my-3">
                    <span class="input-group-text" id="basic-addon1">Year</span>
                    <input type="text" class="form-control" placeholder="2023" name="year" value="2020">
                </div>
            </div>
            <div class="col-7 my-3">
                <select class="form-select" aria-label="Default select example" name="month">
                    <option value="1">Januari</option>
                    <option value="2">Febuari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                  </select>
            </div>
            <div class="col-5">
            </div>

            <div class="col-3">
                Date Start
            </div>
            <div class="col-1">
            </div>
            <div class="col-3">
                Date End
            </div>
            <div class="col-5">
            </div>

            <div class="col-3">
                <input type="date" name="dateStart" value="" id="dateStart">
            </div>
            <div class="col-1">
            </div>
            <div class="col-3">
                <input type="date" name="dateEnd" value="" id="dateEnd">
            </div>
            <div class="col-6 my-3">
                <button type="submit" class="btn btn-primary">filter</button>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
          <tr>
            <th scope="col">no</th>
            <th scope="col">Nama Sales</th>
            <th scope="col">Jumlah Kunjungan</th>
            <th scope="col">Pelanggan baru</th>
            <th scope="col">Effective Call</th>
            <th scope="col">total Omset</th>
            <th scope="col">Detail</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($data['Sales'] as $sales)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>Otto</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    <td>@mdo</td>
                </tr>
            @endforeach
        </tbody>
      </table>

    @push('CSS')
        <style>
            #report .border-report{
                border: 2px solid rgba(255, 0, 0, 0.2);;
                border-radius: 12px;
                padding: 15px 15px 30px;
            }
            
        </style>
    @endpush
    @push('JS')
        <script src="{{ asset('js/chart.js') }}"></script>
        <script src="{{ mix('js/report.js') }}"></script>
    @endpush
@endsection
