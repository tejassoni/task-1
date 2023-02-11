@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Order</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
            </div>
        </div>
    </div>


    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')
        @php
            $old = old();
        @endphp
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>OrderID:</strong>
                    <input type="text" name="order_id"
                        value="{{ isset($old['order_id']) && !empty($old['order_id']) ? $old['order_id'] : $order->order_id }}"
                        class="form-control" placeholder="OrderID" maxlength="50">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <select class='form-control' name='product_id' id='product_id'>
                    @if (isset($products) && $products->isNotEmpty())
                        <option value='' disabled aria-readonly="true" selected> Select Product.. </option>

                        @foreach ($products as $product_key => $product_val)
                            @php
                                $selected = '';
                                if ($product_val['id'] == $order->product_id) {
                                    $selected = 'selected';
                                }
                            @endphp
                            <option value='{{ $product_val['id'] }}' {{ $selected }}>
                                {{ $product_val['name'] }} </option>
                        @endforeach
                    @else
                        <option value='' disabled readonly> No records found..! </option>
                    @endif
                </select>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
		        <div class="form-group">
		            <strong>Total:</strong>
		            <input type="text" name="total_amount" class="form-control" value="{{ isset($old['total_amount']) && !empty($old['total_amount']) ? $old['total_amount'] : $order->total_amount }}" placeholder="Total Amount" maxlength="9" onkeypress="return isNumber(event)" >
		        </div>
		    </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <select class='form-control' name='status' id='status'>                    
                        <option value='' disabled aria-readonly="true" selected> Select Status.. </option>
                        <option value='pending'> Pending </option>
                        <option value='accepted'> Accepted </option>
                   
                </select>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>


    </form>

@endsection
