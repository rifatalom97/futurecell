<div class="gridImageText_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Grid reviews</h4>

    <div class="gridImageText_container">
        <div id="grid_image_text_ara" style="display:block;">
            <div class="gridImageText" id="gridImageText">
                @if(isset($reviews->gridImageText->items) && count($reviews->gridImageText->items))
                @foreach($reviews->gridImageText->items as $item)
                @php($id = $loop->index)
                <div class="gridImageText_item mb-2" id="gridImageText_{{ $id }}">
                    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Review #{{ ($id+1) }}</h4>
                    <button type="button" class="remove_file">x</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="gridImageText_item_details">
                                <div class="form-group">
                                    <label>Details</label>
                                    @foreach(config_languages() as $lang => $language)
                                    <textarea class="form-control" name="reviews[gridImageText][items][{{ $id }}][details][{{$lang}}]">{{ isset($item->details->$lang)?$item->details->$lang:'' }}</textarea>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="gridImageText_item_image">
                                <label>Image</label>
                                <input name="file" type="file" accept="image/*" size="2000"/>
                                <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                                <input class="file" type="hidden" name="reviews[gridImageText][items][{{ $id }}][image]" value="{{ $item->image }}"/>
                                <input class="old_file" type="hidden" name="reviews[gridImageText][items][{{ $id }}][old_image]" value="{{ $item->image }}"/>
                                <img src="{{ asset('/uploads/'.$item->image) }}" class="viewer" style="display:{{ $item->image?'block':'none' }};"/>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif 
            </div>
            <button class="btn btn-dark mt-2" type="button" onClick="addReview(this)">Add item</button>
        </div>
    </div>

    <script>
        function addReview(e){
            let id = $('#gridImageText .gridImageText_item').length;
            let html = '<div class="gridImageText_item mb-2" id="gridImageText_0">';
                    html += '<h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Review #'+(id+1)+'</h4>';
                    html += '<button type="button" class="remove_file">x</button>';
                    html += '<div class="row">';
                        html += '<div class="col-md-6">';
                            html += '<div class="gridImageText_item_details">';
                                html += '<div class="form-group">';
                                    html += '<label>Details</label>';
                                    @foreach(config_languages() as $lang => $language)
                                    html += '<textarea class="form-control" name="reviews[gridImageText][items]['+(id)+'][details][{{$lang}}]"></textarea>';
                                    @endforeach
                                html += '</div>';
                            html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-6">';
                            html += '<div class="gridImageText_item_image">';
                                html += '<label>Image</label>';
                                html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                html += '<input class="file" type="hidden" name="reviews[gridImageText][items]['+(id)+'][image]" value=""/>';
                                html += '<input class="old_file" type="hidden" name="reviews[gridImageText][items]['+(id)+'][old_image]" value=""/>';
                                html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            $('#gridImageText').append(html);
        }
        $(document).on('change','.gridImageText_item_image input[type="file"]',function(e){
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
        $(document).on('click','.gridImageText_item .remove_file',function(e){
            let pn = $(e.target.parentElement);
            if(confirm("Are you sure want to remove this item?")){
                pn.find('.file').val('');
                pn.find('.gridImageText_item img.viewer').attr('src','');
                pn.hide();
            }
        });
    </script>
</div>