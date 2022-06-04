@extends('manager.common.layout')
@section('content')
<div class="categories">
    @include('manager.common.sessionMessage')

    <?php 
        $menus = isset($options['menus'])?$options['menus']:NULL;
    ?>

    <form action="{{ url('manager/settings/header') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Header settings</b></h2>
            </div>
            <div class="admin_info_box_body">
                <!-- @include( 'manager/common/form/textarea',['title'=>'Header scripts','name'=>'header_scripts','value'=>(isset($options['header_scripts'])?$options['header_scripts']:'')] ) -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="menus p-3">
                            <div id="menus" class="border p-3">
                                @if(isset($menus->items)&&count($menus->items))
                                @foreach($menus->items as $item)
                                <?php 
                                    $id = $loop->iteration-1;
                                ?>
                                <div class="menu_item">
                                    <button type="button" class="remove">x</button>
                                    @foreach(config_languages() as $lang => $language)
                                    <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                        <label">Label</label>
                                        <input type="text" class="form-control" placeholder="Menu label" name="menus[items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                                    </div>
                                    @endforeach
                                    <div class="form-group mb-0">
                                        <input class="form-control" placeholder="https://example.com" name="menus[items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                                    </div>
                                </div>
                                @endforeach 
                                @else
                                <div class="menu_item">
                                    <button type="button" class="remove">x</button>
                                    @foreach(config_languages() as $lang => $language)
                                    <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                        <label">Label</label>
                                        <input type="text" class="form-control" placeholder="Menu label" name="menus[items][0][label][{{$lang}}]" />
                                    </div>
                                    @endforeach
                                    <div class="form-group mb-0">
                                        <input class="form-control" placeholder="https://example.com" name="menus[items][0][url]" value type="text"/>
                                    </div>
                                </div>
                                @endif
                                
                            </div>
                            <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)">Add menu</button>
                        </div>
                        <script>
                            function addMenu(e){
                                let id = $('#menus .menu_item').length;
                                let html = '<div class="menu_item">';
                                html += '<button type="button" class="remove">x</button>';
                                @foreach(config_languages() as $lang => $language)
                                html += '<div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">';
                                html += '<label">Label</label>';
                                html += '<input type="text" class="form-control" placeholder="Menu label" name="menus[items]['+id+'][label][{{ $lang }}]" />';
                                html += '</div>';
                                @endforeach
                                html += '<div class="form-group mb-0">';
                                html += '<input class="form-control" placeholder="https://example.com" name="menus[items]['+id+'][url]" value type="text"/>';
                                html += '</div>';
                                html += '</div>';
                                $('#menus').append(html);
                            }
                            $(document).on('click','#menus .remove',function(){
                                $(this).parent().remove();
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-dark btn-lg mt-4" type="submit">Save settings</button>

        <!-- <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div> -->
    </form>
</div>
@endsection