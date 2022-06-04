<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\OptionsController;

use App\Models\Category;

use Session;

class StaticPageController extends Controller
{
    public function home( Request $request ){
 
        $option_type    = 'home_page';

        // Save update
        if($request->isMethod('post')){
            // deta
            $data = array();

            // Banners
            if($request->input('banner')){
                $filterd_banners = array();
                if(count($request->banner)){
                    foreach($request->banner as $item){
                        if( !$item['old'] && !$item['new'] ){
                            continue;
                        }
                        if($item['old'] != $item['new']){
                            UploadController::finishFileUploadProcess($item['new'],$item['old']);
                        }

                        // Check again if new is not null
                        if( $item['new'] ){
                            $item['old'] = $item['new'];
                            $filterd_banners[] = $item;
                        }
                    }
                }
                $data['banners'] = json_encode($filterd_banners);
            }

            // Grid category
            if($request->input('grid_category') && is_array($request->input('grid_category'))){
                $filtered_grid_category = array();
                foreach($request->input('grid_category') as $item){
                    if( !$item['old_image'] && !$item['image'] ){
                        continue;
                    }
                    if($item['old_image'] != $item['image']){
                        UploadController::finishFileUploadProcess($item['image'],$item['old_image']);
                    }

                    // Check again if new is not null
                    if( $item['image'] ){
                        $item['old_image']          = $item['image'];
                        $filtered_grid_category[]    = $item;
                    }
                }
                $data['grid_category'] = json_encode($filtered_grid_category);
            }

            // hot product
            $data['hot_products'] = $request->input('hot_products')?json_encode($request->input('hot_products')):'';
            // Brands
            $data['brands'] = $request->input('brands')?json_encode($request->input('brands')):'';
            // About
            $data['about'] = $request->input('about')?json_encode($request->input('about')):'';
            

            OptionsController::saveOptions($data,$option_type);

            // Save meta tags
            MetaTagsController::createUpdateMetaTags( $option_type, 1, $request );

            // set flush message
            Session::flash('message', 'Page data saved successfully');

            return redirect()->back();
        }
        // End save update

        // Get categories
        $categories = Category::with(array('meta'))->get();

        $options    = OptionsController::getOptions( $option_type );

        $meta_tags = MetaTagsController::getMetaTags( $option_type, 1 );

        return view('manager.static_pages.home.home')
                    ->with('categories',$categories)
                    ->with('meta_tags',$meta_tags)
                    ->with('home_data',$options);
    }

    public function store( Request $request ){
        $option_type    = 'store_page';

        // Save update
        if($request->isMethod('post')){
            // deta
            $data = array();

            // Banners
            if($request->input('banner')){
                $filterd_banners = array();
                if(count($request->banner)){
                    foreach($request->banner as $item){
                        if( !$item['old'] && !$item['new'] ){
                            continue;
                        }
                        if($item['old'] != $item['new']){
                            UploadController::finishFileUploadProcess($item['new'],$item['old']);
                        }
                        // Check again if new is not null
                        if( $item['new'] ){
                            $item['old'] = $item['new'];
                            $filterd_banners[] = $item;
                        }
                    }
                }
                $data['banners'] = json_encode($filterd_banners);
            }
            OptionsController::saveOptions($data,$option_type);
            // Save meta tags
            MetaTagsController::createUpdateMetaTags( $option_type, 1, $request );
            // set flush message
            Session::flash('message', 'Page data saved successfully');
            return redirect()->back();
        }
        // End save update
        $options    = OptionsController::getOptions( $option_type );
        $meta_tags = MetaTagsController::getMetaTags( $option_type, 1 );
        return view('manager.static_pages.store.store')
                    ->with('meta_tags',$meta_tags)
                    ->with('store_data',$options);
    }

    // public function category( Request $request ){
    //     $default_data = array();
        
    //     // return $this->saveAndGetPageContent( $request, 'category_page', $default_data, false );
    //     return view('manager.static_pages.category');
    // }

    // public function brand( Request $request ){
    //     $default_data = array();
        
    //     // return $this->saveAndGetPageContent( $request, 'brand_page', $default_data, false );
    //     return view('manager.static_pages.brand');
    // }

    // public function search( Request $request ){
    //     $default_data = array();
        
    //     // return $this->saveAndGetPageContent( $request, 'search_page', $default_data );
    //     return view('manager.static_pages.search');
    // }
    
    public function contact( Request $request ){
 
        $option_type    = 'contact_page';

        // Save update
        if($request->isMethod('post')){
            // deta
            $data = array();

            // About
            $data['subjects'] = $request->input('subjects')?json_encode($request->input('subjects')):'';
            $data['admin_notification'] = $request->input('admin_notification')?json_encode($request->input('admin_notification')):'';
            
            
            OptionsController::saveOptions($data,$option_type);

            // Save meta tags
            MetaTagsController::createUpdateMetaTags( $option_type, 1, $request );

            // set flush message
            Session::flash('message', 'Contact page data saved successfully');

            return redirect()->back();
        }
        // End save update

        $options    = OptionsController::getOptions( $option_type );

        $meta_tags = MetaTagsController::getMetaTags( $option_type, 1 );

        return view('manager.static_pages.contact')
                    ->with('meta_tags',$meta_tags)
                    ->with('options',$options);
    }
    /**
     * About page
     */
    public function about( Request $request ){
 
        $option_type    = 'about_page';

        // Save update
        if($request->isMethod('post')){
            // deta
            $data = array();

            // About
            $data['title']  = $request->input('title')?json_encode($request->input('title')):'';
            $data['content'] = $request->input('content')?json_encode($request->input('content')):'';
      
            OptionsController::saveOptions($data,$option_type);

            // Save meta tags
            MetaTagsController::createUpdateMetaTags( $option_type, 1, $request );

            // set flush message
            Session::flash('message', 'About page saved successfully');

            return redirect()->back();
        }
        // End save update

        $options    = OptionsController::getOptions( $option_type );

        $meta_tags = MetaTagsController::getMetaTags( $option_type, 1 );

        return view('manager.static_pages.about')
                    ->with('meta_tags',$meta_tags)
                    ->with('options',$options);
    }
    /**
     * Terms condition page
     */
    public function terms_conditions( Request $request ){
 
        $option_type    = 'terms_conditions_page';

        // Save update
        if($request->isMethod('post')){
            // deta
            $data = array();

            // About
            $data['title'] = $request->input('title')?json_encode($request->input('title')):'';
            $data['content'] = $request->input('content')?json_encode($request->input('content')):'';
      
            OptionsController::saveOptions($data,$option_type);

            // Save meta tags
            MetaTagsController::createUpdateMetaTags( $option_type, 1, $request );

            // set flush message
            Session::flash('message', 'About page saved successfully');

            return redirect()->back();
        }
        // End save update

        $options    = OptionsController::getOptions( $option_type );

        $meta_tags = MetaTagsController::getMetaTags( $option_type, 1 );

        return view('manager.static_pages.terms')
                    ->with('meta_tags',$meta_tags)
                    ->with('options',$options);
    }






    // save and get page content
    protected function saveAndGetPageContent($request,$option_type,$default_data = array(),$use_meta_tags = true){
  
        $lang           = $request->lang;
        $result         = array();

        // Save update
        if($request->isMethod('post')){
            
            $data = $default_data;
            
            $filterd_banners    = [];
            $banners            = $request->banners;
            if($banners){
                foreach($banners as $banner){
                    $image          = isset($banner['image'])?$banner['image']:'';
                    $newImage       = isset($banner['newImage'])?$banner['newImage']:'';
                    $url            = isset($banner['url'])?$banner['url']:'';
                    if($image != $newImage){
                        UploadController::finishFileUploadProcess($newImage,$image);
                    }
                    if(!$newImage || !$banner['isVisible']){
                        continue;
                    }
                    $filterd_banners[] = array(
                        'image'     => $newImage,
                        'newImage'  => $newImage,
                        'url'       => $url,
                        'isVisible' => true
                    );
                }
            }

            $data = array_merge($data, ['banners'=>$filterd_banners]);

            $filteredData = [];
            foreach($data as $key => $value ){
                $filteredData[$key] = json_encode($value);
            }
            OptionsController::saveOptions($filteredData,$option_type);

            if($use_meta_tags){
                MetaTagsController::runAndGetMLTMetas( $option_type, 0, $request->all() );
            }
        }
        // End save update

        $options        = OptionsController::getOptions( $option_type );

        $metaTags       = $use_meta_tags?MetaTagsController::getMetaTagsFilterd( $option_type ):[];
        
        $result = array_merge($options,$metaTags);

        return response()->json( ['result' => $result ] );
    }
}
