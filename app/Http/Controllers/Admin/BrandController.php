<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;

use App\Models\Brand;
use App\Models\BrandMeta;
use App\Models\Models;
use App\Models\MetaTags;

use DB;
use Session;


class BrandController extends Controller
{
    /**
     * Return all brand
     */
    public function brands(Request $request){

        $search     = $request->input('search');

        $query = Brand::with(array('meta'=>function($join)use($search){
            if($search){
                $join->where('title','LIKE','%'.$search.'%');
            }
        }));
        $status       = $request->input('status');        
        if( $status ){
            $query = $query->where('status',$status);
        }

        // Get all data
        $brands = $query->orderBy('id','DESC')->paginate(18);

        return view('manager.brands.index')->with('brands',$brands);
    }

    /**
     * Return add
     */
    public function add(){

        return view('manager.brands.add');
    }

    /**
     * Return edit
     */
    public function edit(Request $request){
        $id     = $request->id;

        $brand = Brand::with(array('metas','metaTags'))->where('id',$id)->first();

        return view('manager.brands.edit')->with('brand',$brand);
    }

    // Insert brand
    public function save( Request $request ){
        $brand_id   = $request->input('id');
        $title      = $request->input('title');
        // flush message
        $flush_massage = $brand_id?'Brand update successfully':'Brand added successfully';
        
        $attr = $request->validate([
            'title.*'   => 'required|string|max:255',
            'slug'      => 'required|string|unique:brand,slug,'.$brand_id,
            'image'      => 'nullable|mimes:svg,png,jpg,jpeg|max:2048', //,csv,txt,xlx,xls,pdf
            'status'    => 'required'
        ]);
        
        // Image process
        $image = UploadController::upload('image', $request);
        if($request->input('old_image') && $request->hasFile('image')){
            unlink(public_path() . '/uploads/' . $request->input('old_image'));
        }

        
        $brand = Brand::updateOrCreate(
            ['id'=>$brand_id],
            [
                'slug'      => $request->input('slug'),
                'image'     => $image?:$request->input('old_image'),
                'status'    => (int)$request->input('status')
            ]
        );
        $brand_id = $brand->id;

        $brandMetaTags = MetaTagsController::createUpdateMetaTags( 'brand', $brand_id, $request );

        insert_update_meta( $request, 'brand_id', $brand_id, array('title'), BrandMeta::class );
        
        // set flush message
        Session::flash('message', $flush_massage);
        
        return redirect('/manager/brands');
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
            $brand = Brand::where('id',$id);

            if( $brand->count() ){

                $brandImage = $brand->first()->image;
                if($brandImage && is_file(public_path() . '/uploads/' . $brandImage)){
                    unlink( public_path() . '/uploads/' . $brandImage );
                }

                $brand->delete();

                BrandMeta::where('brand_id',$id)->delete();
                MetaTags::where('meta_for_id',$id)->where('meta_for','brand')->delete();
                // Delete also model which is related of brands
                Models::where('brand_id',$id)->delete();
            }
        }

        // set flush message
        Session::flash('message', 'Brand deleted successfully');
        
        return redirect('/manager/brands');
    }


    /**
     * getBrand
     * @param request brand id
     * @return json response brand
     */
    public function getBrand(Request $request){
        $brand_id = (int)$request->input('id');

        if( $brand_id ){
            $brand = (array) Brand::where('id',$brand_id)->select('id','slug','slugLang','image','status')->first();

            if($brand){
                $brand_metas= BrandMeta::where('brand_id',$brand_id)->select('lang','title')->get();
                $metas = [];
                if($brand_metas){
                    foreach($brand_metas as $meta){
                        $metas[$meta->lang.'_title'] = $meta->title;
                    }
                }
                $brand_meta_tags = MetaTags::where('meta_for','brand')->where('meta_for_id', $brand_id)->get();
                $meta_tags = [];
                if( $brand_meta_tags ){
                    foreach($brand_meta_tags as $meta){
                        $meta_tags[$meta->lang . '_metaTitle'] = $meta->metaTitle;
                        $meta_tags[$meta->lang . '_metaKeywords'] = $meta->metaKeywords;
                        $meta_tags[$meta->lang . '_metaDescription'] = $meta->metaDescription;
                    }
                }

                $brand = array_merge($brand, $metas, $meta_tags);
            }

            return response()->json($brand);
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

        $brands =   DB::table('brand')
                    ->leftJoin('brand_meta',function($join)use($lang){
                        $join->on('brand.id','=','brand_meta.brand_id')
                        ->where('lang',$lang);
                    })
                    ->where('brand.status','1')
                    ->select('brand.id','brand.id as value','brand_meta.title as label')
                    ->orderBy('brand_meta.title','ASC')
                    ->get();

        return response()->json(['brands'=>$brands]);
    }
}
