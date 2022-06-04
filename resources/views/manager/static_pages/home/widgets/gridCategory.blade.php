<div class="grid_categroy_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Grid category</h4>
    
    <?php 
        $filter_cats = [];
        if(count($categories)){
            foreach($categories as $category){
                $filter_cats[$category->id] = $category->meta->title;
            }
        }
        
        $grid_category = isset($home_data['grid_category'])?$home_data['grid_category']:NULL;
    ?>

    <div class="grid_categories">
        <div class="row">
            <div class="col-md-6">
                <div class="each_grid_category">
                    @include('/manager/common/form/ajaxUploader',['name'=>'grid_category[0][image]','old_name'=>'grid_category[0][old_image]','value'=>(isset($grid_category[0]->image)?$grid_category[0]->image:'')])
                    <div class="clearfix mt-2"></div>
                    @include('/manager/common/form/select',['title'=>'Category','name'=>'grid_category[0][category]','options'=>$filter_cats,'value'=>(isset($grid_category[0]->category)?$grid_category[0]->category:'')])
                </div>
            </div>

            <div class="col-md-6">
                <div class="each_grid_category">
                    @include('/manager/common/form/ajaxUploader',['name'=>'grid_category[1][image]','old_name'=>'grid_category[1][old_image]','value'=>(isset($grid_category[1]->image)?$grid_category[1]->image:'')])
                    <div class="clearfix mt-2"></div>
                    @include('/manager/common/form/select',['title'=>'Category','name'=>'grid_category[1][category]','options'=>$filter_cats,'value'=>(isset($grid_category[1]->category)?$grid_category[1]->category:'')])
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <div class="each_grid_category">
                    @include('/manager/common/form/ajaxUploader',['name'=>'grid_category[2][image]','old_name'=>'grid_category[2][old_image]','value'=>(isset($grid_category[2]->image)?$grid_category[2]->image:'')])
                    <div class="clearfix mt-2"></div>
                    @include('/manager/common/form/select',['title'=>'Category','name'=>'grid_category[2][category]','options'=>$filter_cats,'value'=>(isset($grid_category[2]->category)?$grid_category[2]->category:'')])
                </div>
            </div>
            <div class="col-md-4">
                <div class="each_grid_category">
                    @include('/manager/common/form/ajaxUploader',['name'=>'grid_category[3][image]','old_name'=>'grid_category[3][old_image]','value'=>(isset($grid_category[3]->image)?$grid_category[3]->image:'')])
                    <div class="clearfix mt-2"></div>
                    @include('/manager/common/form/select',['title'=>'Category','name'=>'grid_category[3][category]','options'=>$filter_cats,'value'=>(isset($grid_category[3]->category)?$grid_category[3]->category:'')])
                </div>
            </div>
            <div class="col-md-4">
                <div class="each_grid_category">
                    @include('/manager/common/form/ajaxUploader',['name'=>'grid_category[4][image]','old_name'=>'grid_category[4][old_image]','value'=>(isset($grid_category[4]->image)?$grid_category[4]->image:'')])
                    <div class="clearfix mt-2"></div>
                    @include('/manager/common/form/select',['title'=>'Category','name'=>'grid_category[4][category]','options'=>$filter_cats,'value'=>(isset($grid_category[4]->category)?$grid_category[4]->category:'')])
                </div>
            </div>
        </div>
    </div>
</div>