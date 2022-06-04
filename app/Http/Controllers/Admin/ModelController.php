<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;

use App\Models\Models;
use App\Models\ModelsMeta;
use App\Models\Brand;

use DB;
use Session;

class ModelController extends Controller
{
    /**
     * Return all model
     */
    public function models(Request $request){

        $title          = $request->input('title');
        $brand_id    = $request->input('brand');

        $query = Models::with(
            array(
                'meta'=>function($join)use($title){
                    if($title){
                        $join->where('title','LIKE','%'.$title.'%');
                    }
                },
                'brand'=>function($join)use($brand_id){
                    $join = $join->with(['meta']);
                    if($brand_id){
                        $join->where('id',$brand_id);
                    }
                }
            )
        );
        $status       = $request->input('status');        
        if( $status ){
            $query = $query->where('status',$status);
        }

        // Get all data
        $models = $query->orderBy('id','DESC')->paginate(18);


        // Brand for filter search
        $brands = Brand::orderBy('id','DESC')->get();

        return view('manager.models.index')->with('models',$models)->with('brands',$brands);
    }
    /**
     * Return add
     */
    public function add(){

        $brands = Brand::with(['meta'])->get();

        return view('manager.models.add')->with('brands',$brands);
    }

    /**
     * Return edit
     */
    public function edit(Request $request){
        $id     = $request->id;
        $brands = Brand::with(['meta'])->get();
        $model  = Models::with(array('metas','brand'))->where('id',$id)->first();

        return view('manager.models.edit')
                        ->with('brands',$brands)
                        ->with('model',$model);
    }

    // Insert brand
    public function save( Request $request ){
        $model_id   = $request->input('id');
        $model      = $request->input('model');
        $model_id      = $request->input('brand');
        $status      = $request->input('status');
        // flush message
        $flush_massage = $model_id?'Model update successfully':'Model added successfully';
        
        $attr = $request->validate([
            'model'     => 'required|string|max:255',
            'brand'     => 'required|string',
            'status'    => 'required'
        ]);
                
        $brand = Models::updateOrCreate(
            ['id'=>$model_id],
            [
                'model'      => $request->input('model'),
                'model_id'      => $request->input('brand'),
                'status'    => (int)$request->input('status')
            ]
        );
        // set flush message
        Session::flash('message', $flush_massage);
        
        return redirect('/manager/models');
    }

    /**
     * Delete brand
     * @param int id
     * @return json bool
     */
    public function delete( Request $request ){
        $id    = $request->id;

        $ids = !is_array($id)? [$id] : $id;

        foreach($ids as $id){
            Models::find($id)->delete();
        }

        // set flush message
        Session::flash('message', 'Model deleted successfully');
        
        return redirect('/manager/models');
    }


    /**
     * getModel
     * @param request model id
     * @return json response model
     */
    public function getModel(Request $request){
        $model_id = (int)$request->input('id');

        if( $model_id ){
            $model = (array) DB::table('model')->where('id',$model_id)->select('id','model','brand_id','status')->first();

            if($model){
                $model_metas= DB::table('model_meta')->where('model_id',$model_id)->select('lang','title')->get();
                $metas      = [];
                if($model_metas){
                    foreach($model_metas as $meta){
                        $metas[$meta->lang.'_title'] = $meta->title;
                    }
                }
                $model = array_merge($model, $metas);
            }

            return response()->json($model);
        }
    }


    /**
     * fetchData
     * 
     * @param Request $request
     * @return json
     */
    public function fetchData(Request $request){

        $query = Models::with(['meta'])->where('brand_id',$request->brand_id)->where('status',1)->get();

        return response()->json($query);
    }
}
