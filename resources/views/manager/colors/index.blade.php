@extends('manager.common.layout')
@section('content')
<div class="colors">

    @include('manager.common.sessionMessage')

    <div class="row">
        <div class="col-md-6">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Size details</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <form action="{{ url('/manager/colors/save') }}" class="row" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ isset($color->id)?$color->id:0 }}">
                        <div class="col-md-4">
                        @include( 'manager/common/form/multiLangInput',['title'=>'Color title','name'=>'title','values'=>(isset($color->metas)?$color->metas:NULL)] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/input',['type'=>'color','title'=>'Color','name'=>'code','value'=>(isset($color->code)?$color->code:old('code'))] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>['1'=>'Active','0'=>'Draft'],'value'=>(isset($color->status)?$color->status:old('status'))] )
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-dark">Save color</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
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
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="name">title</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="name">Color</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                                    <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($colors->total())
                                    @foreach($colors as $color)
                                        <tr id="color_{{ $color->id }}">
                                            <td>
                                                <input type="checkbox" name="colors" value="{{ $color->id }}">
                                            </td>
                                            <td>{{ $color->meta->title }}</td>
                                            <td>
                                                <div class="color_box" style="width: 50px;height:50px;display:block;background-color: {{ $color->code }}"></div>
                                            </td>
                                            <td>{{ $color->status?'Active':'Draft' }}</td>
                                            <td>{{ date('d F, Y', strtotime($color->created_at)) }}</td>
                                            <td class="text-center action_buttons">
                                                <a href="{{ url('/manager/colors/'.$color->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $color->meta->title }}">
                                                </a>
                                                <a href="{{ url('/manager/colors/delete/'.$color->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $color->meta->title }}">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            No color found to show
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @include('manager.common.paggination', ['paginator' => $colors])
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection