@extends('manager.common.layout')
@section('content')
<div class="contacts">

    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Contacts</b></h2>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/contacts') }}" class="row">
                <div class="col-md-8">
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
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Email</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Mobile</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Subject</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($contacts->total())
                            @foreach($contacts as $contact)
                                <tr id="contact_{{ $contact->id }}">
                                    <td>
                                        <input type="checkbox" name="contacts" value="{{ $contact->id }}">
                                    </td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ $contact->mobile }}</td>
                                    <td>{{ $contact->subject }}</td>
                                    <td>{{ date('d F, Y', strtotime($contact->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/contacts/'.$contact->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/view_icon.svg') }}" alt="edit" title="Edit {{ $contact->name }}">
                                        </a>
                                        <a href="{{ url('/manager/contacts/delete/'.$contact->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $contact->name }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    No contact found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $contacts])
</div>
@endsection