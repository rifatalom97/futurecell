<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MetaTags;
use DB;

class MetaTagsController extends Controller
{
    static public function runAndGetMLTMetas( $meta_for, $meta_for_id, $data ){
        
        self::delete( $meta_for, $meta_for_id );
        self::createMultilangualMeta( $meta_for, $meta_for_id, $data );
        
        return self::getMultilangualMeta( $meta_for, $meta_for_id );
    }

    static public function getMultilangualMeta( $meta_for, $meta_for_id ){
        $metas = self::getMetaTags( $meta_for, $meta_for_id );
        
        $reOrdermetas = [];
        if($metas && count($metas)){
            foreach( $metas as $meta ){
                $reOrdermetas[$meta->lang . '_metaTitle'] = $meta->metaTitle;
                $reOrdermetas[$meta->lang . '_metaKeywords'] = $meta->metaKeywords;
                $reOrdermetas[$meta->lang . '_metaDescription'] = $meta->metaDescription;
            }
        }

        return $reOrdermetas;
    }
    static public function getMetaTags( $meta_for, $meta_for_id ='', $lang = '' ){

        $metaObj = MetaTags::where( 'meta_for', $meta_for );
        
        if( $meta_for_id ){
            $metaObj = $metaObj->where('meta_for_id',$meta_for_id);
        }

        if( $lang ){
            $metaObj = $metaObj->where('lang',$lang);
        }

        return $metaObj->get();
    }
    static public function getMetaTagsFirst( $meta_for, $meta_for_id ='', $lang = '' ){

        $metaObj = DB::table('meta_tags')->where( 'meta_for', $meta_for );
        
        if( $meta_for_id ){
            $metaObj = $metaObj->where('meta_for_id',$meta_for_id);
        }

        if( $lang ){
            $metaObj = $metaObj->where('lang',$lang);
        }

        return $metaObj->first();
    }
    static public function getMetaTagsFilterd( $meta_for, $meta_for_id ='', $lang = '' ){

        $meta_tags = [];
        $result = self::getMetaTags( $meta_for, $meta_for_id, $lang );

        if($result){
            foreach($result as $meta){
                $meta_tags[$meta->lang.'_'.'metaTitle'] = $meta->metaTitle;
                $meta_tags[$meta->lang.'_'.'metaKeywords'] = $meta->metaKeywords;
                $meta_tags[$meta->lang.'_'.'metaDescription'] = $meta->metaDescription;
            }
        }
        return $meta_tags;        
    }

    static public function createMeata( $meta_for, $meta_for_id = '', $data, $lang ){
        
        if( $data ){
            $dfaultData = [
                'lang'          => $lang,
                'meta_for'      => $meta_for,
                'meta_for_id'   => $meta_for_id,
            ];

            $data = array_merge($data, $dfaultData);

            MetaTags::create($data);
        }
    }

    static public function createUpdateMetaTags( $meta_for, $meta_for_id = '', $data ){
        $languages  = config()->get('app.locales');

        foreach($languages as $lang => $language){
            MetaTags::updateOrCreate(
                array(
                    'lang'          => $lang,
                    'meta_for'      => $meta_for,
                    'meta_for_id'   => $meta_for_id
                ),
                array(
                    'lang'              => $lang,
                    'meta_for'          => $meta_for,
                    'meta_for_id'       => $meta_for_id,
                    'metaTitle'         => isset($data->metaTitle)&&isset($data->metaTitle[$lang])?$data->metaTitle[$lang]:NULL,
                    'metaKeywords'      => isset($data->metaKeywords)&&isset($data->metaKeywords[$lang])?$data->metaKeywords[$lang]:NULL,
                    'metaDescription'   => isset($data->metaDescription)&&isset($data->metaDescription[$lang])?$data->metaDescription[$lang]:NULL
                )
            );
        }
    }

    static public function createMultilangualMeta( $meta_for, $meta_for_id = '', $data ){

        $languages = $data['languages'];

        if(isset($languages) && count($languages)){
            foreach( $languages as $language ){
                $lang               = $language['code'];
                $metaTitle          = isset($data[ $lang . '_metaTitle' ])? $data[ $lang . '_metaTitle' ] : '';
                $metaKeywords       = isset($data[ $lang . '_metaKeywords' ])? $data[ $lang . '_metaKeywords' ] : '';
                $metaDescription    = isset($data[ $lang . '_metaDescription' ])? $data[ $lang . '_metaDescription' ] : '';
    
                self::createMeata( 
                    $meta_for, 
                    $meta_for_id,
                    [
                        'metaTitle'         => $metaTitle,
                        'metaKeywords'      => $metaKeywords,
                        'metaDescription'   => $metaDescription
                    ],
                    $lang
                );
            }
        }
    }
    static public function delete( $meta_for, $meta_for_id = 0 ){

        $selectedMeta = MetaTags::where('meta_for',$meta_for);
        if($meta_for_id){
            $selectedMeta->where('meta_for_id',$meta_for_id);
        }
        
        return $selectedMeta->delete();
    }




    static public function get_page_meta_tags( $meta_for = 'default_settings', $replace_meta = array() ){
        $metaTags   = MetaTags::select(['metaDescription','metaKeywords','metaTitle'])
                            ->where( 'meta_for', $meta_for )
                            ->where('lang',app()->getLocale())
                            ->where('metaTitle','!=', '')
                            ->first();
        if(!$metaTags){
            $metaTags   = MetaTags::select(['metaDescription','metaKeywords','metaTitle'])
                            ->where( 'meta_for', 'default_settings' )
                            ->where('lang',app()->getLocale())
                            ->where('metaTitle','!=', '')
                            ->first();
        }

        if(count($replace_meta)){
            foreach($replace_meta as $key => $meta){
                if( $metaTags->{$key} ){
                    $metaTags->{$key} = $meta;
                }
            }
        }

        return $metaTags;
    }
}
