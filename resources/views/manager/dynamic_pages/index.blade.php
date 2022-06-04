@extends('manager.common.layout')
@section('content')
<div class="pages">

    @include('manager.common.sessionMessage')

    <div class="bg-gray admin_info_box">
        <div class="bg-info text-white admin_info_box_header">
            <h2><b>Pages</b></h2>
            <div class="title_right">
                <a class="btn btn-md btn-dark" href="{{ url('/manager/pages/create') }}">Add new page</a>
            </div>
        </div>
        <div class="admin_info_box_body">
            <form action="{{ url('/manager/pages') }}" class="mb-4 row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" type="search" name="search" placeholder="Search title" value="{{ (isset($_GET['search'])?$_GET['search']:false) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div>
                                    <select name="status" class="form-control">
                                        <option value="">Choose status</option>
                                        <option value="1" {{ (isset($_GET['status'])?'selected':false) }}>Active</option>
                                        <option value="0" {{ (isset($_GET['status'])?'selected':false) }}>Deactive</option>
                                    </select>
                                </div>
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
                            <th class="sort_button" data-sortorder="DESC" data-sortby="title">Pages title</th>
                            <th class="sort_button" data-sortorder="DESC" data-sortby="status">Status</th>
                            <th class="sort_button" data-sortorder="ASC" data-sortby="created_at">Added On</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($pages->total())
                            @foreach($pages as $page)
                                <tr id="page_{{ $page->id }}">
                                    <td>
                                        <input type="checkbox" name="pages" value="{{ $page->id }}">
                                    </td>
                                    <td>{{ $page->meta?$page->meta->title:'' }}</td>
                                    <td>{{ ($page->status==1?'Active':'Draft') }}</td>
                                    <td>{{ date('d F, Y', strtotime($page->created_at)) }}</td>
                                    <td class="text-center action_buttons">
                                        <a href="{{ url('/manager/pages/'.$page->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/edit_icon.svg') }}" alt="edit" title="Edit {{ $page->meta?$page->meta->title:'' }}">
                                        </a>
                                        <a href="{{ url('/manager/pages/delete/'.$page->id) }}">
                                            <img class="ml-3 mr-3" src="{{ asset('/assets/admin/img/delete_icon.svg') }}" alt="delete" title="Delete {{ $page->meta?$page->meta->title:'' }}">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="page_3">
                                <td colspan="5">
                                    No page found to show
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('manager.common.paggination', ['paginator' => $pages])
</div>
@endsection