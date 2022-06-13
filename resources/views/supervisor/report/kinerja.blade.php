@extends('layouts/main')

@section('main_content')
  <canvas id="kinerjaSalesChart" width="300" height="300">

    <script src="{{ asset('chart.js/chart.js') }}"></script>
    <script src="{{ mix('js/chartLaporan.js') }}"></script>
  @endsection
