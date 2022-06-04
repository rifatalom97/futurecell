@extends('manager.common.layout')
@section('content')
<div class="brands">
    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Brands</b></h2>
            <div class="title_right">
                <a class="btn btn-md btn-dark" href="{{ url('/manager/brands/add') }}">Add new brand</a>
            </div>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/brands') }}" class="mb-4 row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            @include( 'manager/common/form/input',['name'=>'title','placeholder'=>"Search by title",'value'=>old('title')] )
                        </div>
                        <div class="col-md-6">
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
                            <th class="sort_button" data-sortorder="DESC" data-sortby="name">Name</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="email">Status</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($brands->total())
                            @foreach($brands as $brand)
                                <?php 
                                    $title = $brand->meta? $brand->meta->title:'';
                                ?>
                                <tr id="admin_{{ $brand->id }}">
                                    <td>
                                        <!-- <input type="checkbox" name="brands" value="{{ $brand->id }}"> -->
                                        {{ ($loop->iteration<10?'0'.$loop->iteration:$loop->iteration) }}
                                    </td>
                                    <td>{{ $title }}</td>
                                    <td>{{ $brand->status==1?'Active':'Draft' }}</td>
                                    <td>{{ date('d F, Y', strtotime($brand->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/brands/'.$brand->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $title }}">
                                        </a>
                                        <a href="{{ url('/manager/brands/delete/'.$brand->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $title }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    No brand found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $brands])
</div>
@endsection