<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;

use App\Models\Posts;
use App\Models\PostsMeta;
use DB;
use Session;

class PageController extends Controller
{
    /**
     * Return all page
     */
    public function pages(Request $request){

        $search     = $request->input('search');
        $status     = $request->input('status');

        $query = Posts::with(['meta'=>function($query)use($search){
            if($search){
                $query->where('title','LIKE','%'.$search.'%');
            }
        }]);
        if( $status !== NULL ){
            $query = $query->where('status',$status);
        }
        $pages = $query->where('type','page')->orderBy('id','DESC')->paginate(18);

        return view('manager.dynamic_pages.index')
                ->with('pages',$pages);
    }

    /**
     * Create page
     */
    public function create(){
        return view( 'manager.dynamic_pages.create' );
    }
    /**
     * Edit page
     */
    public function edit(Request $request){
        $page_id = $request->id;

        $page = Posts::with(['metas','metaTags'=>function($join){
            $join->where('meta_for','page');
        }])->where('id',$page_id)->first();


        return view( 'manager.dynamic_pages.edit' )
                ->with('page',$page);
    }
    // Insert page
    public function save( Request $request ){
        $post_id            = $request->input('id');
        $defaultData        = $request->only('status','slug');

        // flush message
        $flush_massage = $post_id?'Page update successfully':'Page added successfully';
        
        $attr = $request->validate([
            'title.*'       => 'required|string|max:255',
            'slug'          => 'required|string|unique:posts,slug,'.$post_id,
            'sub_title.*'   => 'nullable|string|max:255',
            'content.*'     => 'nullable|string',
            'thumbnail'     => 'nullable|mimes:png,jpg,jpeg|max:2048', //,csv,txt,xlx,xls,pdf
            'status'        => 'required'
        ]);

        // Image process
        $thumbnail = UploadController::upload('thumbnail', $request);
        if($request->input('old_thumbnail') && $request->hasFile('thumbnail')){
            unlink(public_path() . '/uploads/' . $request->input('old_thumbnail'));
        }

        // Replace image with new image
        $defaultData['thumbnail']   = $thumbnail?:$request->input('old_thumbnail');
        $defaultData['type']        = 'page';
        $page = Posts::updateOrCreate(
            ['id' => $post_id],
            $defaultData
        );
        $post_id = $page->id;

        MetaTagsController::createUpdateMetaTags( 'page', $post_id, $request );
        insert_update_meta( $request, 'post_id', $post_id, array('title','sub_title','content'), PostsMeta::class );
        
        // set flush message
        Session::flash('message', $flush_massage);
        
        return redirect('/manager/pages');
    }
  
    /**
     * Delete page
     * @param int id
     * @return json bool
     */
    public function delete( Request $request ){
        $deleting_id    = $request->id;

        $deleting_id = !is_array($deleting_id)? [$deleting_id] : $deleting_id;

        foreach($deleting_id as $id){
            $page = Posts::where('id',$id);
            if( $page->count() ){

                $thumbnail = $page->first()->thumbnail;
                if($thumbnail && is_file(public_path() . '/uploads/' . $thumbnail)){
                    unlink( public_path() . '/uploads/' . $thumbnail );
                }

                $page->delete();

                PostsMeta::where('post_id',$id)->delete();
                MetaTagsController::delete('page',$id);
            }
        }

        // set flush message
        Session::flash('message', 'Page deleted successfully');
        
        return redirect('/manager/pages');
    }
}
