@extends('manager.common.layout')
@section('content')
<div class="create_category">

    <form action="{{ url('/manager/category/save') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $model->id }}">
        <div class="row">
            <div class="col-md-6">
                <div class="bg-gray admin_info_box">
                    <div class="bg-info text-white admin_info_box_header">
                        <h2><b>Model</b></h2>
                    </div>
                    <div class="admin_info_box_body">
                            
                        <div class="admin_form">
                            @include( 'manager/common/form/input',['title'=>'Model','name'=>'model','value'=>$model->model] )
                            <?php 
                                $options = array();
                                if(isset($brands)&&count($brands)){
                                    foreach($brands as $brand){
                                        $options[$brand->id] = $brand->meta->title;
                                    }
                                }
                            ?>
                            @include( 'manager/common/form/select',['title'=>'Brand','name'=>'brand','options'=>$options,'value'=>$model->brand_id] )
                            
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>$model->status] )
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-dark btn-lg mt-4" type="submit">Update model</button>
    </form>

</div>
@endsection