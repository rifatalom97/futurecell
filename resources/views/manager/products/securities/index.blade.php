@extends('manager.common.layout')
@section('content')
<div class="securities">

    @include('manager.common.sessionMessage')

    <div class="row">
        <div class="col-md-4">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Details</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <form action="{{ url('/manager/products/securities') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ isset($security->id)?$security->id:0 }}">

                        @include( 'manager/common/form/upload',['title'=>'Image','name'=>'image','value'=>(isset($security->image)?$security->image:'')] )

                        @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>['1'=>'Active','0'=>'Draft'],'value'=>(isset($security->status)?$security->status:old('status'))] )

                        <button type="submit" class="btn btn-dark">Save security</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Sizes</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <div class="all_pages table_container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="sort_button">#</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="name">Title</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                                    <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($securities->total())
                                    @foreach($securities as $security)
                                        <tr id="security_{{ $security->id }}">
                                            <td>
                                                <input type="checkbox" name="securities[]" value="{{ $security->id }}">
                                            </td>
                                            <td>
                                                @if($security->image)
                                                    <img src="{{ asset('/uploads/'.$security->image) }}" style="max-width:200px;">
                                                @endif
                                            </td>
                                            <td>{{ $security->status?'Active':'Draft' }}</td>
                                            <td>{{ date('d F, Y', strtotime($security->created_at)) }}</td>
                                            <td class="text-center action_buttons">
                                                <a href="{{ url('/manager/products/securities/'.$security->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit">
                                                </a>
                                                <a href="{{ url('/manager/products/securities/delete/'.$security->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            No product security found
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @include('manager.common.paggination', ['paginator' => $securities])
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection