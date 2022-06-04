@extends('manager.common.layout')
@section('content')
<div class="create_category">

    <form action="{{ url('/manager/category/save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $category->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="bg-gray admin_info_box">
                    <div class="bg-info text-white admin_info_box_header">
                        <h2><b>Category</b></h2>
                    </div>
                    <div class="admin_info_box_body">
                            
                        <div class="admin_form">
                            
                            @include( 'manager/common/form/multiLangInput',['title'=>'Title','name'=>'title','values'=>$category->metas] )
                            
                            <div class="form-group">
                                <label class="mb-0">Url Slug</label>
                                <input id="slug_field" class="form-control" type="text" name="slug" placeholder="Url slug" onKeyup="slug_filter(this)" value="{{ $category->slug }}">
                                <span class="d-block small">{{ url('/category') }}<span id="slug_viewer">/{{ $category->slug }}</span></span>
                                @error('slug')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            @include( 'manager/common/form/upload',['title'=>'Image','name'=>'image','value'=>$category->image] )
                            
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>$category->status] )
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                @include( 'manager/common/metaTags', ['values'=>$category->metaTags] )
            </div>
        </div>

        <button class="btn btn-dark btn-lg mt-4" type="submit">Update</button>
    </form>
</div>
@endsection