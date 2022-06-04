<div class="expertise_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Our expertise</h4>
    <?php 
        $expartise = $home_data['expartise'];
    ?>
    <div class="expertise_container">

        @foreach(config_languages() as $lang => $language)
        <div class="{{ $lang }}" style="display:{{ $lang==config_lang()?'block':'none' }}">
            <div class="form-group">
                <label for="">Title</label>
                <input class="form-control" name="expertise[title][{{$lang}}]" value="{{ isset($expartise->title->$lang)?$expartise->title->$lang:'' }}"/>
            </div>
        </div>
        @endforeach

        <div class="expertise" id="expertise">
            @if(isset($expartise->items) && count($expartise->items))
            @foreach($expartise->items as $item)
                <?php 
                    $id = $loop->iteration-1;
                ?>
                <div class="expert mb-2" id="expert_{{ $id }}">
                    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Expert item #{{ ($id+1) }}</h4>
                    <button type="button" class="remove_file">x</button>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="expert_details">
                                <label>Logo</label>
                                <input name="file" type="file" accept="image/*" size="2000"/>
                                <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                                <input class="file" type="hidden" name="expertise[items][{{ $id }}][logo]" value="{{ $item->logo }}"/>
                                <input class="old_file" type="hidden" name="expertise[items][{{ $id }}][oldLogo]" value="{{ $item->oldLogo }}"/>
                                <img src="{{ asset('/uploads/'.$item->logo) }}" class="viewer" style="display:{{ $item->logo?'block':'none' }};"/>
                                <div class="form-group">
                                    <label class="mt-4">Details</label>
                                    @foreach(config_languages() as $lang => $language)
                                    <textarea class="form-control" name="expertise[items][{{ $id }}][details][{{$lang}}]">{{ isset($item->details->$lang)?$item->details->$lang:'' }}</textarea>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="expert_image">
                                <label>Image</label>
                                <input name="file" type="file" accept="image/*" size="2000"/>
                                <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                                <input class="file" type="hidden" name="expertise[items][{{ $id }}][image]" value="{{ $item->image }}"/>
                                <input class="old_file" type="hidden" name="expertise[items][{{ $id }}][oldImage]" value="{{ $item->oldImage }}"/>
                                <img src="{{ asset('/uploads/'.$item->image) }}" class="viewer" style="display:{{ $item->oldImage?'block':'none' }};"/>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @endif 
        </div>
        <button class="btn btn-dark mt-2" type="button" onClick="add_new_export(this)">Add expert</button>
    </div>

    <script>
        function add_new_export(e){
            let id = $('#expertise .expert').length;
            let html = '<div class="expert mb-2" id="expert_'+id+'">';
                html += '<h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Expert item #'+(id+1)+'</h4>';
                html += '<button type="button" class="remove_file">x</button>';
                html += '<div class="row">';
                    html += '<div class="col-md-6">';
                        html += '<div class="expert_details">';
                            html += '<label>Logo</label>';
                            html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                            html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                            html += '<input class="file" type="hidden" name="expertise[items]['+id+'][logo]" value=""/>';
                            html += '<input class="old_file" type="hidden" name="expertise[items]['+id+'][oldLogo]" value=""/>';
                            html += '<img src="" class="viewer" style="display:none;"/>';
                            html += '<div class="form-group">';
                            html += '<label class="mt-4">Details</label>';
                            @foreach(config_languages() as $lang => $language)
                            html += '<textarea class="form-control" name="expertise[items]['+id+'][details][{{$lang}}]"></textarea>';
                            @endforeach
                            html += '</div>';
                        html += '</div>';
                    html += '</div>';
                    html += '<div class="col-md-6">';
                        html += '<div class="expert_image">';
                            html += '<label>Image</label>';
                            html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                            html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                            html += '<input class="file" type="hidden" name="expertise[items]['+id+'][image]" value=""/>';
                            html += '<input class="old_file" type="hidden" name="expertise[items]['+id+'][oldImage]" value=""/>';
                            html += '<img src="" class="viewer" style="display:none;"/>';
                        html += '</div>';
                    html += '</div>';
                html += '</div>';
            html += '</div>';
            $('#expertise').append(html);
            
        }
        $(document).on('change','.expert_details input[type="file"],.expert_image input[type="file"]',function(e){
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
        $(document).on('click','.expert .remove_file',function(e){
            let pn = $(e.target.parentElement);
            if(confirm("Are you sure want to remove this item?")){
                pn.find('.file').val('');
                pn.find('.expert img.viewer').attr('src','');
                pn.hide();
            }
        });
    </script>
</div>