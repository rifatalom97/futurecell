@extends('manager.common.layout')
@section('content')
<div class="categories">
    
    @include('manager.common.sessionMessage')

    <form action="{{ url('manager/settings/footer') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Footer settings</b></h2>
            </div>
            <div class="admin_info_box_body">
                <div class="widgets_items sequirites_widget">
                    @include('manager.settings.footer_widgets.securities')
                </div>
                
                <div class="widgets_items menus_widget">
                    @include('manager.settings.footer_widgets.menus')
                </div>

                <div class="widgets_items delivery_widget">
                    @include('manager.settings.footer_widgets.delivery')
                </div>

                <div class="widgets_items copyright_widget">
                    @include('manager.settings.footer_widgets.copyright')
                </div>

            </div>
        </div>

        <!-- <button class="btn btn-dark btn-lg mt-4" type="submit">Save settings</button> -->

        <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection