<div class="bg-gray admin_info_box">
    <div class="bg-info text-white admin_info_box_header">
        <h2><b>Meta Data</b></h2>
    </div>
    <div class="admin_info_box_body">
        @include( 'manager/common/form/multiLangInput',['title'=>'Meta title','name'=>'metaTitle','values'=>$values] )
        @include( 'manager/common/form/multiLangInput',['title'=>'Meta keywords','name'=>'metaKeywords','values'=>$values] )
        @include( 'manager/common/form/multiLangInput',['type'=>'textarea','title'=>'Meta description','name'=>'metaDescription','values'=>$values] )
    </div>
</div>