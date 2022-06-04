@extends('manager.common.layout')
@section('content')
<div class="pages">
    <form action="{{ url('/manager/static-pages/about') }}" method="post">    
        @csrf 

        @include('manager.common.sessionMessage')

        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>About</b></h2>
            </div>
            <div class="admin_info_box_body">
                @include('/manager/common/form/multiLangInput',['type'=>'text','title'=>'Title','name'=>'title','values'=>( isset($options['title'])?$options['title']:'' )])
                
                @include('/manager/common/form/multiLangInput',['type'=>'textarea','tinymce'=>true,'name'=>'content','values'=>( isset($options['content'])?$options['content']:'' )])
            </div>
        </div>

        @include('/manager/common/metaTags',['values'=>$meta_tags])

        <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection