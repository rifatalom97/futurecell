@extends('manager.common.layout')
@section('content')
<div class="create_brand">

    <form action="{{ url('/manager/brands/save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $brand->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="bg-gray admin_info_box">
                    <div class="bg-info text-white admin_info_box_header">
                        <h2><b>Brand</b></h2>
                    </div>
                    <div class="admin_info_box_body">
                            
                        <div class="admin_form">
                            
                            @include( 'manager/common/form/multiLangInput',['title'=>'Title','name'=>'title','values'=>$brand->metas] )
                            
                            <div class="form-group">
                                <label class="mb-0">Url Slug</label>
                                <input id="slug_field" class="form-control" type="text" name="slug" placeholder="Url slug" onKeyup="slug_filter(this)" value="{{ $brand->slug }}">
                                <span class="d-block small">{{ url('/brand') }}<span id="slug_viewer">/{{ $brand->slug }}</span></span>
                                @error('slug')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            @include( 'manager/common/form/upload',['title'=>'Image','name'=>'image','value'=>$brand->image] )
                            
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>$brand->status] )
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                @include( 'manager/common/metaTags', ['values'=>$brand->metaTags] )
            </div>
        </div>

        <button class="btn btn-dark btn-lg mt-4" type="submit">Update</button>
    </form>
</div>
@endsection