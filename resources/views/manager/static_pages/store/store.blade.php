@extends('manager.common.layout')
@section('content')
<div class="pages">

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show mt-4 alert-custom"
            role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ url('/manager/static-pages/store') }}" method="post" novalidate>
        @csrf

        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Store page</b></h2>
            </div>
            <div class="admin_info_box_body">
                <div class="row">
                    <div class="col-md-10">
                        <!-- Banner -->
                        <div class="widgets_items banner_widget">
                            @include('/manager/static_pages/store/banner')
                        </div>
                        <!-- End banner -->
                    </div>
                </div>
            </div>
        </div>

        @include('/manager/common/metaTags',['values'=>$meta_tags])

        <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection