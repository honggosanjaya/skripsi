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
  <div class="pt-4 px-5">
    <h1 class="fw-bold fs-3">Pembagian Wilayah</h1>
    @foreach ($parentCategories as $category)
      <h2 class="fs-5 fw-bold"># {{ $category->nama ?? null }}</h2>
      @if (count($category->subcategory ?? null))
        @include('supervisor.wilayah.subWilayahTree', ['subcategories' => $category->subcategory])
      @endif
    @endforeach
  </div>
@endsection
