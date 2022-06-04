@extends('manager.common.layout')
@section('content')
<div class="subscribers">

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Contact Message</b></h2>

            <div class="title_right">
                <b>Date:</b> {{ date('d F, Y',strtotime($contact->created_at)) }}
            </div>
        </div>
        <div class="admin_info_box_body">
            <div class="all_pages table_container">
                <table class="table">
                    <tbody>
                        <tr>
                            <th><b>Name</b></th>
                            <td>{{ $contact->name }}</td>
                        </tr>
                        <tr>
                            <th><b>Email</b></th>
                            <td>{{ $contact->email }}</td>
                        </tr>
                        <tr>
                            <th><b>Mobile</b></th>
                            <td>{{ $contact->mobile }}</td>
                        </tr>
                        <tr>
                            <th><b>Subject</b></th>
                            <td>{{ $contact->subject }}</td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>Message</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">{{ $contact->message }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection