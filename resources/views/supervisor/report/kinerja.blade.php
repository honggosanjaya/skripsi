@extends('layouts/main')
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/{{ auth()->user()->linkStaff->linkStaffRole->nama }}">Dashboard</a></li>
  <li class="breadcrumb-item active" aria-current="page">Kinerja Salesman</li>
</ol>
@endsection
@section('main_content')

<div id="report">
    <form action="/owner/report/kinerja" method="get">
        @csrf
        <div class="row">
            <div class="col-7">
                <div class="input-group my-3">
                    <span class="input-group-text" id="basic-addon1">Year</span>
                    <input type="text" class="form-control" placeholder="2023" name="year" value="{{$input['year']??null}}">
                </div>
            </div>
            <div class="col-7 my-3">
                <select class="form-select" aria-label="Default select example" name="month">
                    <option {{  1 == $input['month'] ? 'selected' : '' }} value="1">Januari</option>
                    <option {{  2 == $input['month'] ? 'selected' : '' }} value="2">Febuari</option>
                    <option {{  3 == $input['month'] ? 'selected' : '' }} value="3">Maret</option>
                    <option {{  4 == $input['month'] ? 'selected' : '' }} value="4">April</option>
                    <option {{  5 == $input['month'] ? 'selected' : '' }} value="5">Mei</option>
                    <option {{  6 == $input['month'] ? 'selected' : '' }} value="6">Juni</option>
                    <option {{  7 == $input['month'] ? 'selected' : '' }} value="7">Juli</option>
                    <option {{  8 == $input['month'] ? 'selected' : '' }} value="8">Agustus</option>
                    <option {{  9 == $input['month'] ? 'selected' : '' }} value="9">September</option>
                    <option {{  10 == $input['month'] ? 'selected' : '' }} value="10">Oktober</option>
                    <option {{  11 == $input['month'] ? 'selected' : '' }} value="11">November</option>
                    <option {{  12 == $input['month'] ? 'selected' : '' }} value="12">Desember</option>
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
                <input type="date" name="dateStart" value="{{$input['dateStart']??null}}" id="dateStart">
            </div>
            <div class="col-1">
            </div>
            <div class="col-3">
                <input type="date" name="dateEnd" value="{{$input['dateEnd']??null}}" id="dateEnd">
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
            @foreach ($staffs as $sales)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>{{$sales->nama}}</td>
                    <td>{{$sales->linkTrip->count()}}</td>
                    <td>{{$sales->linkTripEcF->count()}}</td>
                    <td>{{$sales->linkTripEc->count()}}</td>
                    <td>{{$sales->linkOrder[0]->total}}</td>
                    <td><button class="btn btn-primary">detail</button></td>
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
