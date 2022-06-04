@extends('manager.common.layout')
@section('content')
<div class="customers">
    
    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Customers</b></h2>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/customers') }}" class="mb-4 row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            @include( 'manager/common/form/input',['name'=>'name','placeholder'=>"Search by name",'value'=>old('name')] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/input',['name'=>'email','placeholder'=>"Search by email",'value'=>old('email')] )
                        </div>
                        <div class="col-md-4">
                            @include( 'manager/common/form/input',['name'=>'mobile','placeholder'=>"Search by mobile",'value'=>old('mobile')] )
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
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($customers->total())
                            @foreach($customers as $customer)
                                <tr id="admin_{{ $customer->id }}">
                                    <td>
                                        <input type="checkbox" name="customers" value="{{ $customer->id }}">
                                    </td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->city }}</td>
                                    <td>{{ $customer->mobile }}</td>
                                    <td>{{ ($customer->status==1?'Active':'Not-active') }}</td>
                                    <td>{{ date('d F, Y', strtotime($customer->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/products/customers/'.$customer->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $customer->name }}">
                                        </a>
                                        <a href="{{ url('/manager/products/customers/delete/'.$customer->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $customer->name }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    No customers found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $customers])
</div>
@endsection