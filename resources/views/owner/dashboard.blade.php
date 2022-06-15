@extends('layouts/main')

@section('main_content')
<div id="report">
    <form action="/owner/" method="get">
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
    @php
        $produk_laris['item_name']=[];  
        $produk_laris['item_total']=[];  
    @endphp
    @foreach ($data['produk_laris'] as $item)
        @php
            array_push($produk_laris['item_name'], $item->linkItem->nama);
            array_push($produk_laris['item_total'], $item->total);
        @endphp
    @endforeach
    <div class="container">
        <div class="row">
            <div class="col-6 my-2">
                <div class="border-report">
                    <canvas id="kinerjaSalesChart" style="height: 200px;" data-label="{{json_encode($produk_laris['item_name'])}}" data-value="{{json_encode($produk_laris['item_total'])}}" >
                </div>
            </div>
            <div class="col-6 my-2">
                <div class="border-report">
                    <h5>omzet</h5>
                    <p>{{$data['omzet']->total}}</p>
                    <h5>pembelian</h5>
                    <p>{{$data['pembelian']->total}}</p>
                    <h5>total</h5>
                    <p>{{$data['omzet']->total-$data['pembelian']->total}}</p>
                    <button class="btn btn-primary">detail>></button>
                </div>
            </div>
            <div class="col-6 my-2">
                <div class="border-report">
                    <h5>list produk dengan penjualan terdikit</h5>
                    @foreach ($data['produk_slow'] as $item)
                        <p>{{$loop->iteration}}. {{$item->linkItem->nama}} terjual sebanyak {{$item->total}}</p>
                    @endforeach
                </div>
            </div>
            <div class="col-6 my-2">
                <div class="border-report">
                    <h5>Produk tidak terjual</h5>
                    @foreach ($data['produk_tidak_terjual'] as $item)
                        <p>{{$loop->iteration}}. {{$item->nama}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
  

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