<div class="copyright_widget_container">
    <h4 class="mt-0" style="position: absolute;top: 0;left: 0;font-size: 20px;background: #17a2b8;color: white;width: 100%;padding: 5px 15px;">Copyright</h4>
    <?php 
        $copyright = isset($options['copyright'])?$options['copyright']:NULL;
    ?>
    <div class="mb-2" id="copyrgiht_container">
        <div class="row">
            <div class="col-md-6">
                @foreach(config_languages() as $lang => $language)
                <div class="form-group mb-0" style="display:{{ config_lang()==$lang?'block':'' }}">
                    <label>Copyright text</label>
                    <input type="text" class="form-control" placeholder="Copyright" name="copyright[text][{{$lang}}]" value="{{ isset($copyright->text->$lang)?$copyright->text->$lang:'' }}"/>
                </div>
                @endforeach
            </div>
            <div class="col-md-6">
                @include('manager.common.form.input',['type'=>'url','title'=>'Instagram','name'=>'copyright[social][instagram_url]','placeholder'=>'Instagram url','value'=>$copyright->social->instagram_url])
                @include('manager.common.form.input',['type'=>'url','title'=>'Facebook','name'=>'copyright[social][facebook_url]','placeholder'=>'Facebook url','value'=>$copyright->social->facebook_url])
                @include('manager.common.form.input',['type'=>'text','title'=>'Mailto','name'=>'copyright[social][mailto]','placeholder'=>'Email','value'=>$copyright->social->mailto])
            </div>
        </div>
    </div>
</div>