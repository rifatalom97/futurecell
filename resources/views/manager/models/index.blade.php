@extends('manager.common.layout')
@section('content')
<div class="models">
    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Models</b></h2>
            <div class="title_right">
                <a class="btn btn-md btn-dark" href="{{ url('/manager/models/add') }}">Add new model</a>
            </div>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/models') }}" class="mb-4 row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-4">
                            @include( 'manager/common/form/input',['name'=>'title','placeholder'=>"Search by title",'value'=>old('title')] )
                        </div>
                        <div class="col-md-4">
                            <?php 
                                $options = array();
                                if(isset($brands)&&count($brands)){
                                    foreach($brands as $brandItem){
                                        $options[$brandItem->id] = $brandItem->meta->title;
                                    }
                                }
                            ?>
                            @include( 'manager/common/form/select',['name'=>'brand','placeholder'=>'Search by brand','options'=>$options,'value'=>old('brand')] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/select',['name'=>'status','placeholder'=>'Search by status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>old('status')] )
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                   <button type="submit" class="btn btn-dark">Search</button>
                </div>
            </form>

            <div class="all_pages table_container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="sort_button">#</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="title">Model</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="brand">Brand</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="email">Status</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($models->total())
                            @foreach($models as $model)
                                <?php 
                                    $title = $model->meta? $model->meta->title:'';
                                ?>
                                <tr id="admin_{{ $model->id }}">
                                    <td>
                                        <!-- <input type="checkbox" name="models" value="{{ $model->id }}"> -->
                                        {{ ($loop->iteration<10?'0'.$loop->iteration:$loop->iteration) }}
                                    </td>
                                    <td>{{ $title }}</td>
                                    <td>{{ $model->brand?$model->brand->meta->title:'' }}</td>
                                    <td>{{ $model->status==1?'Active':'Draft' }}</td>
                                    <td>{{ date('d F, Y', strtotime($model->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/models/'.$model->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $title }}">
                                        </a>
                                        <a href="{{ url('/manager/models/delete/'.$model->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $title }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    No model found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $models])
</div>
@endsection