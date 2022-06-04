<!-- Filter -->
<section class="product_filter_area mt-4">
    <div class="container">
        <div class="product_filter_box text-center mb-5">
            <div class="filer_box_inner pt-5 pb-4 px-4">
                <div class="filter_box_header">
                    <button data-target="color" class="active">צבע</button>
                    <button data-target="player" class="">מכשיר</button>
                    <button data-target="size" class="">אורך</button>
                </div>

                <div class="filter_box_content mb-5 mt-3 pt-2">

                    <div id="color" class="filter_content active" >
                        @if($colors && count($colors))
                        @foreach($colors as $color)
                        <span class="flit colors {{ ativate_selected($selected_colors, $color->code) }}"
                                data-value="{{$color->code}}"
                                data-type="colors"
                                style="background:{{$color->code}}">&nbsp;</span>
                        @endforeach
                        @endif
                    </div>
                    
                    
                    <div id="player" class="filter_content">
                        <table class="table table-borderless filter_players">
                            <thead>
                                <tr>
                                    @if(count($brands))
                                    <th>Brand</th>
                                    @endif
                                    @if(count($models))
                                    <th>Model</th>
                                    @endif
                                    @if(count($categories))
                                    <th>Category</th>
                                    @else
                                    <th>&nbsp;</th>
                                    @endif
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php( $row_count = max(count($brands),count($models),count($categories)) )
                                @for($i=0;$i<$row_count;$i++)
                                <tr>
                                    @if(count($brands))
                                    <td>
                                        @if(isset($brands[$i]->title))
                                        <span class="flit {{ ativate_selected($selected_brands, $brands[$i]->slug) }}" data-value="{{ $brands[$i]->slug }}" data-type="brands" onClick="">{{ $brands[$i]->title }}</span>
                                        @else 
                                        &nbsp;
                                        @endif
                                    </td>
                                    @endif 
                                    @if(count($models))
                                    <td>
                                        @if(isset($models[$i]->title))
                                        <span class="flit {{ ativate_selected($selected_models, $models[$i]->model) }}" data-value="{{ $models[$i]->model }}" data-type="models" onClick="">{{ $models[$i]->title }}</span>
                                        @else 
                                        &nbsp;
                                        @endif
                                    </td>
                                    @endif 
                                    @if(count($categories))
                                    <td>
                                        @if(isset($categories[$i]->title))
                                        <span class="flit {{ ativate_selected($selected_categories, $categories[$i]->slug) }}" data-value="{{ $categories[$i]->slug }}" data-type="categories" onClick="">{{ $categories[$i]->title }}</span>
                                        @else 
                                        &nbsp;
                                        @endif
                                    </td>
                                    @else
                                    <th>&nbsp;</th>
                                    @endif
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <div id="size" class="filter_content">
                        @if( $sizes && count($sizes) )
                        @foreach($sizes as $size)
                        <span class="flit sizes {{ ativate_selected($selected_sizes, $size->value.'-'.$size->unite) }}" 
                                data-value="{{ $size->value.'-'.$size->unite }}" 
                                data-type="sizes">{{ $size->value }} {{ $size->unite }}</span>
                        @endforeach
                        @endif
                    </div>

                </div>

                <div class="filter_box_footer mt-4">
                    <p class="mb-0">
                        <span class="text-muted">נבחרו <span id="flit_quantity">0</span></span>
                        <span class="mx-4 text-muted">|</span>
                        <span class="pointer" onClick="filter_inactive_flit()">נקה</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End filter -->