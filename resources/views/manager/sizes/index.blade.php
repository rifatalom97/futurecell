@extends('manager.common.layout')
@section('content')
<div class="sizes">

    @include('manager.common.sessionMessage')

    <div class="row">
        <div class="col-md-6">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Size details</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <form action="{{ url('/manager/sizes/save') }}" class="row" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ isset($size->id)?$size->id:0 }}">
                        <div class="col-md-4">
                            @include( 'manager/common/form/input',['type'=>'number','title'=>'Value','name'=>'value','placeholder'=>"Size value",'value'=>(isset($size->value)?$size->value:old('value'))] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/select',['title'=>'Unite','name'=>'unite','placeholder'=>"Select unite",'options'=>['KG'=>'KG','GM'=>'GM','M'=>'M','CM'=>'CM','MM'=>'MM','L'=>'L'],'value'=>(isset($size->unite)?$size->unite:old('unite'))] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>['1'=>'Active','0'=>'Draft'],'value'=>(isset($size->status)?$size->status:old('status'))] )
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-dark">Save size</button>
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
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="name">Unite value</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                                    <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($sizes->total())
                                    @foreach($sizes as $size)
                                        <tr id="size_{{ $size->id }}">
                                            <td>
                                                <input type="checkbox" name="sizes" value="{{ $size->id }}">
                                            </td>
                                            <td>{{ $size->value . $size->unite }}</td>
                                            <td>{{ $size->status?'Active':'Draft' }}</td>
                                            <td>{{ date('d F, Y', strtotime($size->created_at)) }}</td>
                                            <td class="text-center action_buttons">
                                                <a href="{{ url('/manager/sizes/'.$size->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/view_icon.svg') }}" alt="edit" title="Edit {{ $size->name }}">
                                                </a>
                                                <a href="{{ url('/manager/sizes/delete/'.$size->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $size->name }}">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            No size found to show
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @include('manager.common.paggination', ['paginator' => $sizes])
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection