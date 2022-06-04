@extends('manager.common.layout')
@section('content')
<div class="subscribers">

    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Subscribers</b></h2>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/subscribers') }}" class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            @include( 'manager/common/form/input',['name'=>'email','placeholder'=>"Search by email",'value'=>old('email')] )
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
                            <th class="sort_button" data-sortorder="DESC" data-sortby="email">Email</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($subscribers->total())
                            @foreach($subscribers as $subscriber)
                                <tr id="subscriber_{{ $subscriber->id }}">
                                    <td>
                                        <input type="checkbox" name="subscribers" value="{{ $subscriber->id }}">
                                    </td>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>{{ ($subscriber->status==1?'Active':'Not-active') }}</td>
                                    <td>{{ date('d F, Y', strtotime($subscriber->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/subscribers/change/'.$subscriber->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/view_icon.svg') }}" alt="edit" title="Edit {{ $subscriber->email }}">
                                        </a>
                                        <a href="{{ url('/manager/subscribers/delete/'.$subscriber->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $subscriber->email }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    No subscriber found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $subscribers])
</div>
@endsection