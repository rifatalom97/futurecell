@extends('manager.common.layout')
@section('content')
<div class="create_category">

    <form action="{{ url('/manager/category/save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="bg-gray admin_info_box">
                    <div class="bg-info text-white admin_info_box_header">
                        <h2><b>Category</b></h2>
                    </div>
                    <div class="admin_info_box_body">
                            
                        <div class="admin_form">
                            @include( 'manager/common/form/multiLangInput',['title'=>'Title','name'=>'title'] )

                            @include( 'manager/common/form/urlslug',['url_prefix'=>'/category'] )

                            @include( 'manager/common/form/upload',['title'=>'Image','name'=>'image','value'=>''] )
                            
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>old('status')] )
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                @include( 'manager/common/metaTags', ['values'=>array()] )
            </div>
        </div>

        <button class="btn btn-dark btn-lg mt-4" type="submit">Add Category</button>
    </form>

</div>
@endsection