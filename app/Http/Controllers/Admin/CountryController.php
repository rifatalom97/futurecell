<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\CountryMeta;
use DB;

class CountryController extends Controller
{
    public function updateCreateCountry(Request $request){
        $countryId      = $request->input('id');
        $languages      = $request->input('languages');
        $defaultData    = $request->only('code','status');

        $country = Country::updateOrCreate(
            ['id'=>$countryId],
            $defaultData
        )->toArray();
        $countryId = $country['id'];

        $country_meta  = $this->insertUpdateGetMeta( $countryId, $request->all() );

        $result = array_merge($country, $country_meta);

        return response()->json($result);
    }

    private function insertUpdateGetMeta( $country_id, $data ){
        $languages = $data['languages'];

        if($languages && count($languages)){
            // First run delete functions
            CountryMeta::where('country_id',$country_id)->delete();
            foreach( $languages as $language ){
                $lang = $language['code'];
                CountryMeta::create([ 
                    'country_id'   => $country_id, 
                    'lang'      => $lang,
                    'name'     => $data[$lang.'_name']
                ]);
            }
        }

        $metas = CountryMeta::where('country_id',$country_id)->get();
        $reOrderMeta = [];
        if($metas){
            foreach( $metas as $meta ){
                $reOrderMeta[$meta->lang . '_name'] = $meta->name;
            }
        }

        return $reOrderMeta;
    }


    public function getCountries(Request $request){
        $lang = $request->input('lang');

        $result = DB::table('countries')
                    ->join('countries_meta','countries_meta.country_id','countries.id')
                    ->where('countries_meta.lang',$lang)
                    ->select('countries.id','countries.code','countries.status','countries.is_default','countries_meta.name')
                    ->orderBy('countries_meta.name','ASC')
                    ->get();

        return response()->json(['countries'=>$result]);
    }
    public function getCountryMeta(Request $request){
        $country_id = $request->input('country_id');
        $result = DB::table('countries_meta')
                    ->where('country_id',$country_id)
                    ->select('country_id','lang','name')
                    ->get();
        $new_meta = array();            
        if($result){
            foreach($result as $meta){
                $new_meta[$meta->lang . '_name'] = $meta->name;
            }
        }
        return response()->json(['country_meta'=>$new_meta]);
    }

    public function changeCountryDefault(Request $request){
        $country_id = $request->input('country_id');
        $db = DB::table('countries');
        $db->update(['is_default' => false]);
        $db->where('id',$country_id)->update(['is_default' => true]);

        return response()->json(['result'=>true]);
    }
}
