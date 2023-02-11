@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Orders</h2>
            </div>
            <div class="pull-right">
                @can('product-create')
                <a class="btn btn-success" href="{{ route('orders.create') }}"> Create New Order</a>
                @endcan
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>OrderID</th>
            <th>Product</th>
            <th>Total</th>
            <th width="280px">Action</th>
        </tr>
	    @foreach ($orders as $order)
	    <tr>
	        <td>{{ ++$i }}</td>
	        <td>{{ $order->order_id }}</td>
	        <td>{{ $order->getProductHasOne->name ?? null }}</td>
            <td>{{ $order->total_amount }}</td>
	        <td>
                <form action="{{ route('orders.destroy',$order->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('orders.show',$order->id) }}">Show</a>
                    @can('order-edit')
                    <a class="btn btn-primary" href="{{ route('orders.edit',$order->id) }}">Edit</a>
                    @endcan


                    @csrf
                    @method('DELETE')
                    @can('order-delete')
                    <button type="submit" class="btn btn-danger">Delete</button>
                    @endcan
                </form>
	        </td>
	    </tr>
	    @endforeach
    </table>


    {!! $orders->links() !!}


@endsection