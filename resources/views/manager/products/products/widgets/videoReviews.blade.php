<div class="video_widget">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Video review</h4>
    <?php 
        $show = isset($reviews->video->show)?$reviews->video->show:'true';
        $url = isset($reviews->video->url)?$reviews->video->url:'';
    ?>
    <div class="mb-2" id="video_widget_container">
        <div class="form-group">
            <label for="">Show or Hide : </label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-secondary {{ $show=='true'?'active':'' }}">
                    <input type="radio" class="show_video_review" name="reviews[video][show]" id="show_video_review1" value="true" {{ $show=='true'?'checked':'' }}> Show
                </label>
                <label class="btn btn-secondary {{ $show=='false'?'active':'' }}">
                    <input type="radio" class="show_video_review" name="reviews[video][show]" id="show_video_review2" value="false" {{ $show=='false'?'checked':'' }}> Hide
                </label>
            </div>
        </div>
        <script>
            $(document).on('change','.show_video_review',function(e){
                $(this).parent().addClass('active');
                $(this).parent().siblings().removeClass('active');
                if(e.target.value=='true'){
                    $('#video_reviews_area').show();
                }else{
                    $('#video_reviews_area').hide();
                }
            })
        </script>
        <div id="video_reviews_area" style="display:{{ $show=='true'?'block':'none' }}">
            @include( 'manager/common/form/input',['type'=>'url','title'=>'Youtube url','name'=>'reviews[video][url]','value'=>$url] )

            <div class="form-group">
                <label>Details</label>
                @foreach(config_languages() as $lang => $language)
                <textarea class="form-control" name="reviews[video][details][{{$lang}}]">{{ isset($show->details->$lang)?$show->details->$lang:'' }}</textarea>
                @endforeach
            </div>
        </div>
    </div>
</div>