@extends('manager.common.layout')
@section('content')
<div class="admins">
    
    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Admins</b></h2>
            <div class="title_right">
                <a class="btn btn-md btn-dark" href="{{ url('/manager/admins/create') }}">Add new admin</a>
            </div>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/admins') }}" class="mb-4 row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" type="search" name="name" placeholder="Search by name" value="{{ (isset($_GET['name'])?$_GET['name']:false) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" type="email" name="email" placeholder="Search by email" value="{{ (isset($_GET['email'])?$_GET['email']:false) }}">
                            </div>
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
                            <th class="sort_button" data-sortorder="DESC" data-sortby="email">Email</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="city">City</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="mobile">Mobile</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="adminRole">Role</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($admins->total())
                            @foreach($admins as $admin)
                                <tr id="admin_{{ $admin->id }}">
                                    <td>
                                        <input type="checkbox" name="admins" value="{{ $admin->id }}">
                                    </td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->city }}</td>
                                    <td>{{ $admin->mobile }}</td>
                                    <td>{{ $admin->role }}</td>
                                    <td>{{ ($admin->status==1?'Active':'Not-active') }}</td>
                                    <td>{{ date('d F, Y', strtotime($admin->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/admins/edit/'.$admin->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $admin->name }}">
                                        </a>
                                        <a href="{{ url('/manager/admins/delete/'.$admin->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $admin->name }}">
                                        </a>
                                        <a href="{{ url('/manager/admins/suspend/'.$admin->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="suspend" title="Suspend {{ $admin->name }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    No admin found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $admins])
</div>
@endsection