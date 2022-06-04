<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Admin\MetaTagsController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\ProductRelationController;

use App\Models\Product;
use App\Models\ProductMeta;
use App\Models\ProductSecurities;
use App\Models\ProductDeliveryOptions as DeliveryOptions;
use App\Models\ProductDeliveryOptionsMeta as DeliveryOptionsMeta;

use App\Http\Controllers\Admin\OptionsController;
use App\Models\Options;

use App\Models\Brand;
use App\Models\Models;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;

use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductSize;

use DB;
use Session;

class ProductController extends Controller
{
    /**
     * Return all product
     */
    public function products(Request $request){

        $lang       = 'he';
        $search     = $request->input('search');

        $query = Product::with(['meta'=>function($query)use($lang, $search){
            if($search){
                $query->where('title','LIKE','%'.$search.'%');
            }else{
                $query->where('lang',$lang);
            }
        }]);

        $status       = $request->input('status');
        if( $status ){
            $query = $query->where('status',$status);
        }

        $products = $query->orderBy('id','DESC')->paginate(20);

        return view('manager.products.products.index')
                    ->with('products',$products);
    }

    /**
     * AddProduct
     */
    public function add(){

        $brands     = Brand::with(['meta'])->where('status',1)->get();
        // $brands = Models::with(['meta'])->where('status',1)->get();
        $sizes      = Size::where('status',1)->get();
        $categories = Category::with(['meta'])->where('status',1)->get();
        $colors     = Color::with(['meta'])->where('status',1)->get();

        return view('manager.products.products.add')
                    ->with('brands',$brands)
                    ->with('sizes',$sizes)
                    ->with('categories',$categories)
                    ->with('colors',$colors);
    }

    /**
     * AddProduct
     */
    public function edit( Request $request ){
        $product_id = $request->id; 

        $product = Product::with(['metas','sizes'=>function($join){
            $join->with(['size']);
        },'colors'=>function($join){
            $join->with(['color']);
        },'categories'=>function($join){
            $join->with(['category']);
        }])->where('id',$product_id)->first();

        

        $brands     = Brand::with(['meta'])->where('status',1)->get();
        $models     = Models::with(['meta'])
                        ->where('status',1)
                        ->where('brand_id',$product->brand)
                        ->get();
        $sizes      = Size::where('status',1)->get();
        $categories = Category::with(['meta'])->where('status',1)->get();
        $colors     = Color::with(['meta'])->where('status',1)->get();

        

        return view('manager.products.products.edit')
                    ->with('brands',$brands)
                    ->with('models',$models)
                    ->with('sizes',$sizes)
                    ->with('categories',$categories)
                    ->with('colors',$colors)
                    ->with('product',$product);
    }

    // Save product
    public function save( Request $request ){
        $product_id      = $request->input('id');
        
        // Validation
        $attr = $request->validate([
            'title.*'               => 'required|string|max:255',
            'slug'                  => 'required|string|unique:products,slug,'.$product_id,
            'model_number'          => 'required',
            'sku'                   => 'required',
            'barcode'               => 'required',
            'regular_price'         => 'nullable',
            'price'                 => 'required',
            'quantity'              => 'required',
            'short_description.*'   => 'nullable',
            'description.*'         => 'nullable',
            'status'                => 'required',
            'brand'                 => 'required',
            'model'                 => 'nullable'
        ]);


        // Image process
        $thumbnail      = $request->input('thumbnail');
        $old_thumbnail  = $request->input('old_thumbnail');
        UploadController::finishFileUploadProcess($thumbnail,$old_thumbnail);
        // Replace image with new image
        $attr['thumbnail'] = $thumbnail;

        // Gallery
        $gallery = $request->input('gallery');
        $filterd_gallery = [];
        if($gallery && count($gallery)){
            foreach($gallery as $item){
                $image        = isset($item['image'])?$item['image']:'';
                $old_image     = isset($item['old_image'])?$item['old_image']:'';
                if($image != $old_image){
                    UploadController::finishFileUploadProcess($image,$old_image);
                }
                $filterd_gallery[] = array(
                    'image'         => $image,
                    'old_image'      => $old_image
                );
            }
        }
        $attr['gallery'] = json_encode($filterd_gallery);
        // End gallry

        // Reviews options
        $reviews = $request->input('reviews');
        if($reviews){
            if(isset($reviews['gridImageText']['items'])){
                $filterd_items = [];
                foreach($reviews['gridImageText']['items'] as $item){
                    
                    $image      = isset($item['image'])?$item['image']:'';
                    $old_image  = isset($item['old_image'])?$item['old_image']:'';
                    if($image != $old_image){
                        UploadController::finishFileUploadProcess($image,$old_image);
                    }
                    if( !$image ){
                        continue;
                    }
                    $filterd_items[] = array(
                        'image'     => $image,
                        'details'   => (isset($item['details'])?$item['details']:null)
                    );
                }
                $reviews['gridImageText']['items'] = $filterd_items;
            }

            if(isset($reviews['gridGalleryReviews']['items'])){
                $filterd_items = [];
                foreach($reviews['gridGalleryReviews']['items'] as $item){
                    $thumb1      = isset($item['thumb1'])?$item['thumb1']:'';
                    $old_thumb1  = isset($item['old_thumb1'])?$item['old_thumb1']:'';
                    if($thumb1 != $old_thumb1){
                        UploadController::finishFileUploadProcess($thumb1,$old_thumb1);
                    }
                    $thumb2      = isset($item['thumb2'])?$item['thumb2']:'';
                    $old_thumb2  = isset($item['old_thumb2'])?$item['old_thumb2']:'';
                    if($thumb2 != $old_thumb2){
                        UploadController::finishFileUploadProcess($thumb2,$old_thumb2);
                    }
                    $thumb3      = isset($item['thumb3'])?$item['thumb3']:'';
                    $old_thumb3  = isset($item['old_thumb3'])?$item['old_thumb3']:'';
                    if($thumb3 != $old_thumb3){
                        UploadController::finishFileUploadProcess($thumb3,$old_thumb3);
                    }
                    if( !$thumb1 ){
                        continue;
                    }
                    $filterd_items[] = array(
                        'thumb1'     => $thumb1,
                        'thumb2'     => $thumb2,
                        'thumb3'     => $thumb3,
                    );
                }
                $reviews['gridGalleryReviews']['items'] = $filterd_items;
            }

            $attr['options'] = json_encode($reviews);
        }
        // End reviews

        // Save or update product
        $product        = Product::updateOrCreate( ['id' => $product_id], $attr );
        $product_id     = $product->id;


        // insert or update relational datas
        $categories = $request->input('categories');
        $sizes      = $request->input('sizes');
        $colors     = $request->input('colors');
        ProductRelationController::insertUpdateRelations($product_id,
            array(
                'product_category'      => $categories,
                'product_size'          => $sizes,
                'product_color'         => $colors,
            )
        );
        // end

        // Insert product meta
        insert_update_meta( 
            $request, 
            'product_id', 
            $product_id, 
            array('title','short_description','description'), 
            ProductMeta::class 
        );
      
        // set flush message
        Session::flash('message', 'Product saved successfully');
        
        return redirect('/manager/products');
    }

    // Filter options
    private function filterOptionImageOption( $value, $visible = true ){
        $data = json_decode($value);
        
        if($data){
            if(isset($data->one)){
                $newImage = $visible?$data->one->newImage:'';
                UploadController::finishFileUploadProcess($newImage,$data->one->image);
                $data->one->image = $newImage;
            }
            if(isset($data->two)){
                $newImage = $visible?$data->two->newImage:'';
                UploadController::finishFileUploadProcess($newImage,$data->two->image);
                $data->two->image = $newImage;
            }
            if(isset($data->three)){
                $newImage = $visible?$data->three->newImage:'';
                UploadController::finishFileUploadProcess($newImage,$data->three->image);
                $data->three->image = $newImage;
            }
        }

        return json_encode($data);
    }
    private function filterOptionImagesWithDetails( $value, $visible = true ){
        $data = json_decode($value);

        $filterd_data = array();
        if($data && count($data)){
            foreach($data as $item){
                $newImage = $visible?$item->newImage:'';
                UploadController::finishFileUploadProcess($newImage,$item->image);
                $item->image  = $newImage;

                $newLogo = $visible?$item->newLogo:'';
                UploadController::finishFileUploadProcess($newLogo,$item->logo);
                $item->logo  = $newLogo;

                $filterd_data[] = $item;
            }
        }

        return json_encode($filterd_data);
    }
    // End


    /**
     * Delete product
     * @param int id
     * @return json bool
     */
    public function deleteProduct( Request $request ){
        $deleting_id    = $request->id;
        $result         = false;
        $deleting_id    = !is_array($deleting_id)? [$deleting_id] : $deleting_id;


        foreach($deleting_id as $id){
            $query = Product::where('id',$id);

            $product = $query->first();

            if( (bool)$product ){
                // $result = $deleting_id;
                if($product->thumbnail && is_file(public_path() . '/uploads/' . $product->thumbnail)){
                    unlink( public_path() . '/uploads/' . $product->thumbnail );
                }
                if($product->gallery){
                    $gallery = json_decode($product->gallery);
                    if($gallery){
                        foreach($gallery as $item){
                            if($item->image && is_file(public_path() . '/uploads/' . $item->image)){
                                unlink( public_path() . '/uploads/' . $item->image );
                            }
                        }
                    }
                }

                

                if($product->options){
                    $options = json_decode($product->options);
                    if(isset($options->gridImageText) && count($options->gridImageText->items)){
                        foreach($options->gridImageText->items as $item){
                            if(isset($item->image) && $item->image && is_file(public_path() . '/uploads/' . $item->image) ){
                                unlink( public_path() . '/uploads/' . $value->image1 );
                            }
                        }
                    }
                }

                $query->delete();
                ProductMeta::where('product_id',$id)->delete();
                ProductCategory::where('product_id',$id)->delete();
                ProductColor::where('product_id',$id)->delete();
                ProductSize::where('product_id',$id)->delete();

                // set flush message
                Session::flash('message', 'Product deleted successfully');
            }
        }

        return redirect('/manager/products');
    }

    public function removeOptionFile( Request $request ){
        $files = $request->input('files');
        if(is_array($files)){
            foreach($files as $file){
                if($file && is_file(public_path() . '/uploads/' . $file) ){
                    unlink( public_path() . '/uploads/' . $file );
                }
            }
        }
    }


    /**
     * getProduct
     * @param request product id
     * @return json response product
     */
    public function getProduct(Request $request){
        $product_id = (int)$request->input('id');

        if( $product_id ){
            $product = (array) DB::table('products')->where('id',$product_id)->select('id','slug','slugLang','thumbnail','gallery','options','modelNumber','sku','barcode','currency','price','regularPrice','buyingPrice','quantity','brand','model','status')->first();
            $product['gallery'] = json_decode($product['gallery']);
            $product['options'] = json_decode($product['options']);

            if($product){
                $products_metas= DB::table("products_meta")->where('product_id',$product_id)->select('lang','title','shortDescription','description')->get();
                $metas = [];
                if($products_metas){
                    foreach($products_metas as $meta){
                        $metas[$meta->lang.'_title'] = $meta->title;
                        $metas[$meta->lang.'_shortDescription'] = $meta->shortDescription;
                        $metas[$meta->lang.'_description'] = $meta->description;
                    }
                }

                $relations = ProductRelationController::getRelations($product_id);

                $product = array_merge($product, $relations, $metas);
            }

            return response()->json($product);
        }
    }


    /**
     * securities
     */
    public function securities(Request $request){
        
        if($request->isMethod('post')){
            $attr = $request->validate([
                'image'      => 'nullable|mimes:png,jpg,jpeg|max:2048', //,csv,txt,xlx,xls,pdf
                'status'    => 'required'
            ]);
            $id = $request->input('id');

            // Image process
            $image = UploadController::upload('image', $request);
            if($request->input('old_image') && $request->hasFile('image')){
                $file_path = public_path() . '/uploads/' . $request->input('old_image');
                if(is_file($file_path)){
                    unlink($file_path);
                }
            }
            $data['image'] = $image?:$request->input('old_image');

            ProductSecurities::updateOrCreate(['id'   => $id],$data);

            // set flush message
            Session::flash('message', 'Security saved successfully');
            return redirect('/manager/products/securities');
        }
        
        $securities = ProductSecurities::paginate(10);
        
        $security = isset($request->id)?ProductSecurities::where('id',$request->id)->first():NULL;

        return view('manager.products.securities.index')
                ->with('securities',$securities)
                ->with('security',$security);
    }
    public function deleteSecurity(Request $request){
        $id = $request->id;
        ProductSecurities::find($id)->delete();
        Session::flash('message', 'Security saved successfully');
        
        return redirect('/manager/products/securities');
    }
    public function getSecurity( Request $request ){
        $id         = $request->id;
        $option     = (array)DB::table('securities')->where('id',$id)->first();

        return response()->json($option);
    }


    /**
     * delivery-options
     */
    public function deliveryOptions(Request $request){
        $method_id = isset($request->id)?$request->id:0;

        if($request->isMethod('post')){
            $attr = $request->validate([
                'title.*'   => 'required|string|max:255',
                'amount'    => 'required',
                'status'    => 'required'
            ]);

            $method = DeliveryOptions::updateOrCreate(
                ['id'=>$method_id],
                [
                    'amount'      => $request->input('amount'),
                    'status'    => (int)$request->input('status')
                ]
            );
            $method_id = $method->id;
            insert_update_meta( $request, 'delivery_options_id', $method_id, array('title'), DeliveryOptionsMeta::class );

            // set flush message
            Session::flash('message', 'Method saved successfully');
            return redirect('/manager/products/delivery-options');
        }

        $method = DeliveryOptions::with('meta')->where('id',$method_id)->first();
        $methods = DeliveryOptions::with('meta')->orderBy('id','DESC')->paginate(18);

        return view('manager.products.delivery_options.index')->with('method',$method)->with('methods',$methods);
    }
    public function delete(Request $request){
        $id = $request->id;
        if($id){
            DeliveryOptions::where('id',$id)->delete();
            DB::table('delivery_options_meta')->where('delivery_options_id',$id)->delete();
        }
        // set flush message
        Session::flash('message', 'Method deleted successfully');
        return redirect('/manager/products/delivery-options');
    }
    public function getDeliveryOption( Request $request ){
        $id = $request->id;

        $option         = (array)DB::table('delivery_options')->where('id',$id)->first();
        $option['meta']    = DB::table('delivery_options_meta')
                                    ->where('delivery_options_id',$id)
                                    ->select('lang','title')
                                    ->get();

        if($option['meta']){
            $metas = [];
            foreach($option['meta'] as $meta){
                $metas[$meta->lang.'_title'] = $meta->title;
            }
            $option['meta'] = $metas;
        }


        return response()->json($option);
    }




    /**
     * Settings
     * 
     * @param request
     * 
     * @return json object
     */
    public function settings(Request $request){
        $op_type    = "products_settings";

        if($request->isMethod('post')){
            $pelecard = $request->pelecard;
            $creditInfo = $request->creditInfo;
            OptionsController::saveOptions([
                'pelecard'      => json_encode($pelecard),
                'creditInfo'    => json_encode($creditInfo)
            ],$op_type);

            // set flush message
            Session::flash('message', 'Settings updated successfully');

            return redirect('/manager/products/settings');
        }
        
        $options    = OptionsController::getOptions($op_type);

        return view('manager.products.settings')
                    ->with('options',$options);
    }
    
}
