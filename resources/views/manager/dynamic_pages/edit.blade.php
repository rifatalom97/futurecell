@extends('manager.common.layout')
@section('content')
<div class="pages">
    <form action="{{ url('/manager/pages/save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $page->id }}">
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Page details</b></h2>
            </div>
            <div class="admin_info_box_body">
                <div class="page_form">
                    <div class="row">
                        <div class="col-md-8">
                            @include( 'manager/common/form/multiLangInput',['title'=>'Title','name'=>'title','values'=>$page->metas] )
                            @include( 'manager/common/form/urlslug',['url_prefix'=>'/page','value'=>$page->slug] )
                            @include( 'manager/common/form/multiLangInput',['title'=>'Sub-Title','name'=>'sub_title','values'=>$page->metas] )
                            @include( 'manager/common/form/multiLangInput',['type'=>'textarea','title'=>'Content','name'=>'content','values'=>$page->metas] )
                        </div>

                        <div class="col-md-4">
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>$page->status] )
                            @include( 'manager/common/form/upload',['title'=>'Thumbnail','name'=>'thumbnail','value'=>$page->thumbnail] )
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        @include( 'manager/common/metaTags', ['values'=>$page->metaTags] )

        <button class="btn btn-dark btn-lg mt-4" type="submit">Update model</button>
    </form>
</div>
@endsection