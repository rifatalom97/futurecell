<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Color;
use App\Models\ColorMeta;
use DB;
use Session;

class ColorController extends Controller
{
    /**
     * Return all color
     */
    public function colors(Request $request){
        $color_id    = isset($request->id)?$request->id:0;
        $color       = Color::with(['meta'])->where('id',$color_id)->first();

        $colors  = Color::with(['meta'])->orderBy('id','DESC')->paginate(12);

        return view('manager.colors.index')->with('colors',$colors)->with('color',$color);
    }

    // Insert color
    public function save( Request $request ){
        $color_id        = $request->input('id');

        $color = Color::updateOrCreate(
            ['id'=> $color_id ],
            $request->only('code','status')
        );

        $color_id = $color->id;

        insert_update_meta( $request, 'color_id', $color_id, array('title'), ColorMeta::class );

        // set flush message
        Session::flash('message', 'Color saved successfully');
        
        return redirect('/manager/colors');
    }

    /**
     * Delete color
     * @param int id
     * @return json bool
     */
    public function delete( Request $request ){
        $color_id    = $request->id;

        $color_ids = !is_array($color_id)? [$color_id] : $color_id;

        foreach($color_ids as $id){
            Color::find($id)->delete();
            ColorMeta::where('color_id',$id)->delete();
        }

        // set flush message
        Session::flash('message', 'Color deleted successfully');
        
        return redirect('/manager/colors');
    }


    /**
     * getColor
     * @param request color id
     * @return json response color
     */
    public function getColor(Request $request){
        $color_id = (int)$request->input('id');

        if( $color_id ){
            $color = (array) DB::table('color')->where('id',$color_id)->select('id','code','status')->first();

            if($color){
                $color_metas= DB::table('color_meta')->where('color_id',$color_id)->select('lang','title')->get();
                $metas = [];
                if($color_metas){
                    foreach($color_metas as $meta){
                        $metas[$meta->lang.'_title'] = $meta->title;
                    }
                }
                
                $color = array_merge($color, $metas);
            }

            return response()->json($color);
        }
    }


    /**
     * fetchData
     * 
     * @param Request $request
     * @return json
     */
    public function fetchData(Request $request){
        $lang       = $request->input('lang') ? : 'en';

        $colors = DB::table('color')->join('color_meta',function($join)use($lang){
                    $join->on('color.id','=','color_meta.color_id')->where('lang',$lang);
                    })->where('color.status','1')->select('color.id','color.id as value','color.code','color_meta.title as label')
                    ->orderBy('color_meta.title','ASC')
                    ->get();

        return response()->json(['colors'=>$colors]);
    }
}
