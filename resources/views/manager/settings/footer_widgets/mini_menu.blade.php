<div class="mini_menu_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Menu area</h4>
    <?php 
        $mini_menu = isset($options['mini_menu'])?$options['mini_menu']:NULL;
    ?>
    <div class="form-group">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary {{ isset($mini_menu->show)&&$mini_menu->show=='true'? 'active':'' }}">
                <input type="radio" class="show_mini_menu" name="mini_menu[show]" value="true" {{ isset($mini_menu->show)&&$mini_menu->show=='true'? 'checked':'' }}> Show menu
            </label>
            <label class="btn btn-secondary {{ isset($mini_menu->show)&&$mini_menu->show=='false'? 'active':'' }}">
                <input type="radio" class="show_mini_menu" name="mini_menu[show]" value="false" {{ isset($mini_menu->show)&&$mini_menu->show=='false'? 'checked':'' }}> Show menu
            </label>
        </div>
    </div>
    <script>
        $(document).on('change','.show_mini_menu',function(e){
            $(this).parent().addClass('active');
            $(this).parent().siblings().removeClass('active');
            if(e.target.value=='true'){
                $('#mini_menu_container').show();
            }else{
                $('#mini_menu_container').hide();
            }
        })
    </script>

    <div class="mb-2" id="mini_menu_container" style="display: {{ isset($mini_menu->show)&&$mini_menu->show=='true'? 'block':'none' }};">
        <div class="row">
            <div class="col-md-6">
                @foreach(config_languages() as $lang => $language)
                <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                    <label">Contact line</label>
                    <input type="text" class="form-control" placeholder="Contact line" name="mini_menu[contact_line][{{$lang}}]" value="{{ isset($mini_menu->contact_line->$lang)?$mini_menu->contact_line->$lang:'' }}"/>
                </div>
                @endforeach
            </div>
            <div class="col-md-6">
                <div class="menus p-3">
                    <div id="menus" class="border p-3">
                        @if(isset($mini_menu->items)&&count($mini_menu->items))
                        @foreach($mini_menu->items as $item)
                        <?php 
                            $id = $loop->iteration-1;
                        ?>
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="mini_menu[items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="mini_menu[items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                            </div>
                        </div>
                        @endforeach 
                        @else
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="mini_menu[items][0][label][{{$lang}}]" />
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="mini_menu[items][0][url]" value type="text"/>
                            </div>
                        </div>
                        @endif
                        
                    </div>

                    
                    <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)">Add menu</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function addMenu(e){
            let id = $('#menus .menu_item').length;
            let html = '<div class="menu_item">';
            html += '<button type="button" class="remove">x</button>';
            @foreach(config_languages() as $lang => $language)
            html += '<div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">';
            html += '<label">Label</label>';
            html += '<input type="text" class="form-control" placeholder="Menu label" name="mini_menu[items]['+id+'][label]" />';
            html += '</div>';
            @endforeach
            html += '<div class="form-group mb-0">';
            html += '<input class="form-control" placeholder="https://example.com" name="mini_menu[items]['+id+'][url]" value type="text"/>';
            html += '</div>';
            html += '</div>';
            $('#menus').append(html);
        }
        $(document).on('click','#menus .remove',function(){
            $(this).parent().remove();
        })
    </script>
</div>