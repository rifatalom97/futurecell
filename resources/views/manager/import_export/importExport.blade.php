@extends('manager.common.layout')
@section('content')
<div class="admins">
    
    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Import</b></h2>
        </div>
        <div class="admin_info_box_body">
            <div class="product_import" style="max-width: 249px;">
                <form action="{{ url('manager/import') }}" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="form-group">
                        <select data-group="" name="table" class="form-control" required>
                            <option value="">Choose a table</option>
                            <option value="users">Customers</option>
                            <option value="products">Products</option>
                            <option value="coupons">Coupons</option>
                            <option value="brand">Brand</option>
                            <option value="model">Model</option>
                            <option value="category">Category</option>
                            <option value="color">Color</option>
                            <option value="size">Size</option>
                            <option value="delivery_options">Delivery options</option>
                            <option value="subscribers">Subscribers</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input class="form-control" type="file" name="excelFile">
                    </div>

                    <div class="clearfix mt-4 mb-2">
                        <button class="btn btn-lg btn-dark" style="min-width: 249px;">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Export</b></h2>
        </div>
        <div class="admin_info_box_body">
            <div class="product_export" style="max-width: 249px;">
                <form action="{{ url('manager/export') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <select name="table" class="form-control" required>
                            <option value="">Choose a table</option>
                            <option value="users">Customers</option>
                            <option value="products">Products</option>
                            <option value="coupons">Coupons</option>
                            <option value="brand">Brand</option>
                            <option value="model">Model</option>
                            <option value="category">Category</option>
                            <option value="color">Color</option>
                            <option value="size">Size</option>
                            <option value="delivery_options">Delivery options</option>
                            <option value="subscribers">Subscribers</option>
                        </select>
                    </div>
                    <div class="clearfix mt-4 mb-2">
                        <button class="btn btn-lg btn-dark" style="min-width: 249px;">Export product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection