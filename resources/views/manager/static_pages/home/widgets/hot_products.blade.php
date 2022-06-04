<div class="grid_categroy_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Hot products carousel</h4>
    
    <?php 
        $filter_cats = [];
        if(count($categories)){
            foreach($categories as $category){
                $filter_cats[$category->id] = $category->meta->title;
            }
        }
        $hot_products_type = array(
            '1' => 'Best sale',
            '2' => 'From category',
        );

        $hot_products = isset($home_data['hot_products'])?$home_data['hot_products']:NULL;
    ?>

    <div class="hot_products_selector">
        <div class="hot_products_type">
        @include('/manager/common/form/select',['title'=>'Category','name'=>'hot_products[type]','options'=>$hot_products_type,'value'=>(isset($hot_products->type)?$hot_products->type:'')])
        </div>
                    
        <div class="hot_products_category" style="display:none">
        @include('/manager/common/form/select',['title'=>'Category','name'=>'hot_products[category]','options'=>$filter_cats,'value'=>(isset($hot_products->category)?$hot_products->category:'')])
        </div>
    </div>
    <script>
        let val = $('.hot_products_type select').val();
        $('.hot_products_category').css('display',(val=='2'?'block':'none'));
        $('.hot_products_type select').on('change',function(){
            $('.hot_products_category').css('display',($(this).val()=='2'?'block':'none'));
        });
    </script>
</div>