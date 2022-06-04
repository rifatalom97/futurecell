@foreach(config_languages() as $lang => $language)
<div class="{{ $lang }}" style="display:{{ ($lang==config_lang()?'block':'none') }}">
    <?php 
        $new_name = $name."[{$lang}]";
        $paramKey = $name.'.'.$lang;

        $value = (isset($values)&&$values&&count((array)$values)?get_val_by_lang($values, $name, $lang):old($new_name));
        
        $params = array(
            'type'              => isset($type)?$type:'text',
            'title'             => (isset($title)?$title:NULL),
            'name'              => $new_name,
            'paramKey'          => $paramKey,
            'value'             => $value,
            'slug_generator'    => ($name=='title'&&$lang==config_lang()?:false),
            'tinymce'           => isset($tinymce)?$tinymce:false
        );
    ?>
    @if(!isset($type)||$type!='textarea')
    @include('manager/common/form/input',$params)
    @else
    @include('manager/common/form/textarea',$params)
    @endif 
</div>
@endforeach
