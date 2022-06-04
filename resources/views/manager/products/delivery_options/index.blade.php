@extends('manager.common.layout')
@section('content')
<div class="colors">

    @include('manager.common.sessionMessage')

    <div class="row">
        <div class="col-md-4">
            <div class="bg-gray admin_info_box">
                <div class="bg-info text-white admin_info_box_header">
                    <h2><b>Details</b></h2>
                </div>
                <div class="admin_info_box_body">
                    <form action="{{ url('/manager/products/delivery-options/save') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ isset($method->id)?$method->id:0 }}">
                        @include( 'manager/common/form/multiLangInput',['title'=>'Title','name'=>'title','values'=>(isset($method->metas)?$method->metas:NULL)] )
                        @include( 'manager/common/form/input',['type'=>'number','title'=>'Amount','name'=>'amount','value'=>(isset($method->amount)?$method->amount:old('amount'))] )
                        @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','placeholder'=>"Select status",'options'=>['1'=>'Active','0'=>'Draft'],'value'=>(isset($method->status)?$method->status:old('status'))] )

                        <button type="submit" class="btn btn-dark">Save method</button>
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
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="name">Amount</th>
                                    <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                                    <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($methods->total())
                                    @foreach($methods as $method)
                                        <tr id="method_{{ $method->id }}">
                                            <td>
                                                <input type="checkbox" name="delivery_options[]" value="{{ $method->id }}">
                                            </td>
                                            <td>{{ $method->meta->title }}</td>
                                            <td>
                                                {{ $method->amount }}
                                            </td>
                                            <td>{{ $method->status?'Active':'Draft' }}</td>
                                            <td>{{ date('d F, Y', strtotime($method->created_at)) }}</td>
                                            <td class="text-center action_buttons">
                                                <a href="{{ url('/manager/products/delivery-options/'.$method->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $method->meta->title }}">
                                                </a>
                                                <a href="{{ url('/manager/products/delivery-options/delete/'.$method->id) }}">
                                                    <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $method->meta->title }}">
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">
                                            No method found to show
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @include('manager.common.paggination', ['paginator' => $methods])
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection