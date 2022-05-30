<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
@extends('layouts/main')
@push('CSS')
<link href=" {{ mix('css/supervisor.css') }}" rel="stylesheet">
@endpush
@section('breadcrumbs')
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="/supervisor">Dashboard</a></li>
  <li class="breadcrumb-item"><a href="/supervisor/wilayah">Wilayah</a></li>
  <li class="breadcrumb-item active" aria-current="page">Lihat Wilayah</li>
</ol>
@endsection
@section('main_content')

<div class="container">

    <a class="btn btn-primary mt-2 mb-3" href="/supervisor/wilayah"><i class="bi bi-arrow-left-short fs-5"></i>Kembali</a>

    <p class="text-center fw-bold text-primary fs-2">Pembagian Wilayah</p>
<div>
@foreach($parentCategories as $category)
 
  <h3># {{$category->nama}}</h3>

  @if(count($category->subcategory))
    @include('supervisor/wilayah.subWilayahTree',['subcategories' => $category->subcategory])
  @endif
 
@endforeach
</div>
</div>

@endsection
