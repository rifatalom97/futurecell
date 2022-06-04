<div class="bg-gray admin_info_box">
    <div class="bg-info text-white admin_info_box_header">
        <h2><b>Product Reviews</b></h2>
        <?php 
            $reviews = isset($product->options)?json_decode($product->options):'';

            $show_reviews = isset($reviews->show_reviews)?$reviews->show_reviews:'';
        ?>
        <div class="title_right">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary {{ $show_reviews=='true'||$show_reviews==''?'active':'' }}">
                    <input type="radio" class="show_product_reviews" name="reviews[show_reviews]" value="true" {{ $show_reviews=='true'?'checked':'' }}> Show
                </label>
                <label class="btn btn-secondary {{ $show_reviews=='false'?'active':'' }}">
                    <input type="radio" class="show_product_reviews" name="reviews[show_reviews]" value="false" {{ $show_reviews=='false'?'checked':'' }}> Hide
                </label>
            </div>
        </div>

    </div>
    <script>
        $(document).on('change','.show_product_reviews',function(e){
            $(this).parent().siblings().removeClass('active');
            $(this).parent().addClass('active');
            $('#product_reviews').css('display',(e.target.value=='true'?'block':'none'));
        })
    </script>
    <div class="admin_info_box_body" id="product_reviews" style="display: {{ $show_reviews=='true'?'block':'none' }}">
        
        <!-- reviewes -->
        <div class="widgets_items">
            @include('/manager/products/products/widgets/videoReviews')
        </div>
        <div class="widgets_items">
            @include('/manager/products/products/widgets/gridGalleryReviews')
        </div>
        <div class="widgets_items">
            @include('/manager/products/products/widgets/gridImageTextReviews')
        </div>
        <!-- end reviewes -->

    </div>
</div>