@foreach ($subcategories as $subcategory)
  <ul>
    <li>
      <h5 class="fs-5">&#187; {{ $subcategory->nama }}</h5>
    </li>
    @if (count($subcategory->subcategory))
      @include('supervisor/wilayah.subWilayahTree', ['subcategories' => $subcategory->subcategory])
    @endif
  </ul>
@endforeach
