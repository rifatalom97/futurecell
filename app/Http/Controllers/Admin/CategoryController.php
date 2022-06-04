<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;

use App\Models\Category;
use App\Models\CategoryMeta;

use DB;
use Session;

class CategoryController extends Controller
{
    /**
     * Return all category
     */
    public function categories(Request $request){

        $search     = $request->input('search');

        $query = Category::with(array('meta'=>function($join)use($search){
            if($search){
                $join->where('title','LIKE','%'.$search.'%');
            }
        }));
        $status       = $request->input('status');        
        if( $status ){
            $query = $query->where('status',$status);
        }

        // Get all data
        $categories = $query->orderBy('id','DESC')->paginate(18);

        return view('manager.category.index')->with('categories',$categories);
    }
    /**
     * Return add
     */
    public function add(){

        return view('manager.category.add');
    }
    /**
     * Return edit
     */
    public function edit(Request $request){
        $id     = $request->id;

        $category = Category::with(array('metas','metaTags'))->where('id',$id)->first();

        return view('manager.category.edit')->with('category',$category);
    }

    public function save( Request $request ){
        $category_id   = $request->input('id');
        $title      = $request->input('title');
        $image      = $request->file('image');
        // flush message
        $flush_massage = $category_id?'Category update successfully':'Category added successfully';
        
        $attr = $request->validate([
            'title.*'   => 'required|string|max:255',
            'slug'      => 'required|string|unique:category,slug,'.$category_id,
            'image'      => 'nullable|mimes:png,jpg,jpeg|max:2048', //,csv,txt,xlx,xls,pdf
            'status'    => 'required'
        ]);
        
        // Image process
        $image = UploadController::upload('image', $request);
        if($request->input('old_image') && $request->hasFile('image')){
            $file_path = public_path() . '/uploads/' . $request->input('old_image');
            if(is_file($file_path)){
                unlink($file_path);
            }
        }

        
        $category = Category::updateOrCreate(
            ['id'=>$category_id],
            [
                'slug'      => $request->input('slug'),
                'image'     => $image?:$request->input('old_image'),
                'status'    => (int)$request->input('status')
            ]
        );
        $category_id = $category->id;

        MetaTagsController::createUpdateMetaTags( 'category', $category_id, $request );

        insert_update_meta( $request, 'category_id', $category_id, array('title'), CategoryMeta::class );
        
        // set flush message
        Session::flash('message', $flush_massage);
        
        return redirect('/manager/category');
    }

     /**
     * Delete category
     * @param int id
     * @return json bool
     */
    public function delete( Request $request ){
        $id    = $request->id;

        $ids = !is_array($id)? [$id] : $id;

        foreach($ids as $id){
            $category = Category::where('id',$id);

            if( $category->count() ){

                $categoryImage = $category->first()->image;
                if($categoryImage && is_file(public_path() . '/uploads/' . $categoryImage)){
                    unlink( public_path() . '/uploads/' . $categoryImage );
                }

                $category->delete();

                CategoryMeta::where('category_id',$id)->delete();
                MetaTagsController::delete('category',$id);
            }
        }

        // set flush message
        Session::flash('message', 'Brand deleted successfully');
        
        return redirect('/manager/category');
    }


    /**
     * Check is exists or not
     */
    public function isCategoryExists(Request $request){

        $id     = $request->input('id')?:0;
        $lang   = $request->input('lang');
        $title   = $request->input('title');

        $query = CategoryMeta::where('lang',$lang)
                        ->where('title','LIKE',$title);
        if( $id ){
            $query = $query->where('category_id','!=',$id);
        }
        
        $result = $query->count();

        echo json_encode(['result'=>(bool)$result]);
    }


    // Insert category
    public function insertOrUpdateCategory( Request $request ){
        $languages      = $request->input('languages');
        $categoryId     = $request->input('id');
        $newImage       = $request->input('newImage');
        $image          = $request->input('image');
        $defaultData    = $request->only('parent','status','image','slug','slugLang');

        // Image process
        UploadController::finishFileUploadProcess($newImage,$image);
        
        // Replace image with new image
        $defaultData['image'] = $newImage;

        $category = Category::updateOrCreate(
            ['id'=>$categoryId],
            $defaultData
        )->toArray();
        $categoryId = $category['id'];

        $categoryMetaTags      = MetaTagsController::runAndGetMLTMetas( 'category', $categoryId, $request->all() );
        $categorymetas         = $this->insertUpdateGetMeta( $categoryId, $request->all() );
        

        $result = array_merge($category, $categorymetas, $categoryMetaTags);

        return response()->json($result);
    }
    private function insertUpdateGetMeta( $categoryId, $data ){
        $languages = $data['languages'];
        if($languages && count($languages)){
            // First run delete functions
            CategoryMeta::where('category_id',$categoryId)->delete();
            foreach( $languages as $language ){
                CategoryMeta::create([ 'category_id' => $categoryId, 'lang'=>$language['code'] ,'title' => $data[$language['code'].'_title'] ]);
            }
        }

        $metas = CategoryMeta::where('category_id',$categoryId)->get();
        $reOrderMeta = [];
        if($metas){
            foreach( $metas as $meta ){
                $reOrderMeta[$meta->lang . '_title'] = $meta->title;
            }
        }

        return $reOrderMeta;
    }


    /**
     * Delete category
     * @param int id
     * @return json bool
     */
    public function deleteCategory( Request $request ){
        $deleting_id    = $request->input('id');

        $deleting_id = !is_array($deleting_id)? [$deleting_id] : $deleting_id;

        foreach($deleting_id as $id){
            $category = DB::table('category')->where('id',$id);

            if( $category->count() ){

                $categoryImage = $category->first()->image;
                if($categoryImage && is_file(public_path() . '/images/' . $categoryImage)){
                    unlink( public_path() . '/images/' . $categoryImage );
                }

                $category->delete();

                CategoryMeta::where('category_id',$id)->delete();
            }
        }

        return response()->json(['result'=>(bool)$deleting_id]);
    }


    /**
     * getCategory
     * @param request category id
     * @return json response category
     */
    public function getCategory(Request $request){
        $category_id = (int)$request->input('id');

        if( $category_id ){
            $category = (array) DB::table('category')->where('id',$category_id)->select('id','parent','slug','slugLang','image','status')->first();

            if($category){
                $category_metas= DB::table("category_meta")->where('category_id',$category_id)->select('lang','title')->get();
                $metas = [];
                if($category_metas){
                    foreach($category_metas as $meta){
                        $metas[$meta->lang.'_title'] = $meta->title;
                    }
                }
                $category_meta_tags = DB::table('meta_tags')->where('meta_for','category')->where('meta_for_id', $category_id)->get();
                $meta_tags = [];
                if( $category_meta_tags ){
                    foreach($category_meta_tags as $meta){
                        $meta_tags[$meta->lang . '_metaTitle'] = $meta->metaTitle;
                        $meta_tags[$meta->lang . '_metaKeywords'] = $meta->metaKeywords;
                        $meta_tags[$meta->lang . '_metaDescription'] = $meta->metaDescription;
                    }
                }

                $category = array_merge($category, $metas, $meta_tags);
            }

            return response()->json($category);
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
        $witoutId   = $request->input('witoutId') ? : 0;

        $categories = DB::table('category')
                        ->join('category_meta',function($join)use($lang){
                            $join->on('category.id','=','category_meta.category_id')
                                ->where('lang',$lang);
                        })
                        ->where('category.status','1')
                        ->where('category.id','!=',$witoutId)
                        ->select('category.id','category.id as value','category_meta.title as label')
                        ->groupBy('category.id')
                        ->orderBy('category_meta.title','ASC')
                        ->get();

        return response()->json(['categories'=>$categories]);
    }
    /**
     * fetchCategoryUrl
     */
    public function fetchCategoryUrl(Request $request){
        $categoryId     = $request->input('categoryId');
        $menu_details   = [];

        if($categoryId){
            $category = Category::where('id',$categoryId)->where('status',1)->first();
            if($category){
                $categoryMeta = CategoryMeta::where('category_id',$categoryId)->get();
                $menu_details['id']     = $categoryId;
                $menu_details['type']   = 'category';
                $menu_details['href']   = '/category/' . $category->slug;
                $menu_details['target_selfe']   = true;
                if($categoryMeta){
                    foreach($categoryMeta as $meta){
                        $menu_details[$meta->lang . '_label'] = $meta->title;
                    }
                }
            }
        }

        return response()->json(['menu'=>$menu_details]);
    }
}
