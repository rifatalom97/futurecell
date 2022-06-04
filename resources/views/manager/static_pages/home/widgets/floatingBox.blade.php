<div class="flt_boxes_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Floating box</h4>
    <?php 
        $floating_boxes = $home_data['floating_boxes'];
    ?>
    <div class="flt_boxes_container">
        <div class="floating_boxes" id="floating_boxes">
            @if(isset($floating_boxes)&&count($floating_boxes))
                @foreach($floating_boxes as $item)
                <?php 
                    $id = $loop->iteration-1;
                ?>
                <div class="fltbx_item mb-2" id="fltbx_item_{{ $id }}">
                    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Floating Item #{{ ($id+1) }}</h4>
                    <button type="button" class="remove_file">x</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fltbx_item_details">
                                <div class="form-group">
                                    <label class="">Title</label>
                                    @foreach(config_languages() as $lang => $language)
                                    <input type="text" class="form-control" name="floating_boxes[{{ $id }}][title][{{$lang}}]" value="{{ isset($item->title->$lang)?$item->title->$lang:'' }}"/>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label class="">Details</label>
                                    @foreach(config_languages() as $lang => $language)
                                    <textarea class="form-control" name="floating_boxes[{{ $id }}][details][{{$lang}}]">{{ isset($item->details->$lang)?$item->details->$lang:'' }}</textarea>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input class="show_flt_box_button" id="0" type="checkbox" name="floating_boxes[{{ $id }}][button][show]" value="1" {{ isset($item->button->show)&&$item->button->show?'checked':'' }}/>
                                        Show button
                                    </label>
                                </div>
                                <div id="flt_box_button_0" style="display:{{ isset($item->button->show)&&$item->button->show?'block':'none' }}">
                                    <div class="form-group">
                                        <label class="">Button text</label>
                                        @foreach(config_languages() as $lang => $language)
                                        <input type="text" class="form-control" name="floating_boxes[{{ $id }}][button][text][{{$lang}}]" value="{{ isset($item->button->text->$lang)?$item->button->text->$lang:'' }}"/>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label class="">Button url</label>
                                        <input class="form-control" type="url" name="floating_boxes[{{ $id }}][button][url]" value="{{ isset($item->button->url)?$item->button->url:'' }}" placeholder="https://example.com"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fltbx_item_image">
                                <label>Image</label>
                                <input name="file" type="file" accept="image/*" size="2000"/>
                                <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                                <input class="file" type="hidden" name="floating_boxes[{{ $id }}][image]" value="{{ $item->image }}"/>
                                <input class="old_file" type="hidden" name="floating_boxes[{{ $id }}][oldImage]" value="{{ $item->oldImage }}"/>
                                <img src="{{ asset('/uploads/'.$item->image) }}" class="viewer" style="display:{{ $item->oldImage?'block':'none' }};"/>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else 
                <div class="fltbx_item mb-2" id="fltbx_item_0">
                    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Floating Item #1</h4>
                    <button type="button" class="remove_file">x</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fltbx_item_details">
                                <div class="form-group">
                                    <label class="">Title</label>
                                    @foreach(config_languages() as $lang => $language)
                                    <input type="text" class="form-control" name="floating_boxes[0][title][{{$lang}}]"/>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label class="">Details</label>
                                    @foreach(config_languages() as $lang => $language)
                                    <textarea class="form-control" name="floating_boxes[0][details][{{$lang}}]"></textarea>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>
                                        <input class="show_flt_box_button" id="0" type="checkbox" name="floating_boxes[0][button][show]" value="1"/>
                                        Show button
                                    </label>
                                </div>
                                <div id="flt_box_button_0" style="display:none">
                                    <div class="form-group">
                                        <label class="">Button text</label>
                                        @foreach(config_languages() as $lang => $language)
                                        <input type="text" class="form-control" name="floating_boxes[0][button][text][{{$lang}}]"/>
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label class="">Button url</label>
                                        <input class="form-control" type="url" name="floating_boxes[0][button][url]" value="" placeholder="https://example.com"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fltbx_item_image">
                                <label>Image</label>
                                <input name="file" type="file" accept="image/*" size="2000"/>
                                <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                                <input class="file" type="hidden" name="floating_boxes[0][image]" value=""/>
                                <input class="old_file" type="hidden" name="floating_boxes[0][oldImage]" value=""/>
                                <img src="" class="viewer" style="display:none;"/>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <button class="btn btn-dark mt-2" type="button" onClick="addExpert(this)">Add box</button>
    </div>

    <script>
        function addExpert(e){
            let id = $('#expertise .expert').length;
            let html = '<div class="fltbx_item mb-2" id="fltbx_item_'+id+'">';
                    html += '<h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Floating Item #'+id+'</h4>';
                    html += '<button type="button" class="remove_file">x</button>';
                    html += '<div class="row">';
                        html += '<div class="col-md-6">';
                            html += '<div class="fltbx_item_details">';
                                html += '<div class="form-group">';
                                    html += '<label class="">Title</label>';
                                    @foreach(config_languages() as $lang => $language)
                                    html += '<input type="text" class="form-control" name="floating_boxes['+id+'][title][{{$lang}}]"/>';
                                    @endforeach
                                html += '</div>';
                                html += '<div class="form-group">';
                                    html += '<label class="">Details</label>';
                                    @foreach(config_languages() as $lang => $language)
                                    html += '<textarea class="form-control" name="floating_boxes['+id+'][details][{{$lang}}]"></textarea>';
                                    @endforeach
                                html += '</div>';
                                html += '<div class="form-group">';
                                    html += '<label>';
                                    html += '<input class="show_flt_box_button" id="'+id+'" type="checkbox" name="floating_boxes[0][button][show]" value="1"/>';
                                    html += 'Show button';
                                    html += '</label>';
                                html += '</div>';
                                html += '<div id="flt_box_button_'+id+'" style="display:none">';
                                    html += '<div class="form-group">';
                                        html += '<label class="">Button text</label>';
                                        @foreach(config_languages() as $lang => $language)
                                        html += '<input type="text" class="form-control" name="floating_boxes['+id+'][button][text][{{$lang}}]"/>';
                                        @endforeach
                                    html += '</div>';
                                    html += '<div class="form-group">';
                                        html += '<label class="">Button url</label>';
                                        html += '<input class="form-control" type="url" name="floating_boxes['+id+'][button][url]" value="" placeholder="https://example.com"/>';
                                    html += '</div>';
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-6">';
                            html += '<div class="fltbx_item_image">';
                                html += '<label>Image</label>';
                                html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                html += '<input class="file" type="hidden" name="floating_boxes['+id+'][image]" value=""/>';
                                html += '<input class="old_file" type="hidden" name="floating_boxes['+id+'][oldImage]" value=""/>';
                                html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            $('#flt_boxes').append(html);
        }
        $(document).on('change','.fltbx_item_image input[type="file"]',function(e){
            let pn = $(e.target.parentElement);
            if(e.target.value){
                let formData = new FormData();
                formData.append('uploading_file',e.target.files[0]);
                pn.addClass('uploading');
                $.ajax({
                    headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"  // must need for file upload
                    },
                    url:    '{{ url("/manager/ajaxFileUpload") }}',
                    method: 'POST', // must need for file upload
                    data:   formData,
                    dataType: 'JSON',
                    contentType:false, // must need for file upload
                    processData:false, // must need for file upload
                    cache:false, // must need for file upload
                    mimeType:'multipart/form-data', // must need for file upload
                    success:function(r){
                        if(r.result){
                            pn.find('.file').val(r.filename);
                            pn.find('img.viewer').attr('src',r.fileurl);
                            pn.find('img.viewer').show();
                        }
                        $(e.target).val(null);
                        pn.removeClass('uploading');

                        // console.log(r);
                    },
                    error:function(r){
                        $(e.target).val(null);
                        pn.removeClass('uploading');
                        console.log(r);
                    }
                });
            }
        });
        $(document).on('click','.fltbx_item .remove_file',function(e){
            let pn = $(e.target.parentElement);
            if(confirm("Are you sure want to remove this item?")){
                pn.find('.file').val('');
                pn.find('.fltbx_item img.viewer').attr('src','');
                pn.hide();
            }
        });
        $(document).on('click','.show_flt_box_button',function(e){
            let pn = $(e.target);
            let f = $(document).find('#flt_box_button_'+pn.attr('id'));
            if( e.target.checked ){
                f.show();
            }else{
                f.hide();
            }
        });
    </script>
</div>