<div class="copyright_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Delivery</h4>
    <?php 
        $delivery = isset($options['delivery'])?$options['delivery']:NULL;
    ?>
    <div class="form-group">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary {{ isset($delivery->show)&&$delivery->show=='true'?'active':'' }}">
                <input type="radio" class="show_delivery" name="delivery[show]" value="true" {{ isset($delivery->show)&&$delivery->show=='true'?'checked':'' }}> Show delivery
            </label>
            <label class="btn btn-secondary {{ isset($delivery->show)&&$delivery->show=='false'?'active':'' }}">
                <input type="radio" class="show_delivery" name="delivery[show]" value="false" {{ isset($delivery->show)&&$delivery->show=='false'?'checked':'' }}> Show delivery
            </label>
        </div>
    </div>
    <script>
        $(document).on('change','.show_delivery',function(e){
            $(this).parent().addClass('active');
            $(this).parent().siblings().removeClass('active');
            if(e.target.value=='true'){
                $('#delivery_container').show();
            }else{
                $('#delivery_container').hide();
            }
        })
    </script>

    <div class="mb-2" id="delivery_container" style="display:{{ isset($delivery->show)&&$delivery->show=='true'?'block':'none' }};">
        <div class="row">
            <div class="col-md-4">
                <div class="border p-3">
                    <div class="delivery_image">
                        <input name="file" type="file" accept="image/*" size="2000"/>
                        <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>

                        <div class="file_box" style="display:{{ isset($delivery->first->image)&&$delivery->first->image?'block':'' }}">
                            <input class="file" type="hidden" name="delivery[first][image]" value="{{ $delivery->first->image }}"/>
                            <input class="old_file" type="hidden" name="delivery[first][old_image]" value="{{ $delivery->first->image }}"/>
                            <img src="{{ asset('/uploads/'.$delivery->first->image) }}"/>
                        </div>
                    </div>
                    @foreach(config_languages() as $lang => $language)
                    <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                        <input type="text" class="form-control" placeholder="Delivery text" name="delivery[first][text][{{$lang}}]" value="{{ isset($delivery->first->text->$lang)?$delivery->first->text->$lang:'' }}"/>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="border p-3">
                    <div class="delivery_image">
                        <input name="file" type="file" accept="image/*" size="2000"/>
                        <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>

                        <div class="file_box" style="display:{{ isset($delivery->second->image)&&$delivery->second->image?'block':'' }}">
                            <input class="file" type="hidden" name="delivery[second][image]" value="{{ $delivery->second->image }}"/>
                            <input class="old_file" type="hidden" name="delivery[second][old_image]" value="{{ $delivery->second->image }}"/>
                            <img src="{{ asset('/uploads/'.$delivery->second->image) }}"/>
                        </div>
                    </div>
                    @foreach(config_languages() as $lang => $language)
                    <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                        <input type="text" class="form-control" placeholder="Delivery text" name="delivery[second][text][{{$lang}}]" value="{{ isset($delivery->second->text->$lang)?$delivery->second->text->$lang:'' }}"/>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="border p-3">
                    <div class="delivery_image">
                        <input name="file" type="file" accept="image/*" size="2000"/>
                        <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>

                        <div class="file_box" style="display:{{ isset($delivery->third->image)&&$delivery->third->image?'block':'' }}">
                            <input class="file" type="hidden" name="delivery[third][image]" value="{{ $delivery->third->image }}"/>
                            <input class="old_file" type="hidden" name="delivery[third][old_image]" value="{{ $delivery->third->image }}"/>
                            <img src="{{ asset('/uploads/'.$delivery->third->image) }}"/>
                        </div>
                    </div>
                    @foreach(config_languages() as $lang => $language)
                    <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                        <input type="text" class="form-control" placeholder="Delivery text" name="delivery[third][text][{{$lang}}]" value="{{ isset($delivery->third->text->$lang)?$delivery->third->text->$lang:'' }}"/>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('change','.delivery_image input[type="file"]',function(e){
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
                        if(r.result){
                            pn.find('.file').val(r.filename);
                            pn.find('.file_box img').attr('src',r.fileurl);
                            pn.find('.file_box').show();
                        }
                        $(e.target).val(null);
                        pn.removeClass('uploading');
                    },
                    error:function(r){
                        $(e.target).val(null);
                        pn.removeClass('uploading');
                    }
                });
            }
        });
    </script>
</div>