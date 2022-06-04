<div class="brands_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Brands</h4>
    <?php         
        $brands = isset($home_data['brands'])?$home_data['brands']:NULL;
    ?>

    <div class="brands">
        <div class="form-group">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary {{ isset($brands->show)&&$brands->show=='true'?'active':'' }}">
                    <input type="radio" class="show_brands" name="brands[show]" value="true" {{ isset($brands->show)&&$brands->show=='true'?'checked':'' }}> Show brands
                </label>
                <label class="btn btn-secondary {{ isset($brands->show)&&$brands->show=='false'?'active':'' }}">
                    <input type="radio" class="show_brands" name="brands[show]" value="false" {{ isset($brands->show)&&$brands->show=='false'?'checked':'' }}> Hide brands
                </label>
            </div>
        </div>
        <script>
            $(document).on('change','.show_brands',function(e){
                $(this).parent().addClass('active');
                $(this).parent().siblings().removeClass('active');
            })
        </script>
    </div>
</div>