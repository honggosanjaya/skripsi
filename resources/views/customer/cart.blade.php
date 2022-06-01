@extends('customer.layouts.customerLayouts')

@section('content')
    <table class="table">
      <thead>
        <th scope="col">#</th>
        <th scope="col">Nama</th>
        <th scope="col">Jumlah</th>
        <th scope="col">Price</th>
      </thead>
      <tbody>
        @php
          $total = 0;
          $t_items = 0;
        @endphp
        @foreach ($cartItems as $item)
          <tr>
            @php
              $t_items = $item->quantity * $item->price;
              $total += $t_items;
            @endphp
            <th scope="row">{{ $loop->index + 1 }}</th>
            <td>{{ $item->name }}</td>
            <td>{{ $item->quantity . ' X ' . $item->price }}</td>
            <td>{{ $t_items }}</td>

          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <td colspan="3" class="table-active">Sub-Total</td>
        <td>{{ $total }}</td>
      </tfoot>
    </table>
    <a href="/customer/cart/tambahorder?route=customerOrder" type="button" class="btn btn-success">submit</a>
@endsection
