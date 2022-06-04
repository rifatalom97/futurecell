<div class="copyright_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Product Securites</h4>
    <?php 
        $securities = isset($options['securities'])?$options['securities']:NULL;
    ?>
    <div class="form-group">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary {{ isset($securities->show)&&$securities->show=='true'?'active':'' }}">
                <input type="radio" class="show_securities" name="securities[show]" value="true" {{ isset($securities->show)&&$securities->show=='true'?'checked':'' }}> Show securities
            </label>
            <label class="btn btn-secondary {{ isset($securities->show)&&$securities->show=='false'?'active':'' }}">
                <input type="radio" class="show_securities" name="securities[show]" value="false" {{ isset($securities->show)&&$securities->show=='false'?'checked':'' }}> Hide securities
            </label>
        </div>
    </div>
    <script>
        $(document).on('change','.show_securities',function(e){
            $(this).parent().addClass('active');
            $(this).parent().siblings().removeClass('active');
        });
    </script>
</div>