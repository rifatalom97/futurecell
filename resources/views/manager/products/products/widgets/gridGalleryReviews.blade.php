<div class="gridGalleryReviews_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Gallery</h4>

    <div class="gridGalleryReviews_container">

        <div id="gridGalleryReviews_area" style="display:block;">
            <div class="gridGalleryReviews" id="gridGalleryReviews">
                @if(isset($reviews->gridGalleryReviews->items) && count($reviews->gridGalleryReviews->items))
                @foreach($reviews->gridGalleryReviews->items as $item)
                @php($id = $loop->index)
                <div class="gridGalleryReviews_item mb-2" id="gridGalleryReviews_{{ $id }}">
                    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Row #{{ ($id+1) }}</h4>
                    <button type="button" class="remove_file">x</button>
                    <div class="row">
                        <div class="col-md-12">
                            @include('manager.common.form.ajaxUploader',['title'=>'Image-1','name'=>"reviews[gridGalleryReviews][items][{$id}][thumb1]",'old_name'=>"reviews[gridGalleryReviews][items][{$id}][old_thumb1]",'value'=>$item->thumb1])
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                        @include('manager.common.form.ajaxUploader',['title'=>'Image-2','name'=>"reviews[gridGalleryReviews][items][{$id}][thumb2]",'old_name'=>"reviews[gridGalleryReviews][items][{$id}][old_thumb2]",'value'=>$item->thumb2])
                        </div>
                        <div class="col-md-6">
                        @include('manager.common.form.ajaxUploader',['title'=>'Image-3','name'=>"reviews[gridGalleryReviews][items][{$id}][thumb3]",'old_name'=>"reviews[gridGalleryReviews][items][{$id}][old_thumb3]",'value'=>$item->thumb3])
                        </div>
                    </div>
                </div>
                @endforeach
                @endif 
            </div>
            <button class="btn btn-dark mt-2" type="button" onClick="addGridGalleryItem(this)">Add gallery item</button>
        </div>
    </div>

    <script>
        function addGridGalleryItem(e){
            let id = $('#gridGalleryReviews .gridGalleryReviews_item').length;
            let html = '<div class="gridGalleryReviews_item mb-2" id="gridGalleryReviews_0">';
                    html += '<h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Row #'+(id+1)+'</h4>';
                    html += '<button type="button" class="remove_file">x</button>';
                    html += '<div class="row">';
                        html += '<div class="col-md-12">';
                            html += '<div class="gridGalleryReviews_item_image">';
                                html += '<label>Image-1</label>';
                                html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                html += '<input class="file" type="hidden" name="reviews[gridGalleryReviews][items]['+(id)+'][thumb1]" value=""/>';
                                html += '<input class="old_file" type="hidden" name="reviews[gridGalleryReviews][items]['+(id)+'][old_thumb1]" value=""/>';
                                html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                    html += '<hr>';
                    html += '<div class="row">';
                        html += '<div class="col-md-6">';
                            html += '<div class="gridGalleryReviews_item_image">';
                                html += '<label>Image-2</label>';
                                html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                html += '<input class="file" type="hidden" name="reviews[gridGalleryReviews][items]['+(id)+'][thumb2]" value=""/>';
                                html += '<input class="old_file" type="hidden" name="reviews[gridGalleryReviews][items]['+(id)+'][old_thumb2]" value=""/>';
                                html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '</div>';
                        html += '</div>';
                        html += '<div class="col-md-6">';
                            html += '<div class="gridGalleryReviews_item_image">';
                                html += '<label>Image-3</label>';
                                html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                html += '<input class="file" type="hidden" name="reviews[gridGalleryReviews][items]['+(id)+'][thumb3]" value=""/>';
                                html += '<input class="old_file" type="hidden" name="reviews[gridGalleryReviews][items]['+(id)+'][old_thumb3]" value=""/>';
                                html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            $('#gridGalleryReviews').append(html);
        }
        $(document).on('change','.gridGalleryReviews_item_image input[type="file"]',function(e){
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
        $(document).on('click','.gridGalleryReviews_item .remove_file',function(e){
            let pn = $(e.target.parentElement);
            if(confirm("Are you sure want to remove this item?")){
                pn.find('.file').val('');
                pn.find('.gridGalleryReviews_item img.viewer').attr('src','');
                pn.hide();
            }
        });
    </script>
</div>