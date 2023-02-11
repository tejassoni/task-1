@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
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


    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @php
            $old = old();
        @endphp
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ (isset($old['name']) && !empty($old['name']))?$old['name']:$product->name }}" class="form-control"
                        placeholder="Name" maxlength="50" onkeypress="return isString(event)">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Price:</strong>
                    <input type="text" name="price" class="form-control" maxlength="9" placeholder="Price" value="{{ (isset($old['price']) && !empty($old['price']))?$old['price']:$product->price }}"
                        onkeypress="return isNumber(event)">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Detail:</strong>
                    <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ (isset($old['detail']) && !empty($old['detail']))?$old['name']:$product->detail }}</textarea>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <input class='form-control @error('formFile') is-invalid @enderror' type='file' name='formFile'
                    id='formFile' accept='image/*'> </br>
                @php
                    $image = asset("/storage/asset/$product->image");
                @endphp
                <img id="formFile_src" src='{{ $image }}' alt='{{ __('sample-image.jpg') }}' height="125"
                    width="125">
                &nbsp;<button type="button" class="btn btn-outline-danger btn-sm img_close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>


    </form>

@endsection
