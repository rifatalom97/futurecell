<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Size;
use DB;
use Session;

class SizeController extends Controller
{
    /**
     * Return all size
     */
    public function sizes(Request $request){
        $size_id    = isset($request->id)?$request->id:0;
        $size       = Size::where('id',$size_id)->first();

        $sizes  = Size::orderBy('id','DESC')->paginate(12);

        return view('manager.sizes.index')->with('sizes',$sizes)->with('size',$size);
    }

    // Insert size
    public function save( Request $request ){
        $size_id        = $request->input('id');

        Size::updateOrCreate(
            ['id'=> $size_id ],
            $request->only('status','unite','value')
        );

        // set flush message
        Session::flash('message', 'Size saved successfully');
        
        return redirect('/manager/sizes');
    }

    /**
     * Delete size
     * @param int id
     * @return json bool
     */
    public function delete( Request $request ){
        $size_id    = $request->id;

        $size_ids = !is_array($size_id)? [$size_id] : $size_id;

        foreach($size_ids as $id){
            Size::find($id)->delete();
        }

        // set flush message
        Session::flash('message', 'Size deleted successfully');
        
        return redirect('/manager/sizes');
    }


    /**
     * getSize
     * @param request size id
     * @return json response size
     */
    public function getSize(Request $request){
        $size_id = (int)$request->input('id');
        if( $size_id ){
            $size = (array) DB::table('size')->where('id',$size_id)->select('id','unite','value','status')->first();
            return response()->json($size);
        }
    }


    /**
     * fetchData
     * 
     * @param Request $request
     * @return json
     */
    public function fetchData(Request $request){

        $sizes = DB::table('size')->where('size.status','1')
                    ->select('size.id','size.id as value', DB::raw("CONCAT(size.value,' ',size.unite) as label"))
                    ->orderBy('size.sizeUnite','ASC')
                    ->get();

        return response()->json(['sizes'=>$sizes]);
    }
}
