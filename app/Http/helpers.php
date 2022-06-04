<?php 

if(!function_exists('config_languages')){
    function config_languages(){
        return Config::get('app.locales');
    }
}
if(!function_exists('config_lang')){
    function config_lang(){
        return Config::get('app.locale');
    }
}
if(!function_exists('get_val_by_lang')){
    function get_val_by_lang( $data, $key, $lang ){
        $result = NULL;
        if(!is_countable($data)){
            $data = (array)$data;
            if($data && count($data)){
                foreach($data as $key => $item){
                    if($key==$lang){
                        $result = $item;
                    }
                }
            }
        }else{
            foreach($data as $item){
                if($item->lang==$lang){
                    $result = $item->{$key};
                }
            }
        }
        return $result;
    }
}
if(!function_exists('insert_update_meta')){
    function insert_update_meta( $request, $fieldKey, $id, $supports=array('title'), $CLASS ){
        foreach(config_languages() as $lang => $language){
            $fields = array($fieldKey => $id, 'lang' => $lang);
            foreach( $supports as $key ){
                $value = NULL;
                if( $request->input($key) && isset($request->input($key)[$lang]) ){
                    $value = $request->input($key)[$lang];
                }
                $fields[$key] = $value;
            }
            if(count($fields)){
                $CLASS::updateOrCreate(array($fieldKey => $id, 'lang' => $lang),$fields);
            }
        }
    }
}


function exploded_flits($request,$key){
    if(isset($request->$key)&&$request->$key){
        $string = trim($request->$key);
        return explode(' ',$string);
    }
    return array();
}
function ativate_selected( $selected, $current ){
    if(is_array($selected) && in_array($current, $selected)){
        return 'active';
    }
}