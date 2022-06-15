@extends('layouts/main')

@section('main_content')
<div id="report">
    <form action="/owner/report/penjualan" method="get">
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
            <div class="col-7">
                <div class="input-group my-3">
                    <span class="input-group-text" id="basic-addon1">nama sales</span>
                    <input type="text" class="form-control" placeholder="julian" name="salesman" value="{{$input['salesman']??null}}">
                </div>
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
            <th scope="col">Tanggal</th>
            <th scope="col">Invoice</th>
            <th scope="col">Total harga</th>
            <th scope="col">Sales</th>
            <th scope="col">Customer</th>
            <th scope="col">Tipe customer</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($data as $dt)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <td>{{$dt->created_at}}</td>
                    <td>{{$dt->linkInvoice->nomor_invoice}}</td>
                    <td>{{$dt->linkInvoice->harga_total}}</td>
                    <td>{{$dt->linkStaff->nama}}</td>
                    <td>{{$dt->linkCustomer->nama}}</td>
                    <td>{{$dt->linkCustomer->linkCustomerType->nama}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    @push('JS')
        <script src="{{ asset('js/chart.js') }}"></script>
        <script src="{{ mix('js/report.js') }}"></script>
    @endpush

@endsection
