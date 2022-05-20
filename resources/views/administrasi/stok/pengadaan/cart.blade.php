@extends('layouts.main')

@section('main_content')
  @if ($message = Session::get('success'))
    <p class="text-success">{{ $message }}</p>
  @endif

  <table class="table table-hover table-sm">
    <thead>
      <tr>
        <th scope="col">Nama</th>
        <th scope="col">Quantity</th>
        {{-- dll --}}
        <th scope="col">Remove</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($cartItems as $item)
        <tr>
          <td>{{ $item->name }}</td>
          <td>
            <form action="{{ route('cart.update') }}" method="POST">
              @csrf
              <input type="hidden" name="id" value="{{ $item->id }}">
              <input type="number" name="quantity" value="{{ $item->quantity }}" />
              <button type="submit" class="btn btn-sm btn-primary">update</button>
            </form>
          </td>
          <td>
            <form action="{{ route('cart.remove') }}" method="POST">
              @csrf
              <input type="hidden" value="{{ $item->id }}" name="id">
              <button class="btn btn-sm text-danger">x</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div>
    {{-- Total: ${{ Cart::getTotal() }} --}}
  </div>
  <div>
    <form action="{{ route('cart.clear') }}" method="POST">
      @csrf
      <button class="btn btn-danger">Remove All Cart</button>
    </form>
  </div>
@endsection
