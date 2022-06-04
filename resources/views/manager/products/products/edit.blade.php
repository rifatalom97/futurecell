@extends('manager.common.layout')
@section('content')
<div class="pages">

    <form action="{{ url('/manager/products/save') }}" method="post">
        @csrf 
        <input type="hidden" name="id" value="{{ $product->id }}">

        <div class="bg-gray admin_info_box">
            <div class="bg-info text-white admin_info_box_header">
                <h2><b>Product details</b></h2>
                <div class="title_right">
                    <a class="btn btn-md btn-dark" href="{{ url('/manager/products/add') }}">Add new product</a>
                </div>
            </div>
            <div class="admin_info_box_body">
                <div class="product_form">
                    <div class="row">
                        <div class="col-md-8">
                            @include( 'manager/common/form/multiLangInput',['title'=>'Title','name'=>'title','values'=>$product->metas] )

                            @include( 'manager/common/form/urlslug',['url_prefix'=>'/store','value'=>$product->slug] )

                            <div class="row">
                                <div class="col-md-6">
                                    @include( 'manager/common/form/input',['title'=>'Model number','name'=>'model_number','placeholder'=>'Model number','value'=>$product->model_number] )
                                </div>
                                <div class="col-md-6">
                                    @include( 'manager/common/form/input',['title'=>'SKU','name'=>'sku','placeholder'=>'Sku','value'=>old('sku'),'value'=>$product->sku] )
                                </div>
                            </div>

                            @include( 'manager/common/form/input',['title'=>'Barcode','name'=>'barcode','placeholder'=>'Barcode','value'=>$product->barcode] )

                            <div class="row">
                                <div class="col-md-4">
                                    @include( 'manager/common/form/input',['type'=>'number','title'=>'Regular price ($)','name'=>'regular_price','placeholder'=>'Regular price','value'=>$product->regular_price] )
                                </div>
                                <div class="col-md-4">
                                    @include( 'manager/common/form/input',['type'=>'number','title'=>'Price ($)','name'=>'price','placeholder'=>'Price','value'=>$product->price] )
                                </div>
                                <div class="col-md-4">
                                    @include( 'manager/common/form/input',['type'=>'number','title'=>'Quantity','name'=>'quantity','placeholder'=>'Quantity','value'=>$product->quantity] )
                                </div>
                            </div>
                            @include( 'manager/common/form/multiLangInput',['type'=>'textarea','title'=>'Short description','name'=>'short_description','values'=>$product->metas] )
                            @include( 'manager/common/form/multiLangInput',['type'=>'textarea','title'=>'Description','name'=>'description','values'=>$product->metas] )
                        </div>
                        <div class="col-md-4">
                            
                            @include( 'manager/common/form/select',['title'=>'Status','name'=>'status','options'=>array('1'=>'Active','0'=>'Draft'),'value'=>$product->status] )
                            
                            <?php 
                                $filter_brands=array();
                                if(count($brands)){
                                    foreach($brands as $brand){
                                        $filter_brands[$brand->id] = $brand->meta->title;
                                    }
                                }
                            ?>
                            @include( 'manager/common/form/select',['title'=>'Brand','name'=>'brand','placeholder'=>'Choose brand','options'=>$filter_brands,'value'=>$product->brand] )
                            
                            <div id="models" style="display:<?= count($models)? 'block':'none'; ?>">
                                <div class="form-group">
                                    <label class="mb-0">Model</label>
                                    <select name="model" class="form-control">
                                        <option value="">Choose model</option>
                                        @foreach($models as $model)
                                        <option value="{{ $model->id }}" {{ ($model->id==$product->model)?'selected':'' }}>{{ $model->model }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <script>
                                $(document).on('change','[name="brand"]',function(){
                                    let brand_id = $(this).val();
                                    if($(this).val()){
                                        $('.admin_info_box').css('opacity','.5');
                                        $.ajax({
                                            url: '{{ url("/manager/models/get-models") }}',
                                            data: {brand_id:brand_id},
                                            dataType: 'JSON',
                                            success:function(r){
                                                let options = '<option value="">Choose model</option>';
                                                if(r && r.length){
                                                    for(let i = 0; i<r.length;i++){
                                                        options += '<option value='+r[i].id+'>'+r[i].meta.title+'</option>';
                                                    }
                                                    $('#models [name="model"]').html(options);
                                                    $('#models').show();
                                                }else{
                                                    $('#models').hide();
                                                }

                                                $('.admin_info_box').css('opacity','1');
                                            },
                                            error:function(r){
                                                console.log(r);
                                            }
                                        });
                                    }
                                })
                            </script>

                            
                            @if(count($sizes))
                            <?php 
                                $selected_sizes = array();
                                if($product->sizes && count($product->sizes)){
                                    foreach($product->sizes as $size){
                                        $selected_sizes[] = $size->size_id;
                                    }
                                }
                            ?>
                            <div class="form-group">
                                <label class="mb-0">Sizes</label>
                                <div class="multiple_select_field">
                                    @foreach($sizes as $size)
                                    <div class="select_item">
                                        <input id="size_{{$size->id}}" name="sizes[]" type="checkbox" value="{{ $size->id }}" {{ in_array($size->id, $selected_sizes)?'checked':'' }}>
                                        <label for="size_{{$size->id}}">
                                            <span class="item_label">{{ $size->value }} {{ $size->unite }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if(count($categories))
                            <?php 
                                $selected_categories = array();
                                if($product->categories && count($product->categories)){
                                    foreach($product->categories as $category){
                                        $selected_categories[] = $category->category_id;
                                    }
                                }
                            ?>
                            <div class="form-group">
                                <label class="mb-0">Categories</label>
                                <div class="multiple_select_field">
                                    @foreach($categories as $category)
                                    <div class="select_item">
                                        <input name="categories[]" id="category_{{ $category->id }}" type="checkbox" value="{{ $category->id }}" {{ in_array($category->id, $selected_categories)?'checked':'' }}>
                                        <label for="category_{{ $category->id }}">
                                            <span class="item_label">{{ $category->meta->title }}</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if(count($colors))
                            <?php 
                                $selected_colors = array();
                                if($product->colors && count($product->colors)){
                                    foreach($product->colors as $color){
                                        $selected_colors[] = $color->color_id;
                                    }
                                }
                            ?>
                            <div class="form-group">
                                <label class="mb-0">Colors</label>
                                <div class="multiple_colors_select_field">
                                    @foreach($colors as $color)    
                                    <div class="select_item">
                                        <input name="colors[]" id="color_{{ $color->id }}" type="checkbox" value="{{ $color->id }}" {{ in_array($color->id, $selected_colors)?'checked':'' }}>
                                        <label for="color_{{ $color->id }}">
                                            <span class="color_circle" style="background-color: {{ $color->code }};">&nbsp;</span>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="card mb-3">
                                <div class="card-header">Thumbnail</div>
                                <div class="card-body">

                                    <div class="thumbnail_uploader" id="thumbnail_uploader">
                                        <input name="file" type="file" accept="image/*" size="2000"/>
                                        <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>

                                        <div class="file_box" style="display:{{ $product->thumbnail?'block':'none' }}">
                                            <input class="file" type="hidden" name="thumbnail" value="{{ $product->thumbnail }}"/>
                                            <input class="old_file" type="hidden" name="old_thumbnail" value="{{ $product->thumbnail }}"/>
                                            <img src="{{ asset('/uploads/'.$product->thumbnail) }}"/>
                                            <button type="button" class="remove_file">x</button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">Gallery</div>
                                <div class="card-body">
                                    <div class="list-group mb-3 gallery-lists" id="gallery-lists" role="tablist">
                                        @if($product->gallery)
                                            <?php 
                                                $gallery = json_decode($product->gallery);
                                            ?>
                                            @if($gallery && count($gallery))
                                                @foreach($gallery as $item)
                                                <?php 
                                                    if(!isset($item->image)||!$item->image){
                                                        continue;
                                                    }
                                                    $id = $loop->iteration;
                                                ?>
                                                <div class="list-group-item list-group-item-action pointer gallery_item" data-toggle="list" role="tab" aria-controls="gallery">
                                                    <h5 class="gallery-heading mb-0 pb-0 d-flex flex-row justify-content-between">Item {{ $id }} <span class="btn btn-danger btn-sm remove_file">Remove</span></h5>
                                                    <div class="gallery_content mt-4" style="display: none;">
                                                        <input name="file" type="file" accept="image/*" size="2000"/>
                                                        <img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>
                                                        <div class="file_box" style="display:block">
                                                            <input class="file" type="hidden" name="gallery[{{ $id }}][image]" value="{{ $item->image }}"/>
                                                            <input class="old_file" type="hidden" name="gallery[{{ $id }}][old_image]" value="{{ $item->image }}"/>
                                                            <img src="{{ asset('/uploads/'.$item->image) }}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @endif
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-primary" onClick="addGalleryItem()">Add gallery item</button>
                                </div>
                            </div>

                            <script>
                                function addGalleryItem(){
                                    let id = $('#gallery-lists .gallery_item').length;
                                    let html = '<div class="list-group-item list-group-item-action pointer gallery_item" data-toggle="list" role="tab" aria-controls="gallery">';
                                    html += '<h5 class="gallery-heading mb-0 pb-0 d-flex flex-row justify-content-between" data-id="1">Item '+(id+1)+' <span data-id="1" class="btn btn-danger btn-sm remove_file">Remove</span></h5>';
                                    html += '<div class="gallery_content mt-4" style="display: none;">';
                                    html += '<input name="file" type="file" accept="image/*" size="2000"/>';
                                    html += '<img class="loading" src="{{ asset('/assets/admin/img/loading.gif') }}"/>';
                                    html += '<div class="file_box" style="display:none">';
                                    html += '<input class="file" type="hidden" name="gallery['+id+'][image]" value=""/>';
                                    html += '<input class="old_file" type="hidden" name="gallery['+id+'][old_image]" value=""/>';
                                    html += '<img src=""/>';
                                    html += '</div>';
                                    html += '</div>';
                                    html += '</div>';
                                    $('#gallery-lists').append(html);
                                }
                                $(document).on('click','.gallery-heading',function(){
                                    $(this).parent().siblings().find('.gallery_content').hide();
                                    $(this).parent().find('.gallery_content').show();
                                })
                                $(document).on('change','#thumbnail_uploader input[type="file"],.gallery_item input[type="file"]',function(e){
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
                                $(document).on('click','#thumbnail_uploader .remove_file,.gallery_item .remove_file',function(e){
                                    let pn = $(e.target.parentElement);
                                    if(pn.hasClass('gallery-heading')){
                                        pn = pn.parent();
                                    }
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
                    </div>
                </div>
            </div>
        </div>

        @include('manager.products.products.productReviews')

        <div class="submit_fixed_bar">
            <button class="btn btn-dark btn-lg" type="submit">Save</button>
        </div>
    </form>
</div>
@endsection