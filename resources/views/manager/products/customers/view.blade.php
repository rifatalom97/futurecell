@extends('manager.common.layout')
@section('content')
<div class="customers">
    
    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Customers</b></h2>
        </div>
        <div class="admin_info_box_body">
            <div class="row">
                <div class="col-md-6">
                    <form action="{{ url('/manager/products/customers/save') }}" method="post" class="mb-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id }}">

                        @include( 'manager/common/form/input',['title'=>'Name','name'=>'name','placeholder'=>"Name",'value'=>isset($customer->name)?$customer->name:old('name')] )
                                    
                        <div class="form-group">
                            <label class="mb-0">Email</label>
                            <input class="form-control" type="text" name="email" placeholder="Email" value="{{ isset($customer->email)?$customer->email:old('email') }}" disabled>
                        </div>
                        
                        @include( 'manager/common/form/input',['title'=>'Mobile','name'=>'mobile','placeholder'=>"Mobile",'value'=>isset($customer->mobile)?$customer->mobile:old('mobile')] )
                        
                        @include( 'manager/common/form/input',['title'=>'City','name'=>'city','placeholder'=>"City",'value'=>isset($customer->city)?$customer->city:old('city')] )
                        
                        @include( 'manager/common/form/input',['title'=>'Zip-code','name'=>'zip_code','placeholder'=>"Zip code",'value'=>isset($customer->zip_code)?$customer->zip_code:old('zip_code')] )
                        
                        @include( 'manager/common/form/input',['title'=>'New password','name'=>'password','placeholder'=>"New password",'value'=>old('password')] )
                        
                        @include( 'manager/common/form/input',['title'=>'Confirm password','name'=>'re_password','placeholder'=>"Confirm password",'value'=>old('re_password')] )
                        
                        <button type="submit" class="btn btn-dark">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection