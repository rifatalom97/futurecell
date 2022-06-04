<div class="menus_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Menu area</h4>
    <?php 
        $menus = isset($options['menus'])?$options['menus']:NULL;
    ?>
    <div class="mb-2" id="footer_menus_container" style="display: block;">
        <div class="row">

            <!-- Group 1 -->
            <div class="col-md-2">
                <div class="menus">
                    <div id="menus1" class="border">
                        @if(isset($menus->group1->items)&&count($menus->group1->items))
                        <?php $id = 0;?>
                        @foreach($menus->group1->items as $item)
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="menus[group1][items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="menus[group1][items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                            </div>
                        </div>
                        <?php $id++; ?>
                        @endforeach
                        @endif
                    </div>                   
                    <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)" data-group="group1">Add menu</button>
                </div>
            </div><!-- Group1 -->
            
            <!-- Group2 -->
            <div class="col-md-2">
                <div class="menus">
                    <div id="menus2" class="border">
                        @if(isset($menus->group2->items)&&count($menus->group2->items))
                        <?php $id = 0; ?>
                        @foreach($menus->group2->items as $item)
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="menus[group2][items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="menus[group2][items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                            </div>
                        </div>
                        <?php $id++; ?>
                        @endforeach
                        @endif
                    </div>                   
                    <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)" data-group="group2">Add menu</button>
                </div>
            </div><!-- Group2 -->
            
            <!-- Group3 -->
            <div class="col-md-2">
                <div class="menus">
                    <div id="menus3" class="border">
                        @if(isset($menus->group3->items)&&count($menus->group3->items))
                        <?php $id = 0; ?>
                        @foreach($menus->group3->items as $item)
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="menus[group3][items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="menus[group3][items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                            </div>
                        </div>
                        <?php $id++; ?>
                        @endforeach
                        @endif
                    </div>                   
                    <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)" data-group="group3">Add menu</button>
                </div>
            </div><!-- Group3 -->
            
            <!-- Group4 -->
            <div class="col-md-2">
                <div class="menus">
                    <div id="menus4" class="border">
                        @if(isset($menus->group4->items)&&count($menus->group4->items))
                        <?php $id = 0; ?>
                        @foreach($menus->group4->items as $item)
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="menus[group4][items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="menus[group4][items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                            </div>
                        </div>
                        <?php $id++; ?>
                        @endforeach
                        @endif
                    </div>                   
                    <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)" data-group="group4">Add menu</button>
                </div>
            </div><!-- Group4 -->

            <!-- Group5 -->
            <div class="col-md-2">
                <div class="menus">
                    <div id="menus5" class="border">
                        @if(isset($menus->group5->items)&&count($menus->group5->items))
                        <?php $id = 0; ?>
                        @foreach($menus->group5->items as $item)
                        <div class="menu_item">
                            <button type="button" class="remove">x</button>
                            @foreach(config_languages() as $lang => $language)
                            <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                                <label">Label</label>
                                <input type="text" class="form-control" placeholder="Menu label" name="menus[group5][items][{{ $id }}][label][{{$lang}}]" value="{{ isset($item->label->$lang)?$item->label->$lang:'' }}"/>
                            </div>
                            @endforeach
                            <div class="form-group mb-0">
                                <input class="form-control" placeholder="https://example.com" name="menus[group5][items][{{ $id }}][url]" value="{{ $item->url?:'' }}" type="text"/>
                            </div>
                        </div>
                        <?php $id++; ?>
                        @endforeach
                        @endif
                        
                    </div>
                    <button class="btn btn-dark mt-2" type="button" onClick="addMenu(this)" data-group="group5">Add menu</button>
                </div>
            </div><!-- Group5 -->

        </div>
    </div>
    <script>
        function addMenu(e){
            let group   = e.dataset['group'];
            let id      = $(e).parent().find('.menu_item').length;
            
            let html    = '<div class="menu_item">';
            html += '<button type="button" class="remove">x</button>';
            @foreach(config_languages() as $lang => $language)
            html += '<div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">';
            html += '<label">Label</label>';
            html += '<input type="text" class="form-control" placeholder="Menu label" name="menus['+group+'][items]['+id+'][label][{{ $lang }}]" />';
            html += '</div>';
            @endforeach
            html += '<div class="form-group mb-0">';
            html += '<input class="form-control" placeholder="https://example.com" name="menus['+group+'][items]['+id+'][url]" value type="text"/>';
            html += '</div>';
            html += '</div>';
            $(e).parent().find('.border').append(html);
        }
        $(document).on('click','.menus .remove',function(){
            $(this).parent().remove();
        })
    </script>
</div>