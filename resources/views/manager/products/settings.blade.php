@extends('manager.common.layout')
@section('content')
<div class="products_settings">

    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Settings</b></h2>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/products/settings') }}" class="mb-4 row" method="POST">
                @csrf 
                <div class="col-md-4">
                    @include( 'manager/common/form/multiLangInput',['title'=>'Credit info title','name'=>'creditInfo[title]','placeholder'=>'','values'=>(isset($options['creditInfo']->title)?$options['creditInfo']->title:null)] )
                    @include( 'manager/common/form/multiLangInput',['title'=>'Credit info details','name'=>'creditInfo[details]','placeholder'=>'','values'=>(isset($options['creditInfo']->details)?$options['creditInfo']->details:null)] )
                </div>
                <div class="col-md-4">
                    <div class="payment_settings">
                        <div id="pelecard" class="active payment_api_option">
                            <label for="pelecard">
                                <input id="pelecard" type="radio" name="apiType" value="pelecard">
                                Pelecard
                            </label>
                            <div class="payment_api_details">
                                @include( 'manager/common/form/input',['type'=>'text','title'=>'Terminal','name'=>'pelecard[terminal]','placeholder'=>'Terminal','value'=>(isset($options['pelecard']->terminal)?$options['pelecard']->terminal:'')] )

                                @include( 'manager/common/form/input',['type'=>'text','title'=>'Username','name'=>'pelecard[username]','placeholder'=>'Username','value'=>(isset($options['pelecard']->username)?$options['pelecard']->username:'')] )

                                @include( 'manager/common/form/input',['type'=>'password','title'=>'Password','name'=>'pelecard[password]','placeholder'=>'Password','value'=>(isset($options['pelecard']->password)?$options['pelecard']->password:'')] )
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                   <button type="submit" class="btn btn-dark btn-lg">Save settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection