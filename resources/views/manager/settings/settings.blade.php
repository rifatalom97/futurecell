@extends('manager.common.layout')
@section('content')
<div class="categories">
    @include('manager.common.sessionMessage')

    <form action="{{ url('manager/settings') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="bg-gray admin_info_box">
                    <div class="bg-info text-white admin_info_box_header">
                        <h2><b>Settings</b></h2>
                    </div>
                    <div class="admin_info_box_body">
                        <div class="row">
                            <div class="col-md-12">
                                @include( 'manager/common/form/input',['title'=>'Site name','name'=>'site_name','value'=>(isset($options['site_name'])?$options['site_name']:'')] )
                            </div>
                            <div class="col-md-12">
                                @include( 'manager/common/form/input',['type'=>'email','title'=>'Administrator Email','name'=>'administrator_email','value'=>(isset($options['administrator_email'])?$options['administrator_email']:'')] )
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                @include( 'manager/common/form/upload',['title'=>'Logo','name'=>'logo','value'=>(isset($options['logo'])?$options['logo']:'')] )
                            </div>
                            <div class="col-md-6">
                                @include( 'manager/common/form/upload',['title'=>'Favicon','name'=>'favicon','value'=>(isset($options['favicon'])?$options['favicon']:'')] )
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-dark btn-lg mt-4" type="submit">Save settings</button>
            </div>

            <div class="col-md-6">
                @include( 'manager/common/metaTags', ['values'=>$metaTags] )
            </div>
        </div>

        <!-- <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div> -->
    </form>
</div>
@endsection