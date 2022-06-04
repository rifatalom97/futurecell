<div class="banner_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Banner</h4>

    <div class="mb-2 row" id="banner_container">

        @if(isset($store_data['banners']))
            @foreach($store_data['banners'] as $item)
            <?php 
                $id = $loop->iteration-1;
            ?>
            <div class="banner_item col-md-3" id="banner_{{ $id }}">
                <input name="file" type="file" accept="image/*" size="2000"/>
                <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>

                <div class="file_relative mt-4" style="display:block">
                    <input class="form-control" type="url" name="banner[{{ $id }}][url]" value="{{ $item->url }}" placeholder="https://example.com"/>
                    <div class="file_box">
                        <input class="file" type="hidden" name="banner[{{ $id }}][new]" value="{{ $item->new }}"/>
                        <input class="old_file" type="hidden" name="banner[{{ $id }}][old]" value="{{ $item->old }}"/>
                        <img src="{{ asset('/uploads/'.$item->old) }}"/>
                        <button type="button" class="remove_file">x</button>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
    <button class="btn btn-dark mt-2" type="button" onClick="addBanner(this)">Add new banner</button>
    <script>
        function addBanner(e){
            let id = $('#banner_container .banner_item').length;
            let html = '<div class="banner_item col-md-3" id="banner_'+id+'">';
                    html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                    html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                    html += '<div class="file_relative mt-4" style="display:none">';
                        html += '<input class="form-control" type="url" name="banner['+id+'][url]" value="" placeholder="https://example.com"/>';
                        html += '<div class="file_box">';
                            html += '<input class="file" type="hidden" name="banner['+id+'][new]" value=""/>';
                            html += '<input class="old_file" type="hidden" name="banner['+id+'][old]" value=""/>';
                            html += '<img src=""/>';
                            html += '<button class="remove_file" type="button">x</button>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            $('#banner_container').append(html);
        }
        $(document).on('change','#banner_container .banner_item input[type="file"]',function(e){
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
                            pn.find('.file_relative').show();
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
        $(document).on('click','.banner_item .remove_file',function(e){
            let pn = $(e.target.parentElement.parentElement.parentElement);
            if( (!pn.find('.old_file').val() && !pn.find('.file').val()) || (!pn.find('.old_file').val() && pn.find('.file').val()) ){
                pn.remove();
            }else{
                if(confirm("Are you sure want to remove this item?")){
                    pn.find('.file').val('');
                    pn.find('.file_box img').attr('src','');
                    pn.hide();
                }
            }
        });
    </script>
</div>