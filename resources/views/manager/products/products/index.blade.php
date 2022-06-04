@extends('manager.common.layout')
@section('content')
<div class="products">

    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Products</b></h2>
            <div class="title_right">
                <a class="btn btn-md btn-dark" href="{{ url('/manager/products/add') }}">Add new product</a>
            </div>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/pages') }}" class="mb-4 row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" type="search" name="search" placeholder="Search title" value="{{ (isset($_GET['search'])?$_GET['search']:false) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div>
                                    <select name="status" class="form-control">
                                        <option value="">Choose status</option>
                                        <option value="1" {{ (isset($_GET['status'])?'selected':false) }}>Active</option>
                                        <option value="0" {{ (isset($_GET['status'])?'selected':false) }}>Deactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                   <button type="submit" class="btn btn-dark">Search</button>
                </div>
            </form>

            <div class="all_products table_container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sort_button">#</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="title">Title</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="modelNumber">Model number</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="barcode">Barcode</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="quantity">Quantity</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="price">Sale Price</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($products)&&count($products))
                            @foreach($products as $product)
                                <tr id="product_{{ $product->id }}">
                                    <td><input type="checkbox" name="products" value="{{ $product->id }}"></td>
                                    <td>{{ $product->meta->title }}</td>
                                    <td>{{ $product->model_number }}</td>
                                    <td>{{ $product->barcode }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>${{ $product->price }}</td>
                                    <td>{{ ($product->status==1?'Publish':'Draft') }}</td>
                                    <td>{{ date('d F, Y', strtotime($product->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/store/'.$product->slug) }}" target="_blank">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/view_icon.svg') }}" alt="View" title="View {{ $product->meta->title }}">
                                        </a>
                                        <a href="{{ url('/manager/products/'.$product->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $product->meta->title }}">
                                        </a>
                                        <a href="{{ url('/manager/products/delete/'.$product->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $product->meta->title }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else 
                        <tr id="product_19">
                            <td colspan="10">No product found to show</td>
                        </tr>
                        @endif 
                    </tbody>
                </table>
                </div>

        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $products])
</div>
@endsection