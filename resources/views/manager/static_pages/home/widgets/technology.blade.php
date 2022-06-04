<div class="technology_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Our technology</h4>
    <?php 
        $technology = $home_data['technology'];
    ?>
    <div class="technology_container">

        @foreach(config_languages() as $lang => $language)
        <div class="{{ $lang }}" style="display:{{ $lang==config_lang()?'block':'none' }}">
            <div class="form-group">
                <label for="">Title</label>
                <input class="form-control" name="technology[title][{{$lang}}]" value="{{ isset($technology->title->$lang)?$technology->title->$lang:'' }}"/>
            </div>
        </div>
        @endforeach

        <div class="technology" id="technology">
            @if(isset($technology->items) && count($technology->items))
            @foreach($technology->items as $item)
            <?php 
                $id = $loop->iteration-1;
            ?>
            <div class="technology_item mb-2" id="technology_{{ $id }}">
                <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Technology #{{ ($id+1) }}</h4>
                <button type="button" class="remove_file">x</button>
                <div class="row">
                    <div class="col-md-6">
                        <div class="technology_item_details">
                            <div class="form-group">
                                <label>Title</label>
                                @foreach(config_languages() as $lang => $language)
                                <input class="form-control" name="technology[items][{{ $id }}][title][{{$lang}}]" value="{{ isset($item->title->$lang)?$item->title->$lang:'' }}" type="text"/>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label>Details</label>
                                @foreach(config_languages() as $lang => $language)
                                <textarea class="form-control" name="technology[items][{{ $id }}][details][{{$lang}}]">{{ isset($item->details->$lang)?$item->details->$lang:'' }}</textarea>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="technology_item_image">
                            <label>Image</label>
                            <input name="file" type="file" accept="image/*" size="2000"/>
                            <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                            <input class="file" type="hidden" name="technology[items][{{ $id }}][image]" value="{{ $item->image }}"/>
                            <input class="old_file" type="hidden" name="technology[items][{{ $id }}][oldImage]" value="{{ $item->oldImage }}"/>
                            <img src="{{ asset('/uploads/'.$item->image) }}" class="viewer" style="display:{{ $item->oldImage?'block':'none' }};"/>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @else 
            <div class="technology_item mb-2" id="technology_0">
                <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Technology #1</h4>
                <button type="button" class="remove_file">x</button>
                <div class="row">
                    <div class="col-md-6">
                        <div class="technology_item_details">
                            <div class="form-group">
                                <label>Title</label>
                                @foreach(config_languages() as $lang => $language)
                                <input class="form-control" name="technology[items][0][title][{{$lang}}]" value="" type="text"/>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label>Details</label>
                                @foreach(config_languages() as $lang => $language)
                                <textarea class="form-control" name="technology[items][0][details][{{$lang}}]"></textarea>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="technology_item_image">
                            <label>Image</label>
                            <input name="file" type="file" accept="image/*" size="2000"/>
                            <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                            <input class="file" type="hidden" name="technology[items][0][image]" value=""/>
                            <input class="old_file" type="hidden" name="technology[items][0][oldImage]" value=""/>
                            <img src="" class="viewer" style="display:none;"/>
                        </div>
                    </div>
                </div>
            </div>
            @endif 
        </div>
        <button class="btn btn-dark mt-2" type="button" onClick="addTechnology(this)">Add technology</button>
    </div>

    <script>
        function addTechnology(e){
            let id = $('#technology').find('.technology_item').length+1;
            let html = '<div class="technology_item mb-2" id="technology_'+id+'">';
                    html += '<h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Technology #'+id+'</h4>';
                    html += '<button type="button" class="remove_file">x</button>';
                    html += '<div class="row">';
                        html += '<div class="col-md-6">';
                            html += '<div class="technology_item_details">';
                                html += '<div class="form-group">';
                                    html += '<label>Title</label>';
                                    @foreach(config_languages() as $lang => $language)
                                    html += '<input class="form-control" name="technology[items]['+id+'][title][{{$lang}}]" value="" type="text"/>';
                                    @endforeach
                                html += '</div>';
                                html += '<div class="form-group">';
                                    html += '<label>Details</label>';
                                    @foreach(config_languages() as $lang => $language)
                                    html += '<textarea class="form-control" name="technology[items]['+id+'][details][{{$lang}}]"></textarea>';
                                    @endforeach
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-6">';
                            html += '<div class="technology_item_image">';
                                html += '<label>Image</label>';
                                html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                html += '<input class="file" type="hidden" name="technology[items]['+id+'][image]" value=""/>';
                                html += '<input class="old_file" type="hidden" name="technology[items]['+id+'][oldImage]" value=""/>';
                                html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            $('#technology').append(html);
        }
        $(document).on('change','.technology_item_image input[type="file"]',function(e){
            let pn = $(e.target.parentElement);
            if(e.target.value){
                let formData = new FormData();
                formData.append('uploading_file',e.target.files[0]);
                pn.addClass('uploading');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"  // must need for file upload
                    },
                    url: '{{ url("/manager/ajaxFileUpload") }}',
                    method:'POST', // must need for file upload
                    data: formData,
                    dataType: 'JSON',
                    contentType:false, // must need for file upload
                    processData:false, // must need for file upload
                    cache:false, // must need for file upload
                    mimeType:'multipart/form-data', // must need for file upload
                    success:function(r){
                        console.log(r);
                        if(r.result){
                            pn.find('.file').val(r.filename);
                            pn.find('img.viewer').attr('src',r.fileurl);
                            pn.find('img.viewer').show();
                        }
                        $(e.target).val(null);
                        pn.removeClass('uploading');
                    },
                    error:function(r){
                        $(e.target).val(null);
                        pn.removeClass('uploading');
                        console.log(r);
                    }
                });
            }
        });
        $(document).on('click','.technology_item .remove_file',function(e){
            let pn = $(e.target.parentElement);
            if(confirm("Are you sure want to remove this item?")){
                pn.find('.file').val('');
                pn.find('.technology_item img.viewer').attr('src','');
                pn.hide();
            }
        });
    </script>
</div>