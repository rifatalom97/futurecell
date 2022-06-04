<div class="grid_categroy_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">About</h4>
    <?php         
        $about = isset($home_data['about'])?$home_data['about']:NULL;
    ?>
    <div class="about">
        @include('/manager/common/form/multiLangInput',['title'=>'Title','name'=>'about[title]','values'=>isset($about->title)?$about->title:NULL])
        
        @include('/manager/common/form/multiLangInput',['type'=>'textarea','title'=>'Title','name'=>'about[details]','values'=>isset($about->details)?$about->details:NULL])
    </div>
</div>