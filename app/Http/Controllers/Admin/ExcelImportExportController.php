<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Http\Controllers\Admin\ProductRelationController;

use DB;
use App\Models\User;
use App\Models\UserBillingAddress;
use App\Models\UserShippingAddress;
use App\Models\Product;
use App\Models\ProductMeta;
use App\Models\Coupon;
use App\Models\CouponMeta;
use App\Models\Brand;
use App\Models\BrandMeta;
use App\Models\Models;
use App\Models\ModelsMeta;
use App\Models\Category;
use App\Models\CategoryMeta;
use App\Models\Color;
use App\Models\ColorMeta;
use App\Models\Size;
use App\Models\ProductDeliveryOptions;
use App\Models\ProductCategory;
use App\Models\Country;
use App\Models\CountryMeta;
use App\Models\Subscribers;
use Session;
use Str;
class ExcelImportExportController extends Controller
{
    public function importExportExcel(){
        return view('manager.import_export.importExport');
    }
    public function exportExcel(Request $request){

        $table = $request->table;

        if( $table ){

            $spreadsheet    = new Spreadsheet();
            $sheet          = $spreadsheet->getActiveSheet();
            $sheet->setTitle($table);

            if( $table == 'users' ){
                $exporting_data = $this->usersExport();
            }
            if( $table == 'products' ){
                $exporting_data = $this->productExport();
            }
            if( $table == 'brand' ){
                $exporting_data = $this->brandExport();
            }
            if( $table == 'model' ){
                $exporting_data = $this->modelExport();
            }
            if( $table == 'category' ){
                $exporting_data = $this->categoryExport();
            }
            if( $table == 'color' ){
                $exporting_data = $this->colorExport();
            }
            if( $table == 'size' ){
                $exporting_data = $this->sizeExport();
            }
            if( $table == 'delivery_options' ){
                $exporting_data = $this->delivery_optionsExport();
            }
            if( $table == 'coupons' ){
                $exporting_data = $this->couponsExport();
            }
            if( $table == 'subscribers' ){
                $exporting_data = $this->subscribersExport();
            }
            

            if( $exporting_data ){
                $i = 1;
                $colHead = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N',
                'O','P','Q','R','S','T','U','V','W','X','Y','Z'];
                foreach( $exporting_data as $data ){
                    if($data){
                        $j = 0;
                        foreach($data as $key => $value){
                            if($i==1){
                                $sheet->setCellValue($colHead[$j].$i, $key); // key , value(as heading key)
                            }
                            $sheet->setCellValue($colHead[$j].($i+1), $value);
                            $j++;
                        }
                    }
                    $i++;
                };
            }

            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=".$table.".xlsx");  //File name extension was wrong
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private",false);

            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");

            exit;
        }
    }
    
    protected function usersExport(){
        $users = DB::table('users')->get();
        $filterd_users = [];
        if(count($users)){
            foreach($users as $user){
                $user_balance = DB::table('user_balance')->where('user_id',$user->id)->first();
                $user_points = DB::table('user_points')->where('user_id',$user->id)->first();
                
                $filterd_users[] = [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'mobile'    => $user->mobile,
                    'city'      => $user->city,
                    'zip_code'  => $user->zip_code,
                    'company'   => $user->company,
                    'address'   => $user->address,
                    'password'  => $user->password,
                    'balance'   => isset($user_balance->balance)?$user_balance->balance:0,
                    'points'    => isset($user_points->points)?$user_points->points:0,
                    'status'    => $user->status,
                ];
            }
        }

        return $filterd_users;
    }
    protected function productExport(){
        $products = Product::join('products_meta','products_meta.product_id','=','products.id')
                    ->select(
                        'products_meta.title',
                        'products_meta.short_description',
                        'products_meta.description',
                        'products.*'
                    )
                    ->get();
        $filterd_products   = [];
        if(count($products)){
            foreach($products as $product){
                $model = DB::table('model')->where('id',$product->model)->first();
                $brand = DB::table('brand_meta')->where('brand_id',$product->brand)->first();

                $product_categories = ProductCategory::with(['category'=>function($join){
                                        $join->with(['meta']);
                                    }])->where('product_id',$product->id)->get();
                $filter_category  = '';
                if(count($product_categories)){
                    foreach($product_categories as $pdc){
                        $filter_category .= $pdc->category->meta->title . ', ';
                    }
                    $filter_category = rtrim($filter_category, ', ');
                }

                $filterd_gallery = '';
                if( $product->gallery ){
                    $gl = json_decode($product->gallery);
                    if(count($gl)){
                        foreach($gl as $item){
                            if(isset($item->image)){
                                $filterd_gallery .= $item->image . ', ';
                            }
                        }
                        $filterd_gallery = rtrim($filterd_gallery, ', ');
                    }
                }

                $filterd_products[] = [
                    'id'                => $product->id,
                    'slug'              => $product->slug,
                    'model_number'      => $product->model_number,
                    'title'             => $product->title,
                    'short_description' => $product->short_description,
                    'description'       => $product->description,
                    'barcode'           => $product->barcode,
                    'price'             => $product->price,
                    'regular_price'     => $product->regular_price,
                    'quantity'          => $product->quantity,
                    'category'          => $filter_category,
                    'brand'             => ($brand?$brand->title:''),
                    'model'             => ($model?$model->model:''),
                    'thumbnail'         => $product->thumbnail,
                    'gallery'           => $filterd_gallery,
                    'status'            => $product->status,
                ];
            }
        }

        return $filterd_products;
    }
    protected function brandExport(){
        $brands         = Brand::join('brand_meta','brand_meta.brand_id','=','brand.id')->get();
        $filterd_brands = [];
        if(count($brands)){
            foreach($brands as $brand){
                $filterd_brands[] = [
                    'id'            => $brand->id,
                    'slug'          => $brand->slug,
                    'title'         => $brand->title,
                    'image'         => $brand->image,
                    'status'        => $brand->status,
                ];
            }
        }

        return $filterd_brands;
    }
    protected function modelExport(){
        $models         = Models::with(['brand'])->get();
        $filterd_models = [];
        if(count($models)){
            foreach($models as $model){
                $filterd_models[] = [
                    'id'            => $model->id,
                    'model'         => $model->model,
                    'brand'         => $model->brand->title,
                    'status'        => $model->status,
                ];
            }
        }

        return $filterd_models;
    }
    protected function categoryExport(){
        $categorys          = Category::with(['meta'])->get();
        $filterd_categorys  = [];
        if(count($categorys)){
            foreach($categorys as $category){
                $filterd_categorys[] = [
                    'id'            => $category->id,
                    'slug'          => $category->slug,
                    'title'         => $category->meta->title,
                    'image'         => $category->image,
                    'status'        => $category->status,
                ];
            }
        }

        return $filterd_categorys;
    }
    protected function colorExport(){
        $colors         = Color::with(['meta'])->get();
        $filterd_colors = [];
        if(count($colors)){
            foreach($colors as $color){
                $filterd_colors[] = [
                    'id'        => $color->id,
                    'code'      => $color->code,
                    'title'     => $color->meta->title,
                    'status'    => $color->status,
                ];
            }
        }

        return $filterd_colors;
    }
    protected function sizeExport(){
        $sizes          = Size::get();
        $filterd_sizes  = [];
        if(count($sizes)){
            foreach($sizes as $size){
                $filterd_sizes[] = [
                    'id'        => $size->id,
                    'unite'     => $size->unite,
                    'value'     => $size->value,
                    'status'    => $size->status,
                ];
            }
        }
        return $filterd_sizes;
    }
    protected function subscribersExport(){
        $subscribers = Subscribers::get();
        $filterd_subscribers = [];
        if(count($subscribers)){
            foreach($subscribers as $subscriber){
                $filterd_subscribers[] = [
                    'id'            => $subscriber->id,
                    'user_id'       => $subscriber->user_id,
                    'email'         => $subscriber->email,
                    'status'        => $subscriber->status,
                ];
            }
        }
        return $filterd_subscribers;
    }
    protected function delivery_optionsExport(){
        $delivery_options           = ProductDeliveryOptions::with(['meta'])->get();
        $filterd_delivery_options   = [];
        if(count($delivery_options)){
            foreach($delivery_options as $delivery_option){
                $filterd_delivery_options[] = [
                    'id'        => $delivery_option->id,
                    'amount'    => $delivery_option->amount,
                    'title'     => $delivery_option->meta->title,
                    'status'    => $delivery_option->status,
                ];
            }
        }

        return $filterd_delivery_options;
    }
    protected function couponsExport(){
        $coupons            = Coupon::with(['meta'])->get();
        $filterd_coupons    = [];
        if(count($coupons)){
            foreach($coupons as $coupon){
                $filterd_coupons[] = [
                    'id'            => $coupon->id,
                    'title'         => $coupon->meta->title,
                    'description'   => $coupon->meta->description,
                    'coupon_code'   => $coupon->coupon_code,
                    'discount_type' => $coupon->discount_type,
                    'discount'      => $coupon->discount,
                    'start_date'    => $coupon->start_date,
                    'expire_date'   => $coupon->expire_date,
                    'status'        => $coupon->status,
                ];
            }
        }

        return $filterd_coupons;
    }

    public function importExcel(Request $request){


        $table              = $request->input('table');
        $file               = $request->file('excelFile');
        $fileName           = $file->getClientOriginalName() ;
        $fileExtention      = $file->getClientOriginalExtension();
        $newName            = md5(time().md5($fileName).uniqid(rand(), true)).'.'.$fileExtention;
        $destinationPath    = public_path().'/uploads/temp';
        $newFileUrl         = url('/uploads/temp/'.$newName);
        $url                = $file->move($destinationPath,$newName);

        /** Create a new Xls Reader  **/
        $reader         = IOFactory::createReader('Xlsx');
        $spreadsheet    = $reader->load( $destinationPath . '/' . $newName );
        $getRow         = $spreadsheet->getActiveSheet()->toArray();

        return $this->filter_and_push( $getRow, $table );
    }

    private function filter_and_push( $getRow, $table ){
        $data           = array();
        $i              = 0;
        $keyNames        = array();
        foreach ($getRow as $row) {
            if ( $row ) {
                if( $i == 0 ){
                    foreach($row as $keyName){
                        $keyNames[] = $keyName;
                    }
                }else{
                    $rowData    = array();
                    $j          = 0;
                    foreach($row as $value){

                        if(!isset($keyNames[$j]) && !$keyNames[$j]){
                            return response()->json(['result'=>500]);
                            exit;
                        }
                        $heading = $keyNames[$j];

                        $this->checkSupportedColumnHeading( $heading, $table );

                        $rowData[$heading] = $value?$value:'';
                        
                        $j++;
                    }
                    $data[] = $rowData;
                }

                $i++;
            }
        }
        
        if(count($data)){
            $importTable = 'import'.ucfirst($table);
            return $this->$importTable( $data );
        }else{
            Session::flash('message', 'Data filter error');
            return redirect()->back();
        }
    }

    private function checkSupportedColumnHeading( $heading, $table = 'products' ){
        $isValide = true;
        if($table && $heading){
            if( $table == 'users' && array_search($heading, ['id','name','email','mobile','city','state','country','zip_code','company','address','password','balance','points','status']) === false ){
                $isValide = false;
            }
            if( $table == 'products' && array_search($heading, ['id','slug','model_number','title','short_description','description','barcode','price','regular_price','quantity','brand','model','thumbnail','gallery','status']) === false ){
                $isValide = false;
            }
            if( $table == 'brand' && array_search($heading, ['id','slug','title','image','status']) === false ){
                $isValide = false;
            }
            if( $table == 'model' && array_search($heading, ['id','model','brand','status']) === false ){
                $isValide = false;
            }
            if( $table == 'category' && array_search($heading, ['id','slug','title','image','status']) === false ){
                $isValide = false;
            }
            if( $table == 'color' && array_search($heading, ['id','code','title','status']) === false ){
                $isValide = false;
            }
            if( $table == 'size' && array_search($heading, ['id','unite','value','status']) === false ){
                $isValide = false;
            }
            if( $table == 'subscribers' && array_search($heading, ['id','user_id','email','status']) === false ){
                $isValide = false;
            }
            if( $table == 'delivery_options' && array_search($heading, ['id','amount','title','status']) === false ){
                $isValide = false;
            }
            if( $table == 'coupons' && array_search($heading, ['id','coupon_code','discount_type','discount','start_date','expire_date','status']) === false ){
                $isValide = false;
            }
        }
        if( $isValide === false ){
            Session::flash('message', 'File & Table fields dosent match');
            return redirect()->back();
        }
    }
    // Import table data
    protected function importUsers($data){
        if($data){
            foreach($data as $userData){
                $id         = (int)$userData['id'];
                $points     = (int)$userData['points'];
                $balance    = (int)$userData['balance'];

                // Unset key data which is not need to pass on query
                $userData = $this->unsetInputedData(
                                $userData,
                                array('points','balance')
                            );

                $user = User::updateOrCreate(
                            ['email'=>$userData['email']],
                            $userData
                        );
                if( $id && $user){
                    $id = $user->id;
                }

                // balance and points
                $balance_query  = DB::table('user_balance');
                $points_query   = DB::table('user_points');
                $balance_query->where('user_id',$id)->delete();
                $points_query->where('user_id',$id)->delete();
                $balance_query->insert(['user_id'=>$id,'balance'=>$balance]);
                $points_query->insert(['user_id'=>$id,'points'=>$balance]);
                // end balance and points

                // User Billing address and shipping
                UserBillingAddress::create(['user_id'=>$id]);
                UserShippingAddress::create(['user_id'=>$id]);
            }
        }
        Session::flash('message', 'Success! Users uploaded successfully');
        return redirect('/manager/import-export');
    }
    protected function importProducts($data){

        if($data){
            foreach($data as $product){
                $id                     = (int)$product['id'];
                $title                  = $product['title'];
                $short_description      = isset($product['short_description'])?$product['short_description']:"";
                $description            = isset($product['description'])?$product['description']:"";
                $category               = isset($product['category'])?$product['category']:"";

                // Unset key data which is not need to pass on query
                $product = $this->unsetInputedData($product,array('title','short_description','description','category','brand','model','gallery'));
                $product['regular_price'] = (double)$product['regular_price'];
                $updatedProduct = Product::updateOrCreate(
                    ['id'=>$id],
                    $product
                );


                if( $updatedProduct){
                    $id = $updatedProduct->id;
                }

                // category
                $categories = explode(',',$category);
                if(count($categories)){
                    $filterd_category_ids = [];
                    foreach($categories as $item){
                        $ct = trim($item);
                        if($ct){
                            $catMeta = CategoryMeta::where('title',$ct)->first();
                            if($catMeta){
                                $filterd_category_ids[] = $catMeta->category_id;
                            }else{
                                $category = Category::create(
                                    [
                                        'slug'      => Str::slug($ct),
                                        'image'     => NULL,
                                        'status'    => 1
                                    ]
                                );
                                if($category->id){
                                    CategoryMeta::create([
                                        'category_id'   => $category->id,
                                        'title'         => $ct,
                                        'lang'          => app()->getLocale()
                                    ]);
                                    $filterd_category_ids[] = $category->id;
                                }
                            }
                        }
                    }
                }

                // Brand
                $brand_id = NULL;
                if(isset($product['brand'])&&$product['brand']){
                    $br_title   = trim($product['brand']);
                    $slug       = Str::slug( $br_title );
                    $brm        = BrandMeta::where('title',$br_title)->first();
                    if($brm){
                        $brand_id = $brm->id;
                    }else{
                        $brand = Brand::updateOrCreate(
                            [
                                'slug'=>$slug
                            ],
                            [
                                'slug'      => $slug,
                                'image'     => NULL,
                                'status'    => 1
                            ]
                        );
                        if($brand->id){
                            BrandMeta::create([
                                'brand_id'  => $brand->id,
                                'title'     => $br_title,
                                'lang'     => app()->getLocale(),
                            ]);
                        }
                    }
                }
                // model
                $model_id = NULL;
                if(isset($product['model'])&&$product['model']){
                    $model  = trim($product['model']);
                    $m      = Models::where('model',$model)->first();
                    if($m){
                        $model_id = $m->id;
                    }else{
                        $model = Models::create([
                            'model'     => $model,
                            'brand_id'  => (int)$brand_id,
                            'status'    => 1
                        ]);
                        $model_id = $model->id;
                    }
                }

                // Gallery
                $gallery = [];
                if(isset($product['gallery']) && $product['gallery']){
                    $gls = explode(',', $product['gallery']);
                    if(count($gls)){
                        foreach($gls as $gl){
                            if($gl){
                                $gallery[] = array(
                                    'image' => $gl,
                                    'old_image' => $gl,
                                );
                            }
                        }
                    }
                    $gallery = json_encode($gallery);
                }
                Product::where('id',$id)->update([
                    'brand' => $brand_id,
                    'model' => $model_id,
                    'gallery' => $gallery
                ]);
                

                // Product meta
                if($id){
                    $meta = ProductMeta::updateOrCreate([
                        'product_id' => $id,
                        'lang' => app()->getLocale()
                    ],[
                        'product_id'        => $id,
                        'title'             => $title,
                        'short_description'  => $short_description,
                        'description'       => $description,
                        'lang'              => app()->getLocale(),
                    ]);
                    
                }

                // Category
                if(count($filterd_category_ids)){ 
                    foreach($filterd_category_ids as $cat_id){
                        ProductCategory::create([
                            'product_id' => $id,
                            'category_id' => $cat_id
                        ]);
                    }
                }
            }
        }
        Session::flash('message','Products uploaded successfully');
        return redirect('manager/import-export');
    }
    
    protected function importCoupons($data){

        if($data){
            foreach($data as $coupon){
                $id                         = $coupon['id'];
                $title                      = $coupon['title'];
                $description                = isset($coupon['description'])?$coupon['description']:"[]";
                $coupon['startDate']       = date("Y-m-d H:i:s", strtotime($coupon['startDate']));
                $coupon['expireDate']      = date("Y-m-d H:i:s", strtotime($coupon['expireDate']));

                $filterdTitle               = $this->getLangSplitedTexts($title);
                $filterdDescription         = $this->getLangSplitedTexts($description);
                
                // Unset key data which is not need to pass on query
                $coupon = $this->unsetInputedData($coupon,array('title','description'));

                $pdCoupon = Coupon::updateOrCreate(
                    ['id'=>$id],
                    $coupon
                );
                if( $id && $pdCoupon){
                    $id = $pdCoupon->id;
                }

                // User Billing address and shipping
                CouponMeta::where('coupon_id',$id)->delete();
                $lang_count = count($filterdTitle);
                if($lang_count){
                    foreach($filterdTitle as $key => $val){
                        CouponMeta::create([
                            'coupon_id'        => $id,
                            'title'             => $val,
                            'description'       => isset($filterdDescription[$key])?$filterdDescription[$key]:'',
                            'lang'              => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importBrand($data){
        if($data){
            foreach($data as $brand){
                $id                         = $brand['id'];
                $title                      = $brand['title'];

                $filterdTitle               = $this->getLangSplitedTexts($title);
                
                // Unset key data which is not need to pass on query
                $brand = $this->unsetInputedData($brand,array('title'));

                $upbrand = Brand::updateOrCreate(
                    ['id'=>$id],
                    $brand
                );
                if( $id && $upbrand){
                    $id = $upbrand->id;
                }

                // User Billing address and shipping
                BrandMeta::where('brand_id',$id)->delete();
                if($filterdTitle){
                    foreach($filterdTitle as $key => $val){
                        BrandMeta::create([
                            'brand_id'        => $id,
                            'title'             => $val,
                            'lang'              => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importModel($data){
        if($data){
            foreach($data as $model){
                $id                         = $model['id'];
                $title                      = $model['title'];

                $filterdTitle               = $this->getLangSplitedTexts($title);
                
                // Unset key data which is not need to pass on query
                $model = $this->unsetInputedData($model,array('title'));

                $upModel = Models::updateOrCreate(
                    ['id'=>$id],
                    $model
                );
                if( $id && $upModel){
                    $id = $upModel->id;
                }

                // User Billing address and shipping
                ModelsMeta::where('model_id',$id)->delete();
                if($filterdTitle){
                    foreach($filterdTitle as $key => $val){
                        ModelsMeta::create([
                            'model_id'        => $id,
                            'title'             => $val,
                            'lang'              => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importCategory($data){
        if($data){
            foreach($data as $category){
                $id                         = $category['id'];
                $title                      = $category['title'];

                $filterdTitle               = $this->getLangSplitedTexts($title);
                
                // Unset key data which is not need to pass on query
                $category = $this->unsetInputedData($category,array('title'));

                $newCategory = Category::updateOrCreate(
                    ['id'=>$id],
                    $category
                );
                if( $id && $newCategory){
                    $id = $newCategory->id;
                }

                // User Billing address and shipping
                CategoryMeta::where('category_id',$id)->delete();
                if($filterdTitle){
                    foreach($filterdTitle as $key => $val){
                        CategoryMeta::create([
                            'category_id'        => $id,
                            'title'             => $val,
                            'lang'              => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importColor($data){
        if($data){
            foreach($data as $color){
                $id                         = $color['id'];
                $title                      = $color['title'];

                $filterdTitle               = $this->getLangSplitedTexts($title);
                
                // Unset key data which is not need to pass on query
                $color = $this->unsetInputedData($color,array('title'));

                $newColor = Color::updateOrCreate(
                    ['id'=>$id],
                    $category
                );
                if( $id && $newCategory){
                    $id = $newCategory->id;
                }

                // User Billing address and shipping
                ColorMeta::where('color_id',$id)->delete();
                if($filterdTitle){
                    foreach($filterdTitle as $key => $val){
                        ColorMeta::create([
                            'color_id'        => $id,
                            'title'             => $val,
                            'lang'              => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importSize($data){
        if($data){
            foreach($data as $size){
                $id                         = $size['id'];
                
                Size::updateOrCreate(
                    ['id'=>$id],
                    $size
                );
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importDelivery_options($data){
        if($data){
            foreach($data as $delivery_option){
                $id                         = $delivery_option['id'];
                $title                      = $delivery_option['title'];

                $filterdTitle               = $this->getLangSplitedTexts($title);
                
                // Unset key data which is not need to pass on query
                $delivery_option = $this->unsetInputedData($delivery_option,array('title'));

                $newDOp = ProductDeliveryOptions::updateOrCreate(
                    ['id'=>$id],
                    $delivery_option
                );
                if( $id && $newDOp){
                    $id = $newDOp->id;
                }

                // User Billing address and shipping
                DB::table('delivery_options_meta')->where('delivery_options_id',$id)->delete();
                if($filterdTitle){
                    foreach($filterdTitle as $key => $val){
                        DB::table('delivery_options_meta')->create([
                            'delivery_options_id'   => $id,
                            'title'                 => $val,
                            'lang'                  => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importCountries($data){
        if($data){
            foreach($data as $country){
                $id                         = $country['id'];
                $name                      = $country['name'];

                $filterdName               = $this->getLangSplitedTexts($name);
                
                // Unset key data which is not need to pass on query
                $country = $this->unsetInputedData($country,array('name'));

                $newCountry = Country::updateOrCreate(
                    ['id'=>$id],
                    $country
                );
                if( $id && $newCountry){
                    $id = $newCountry->id;
                }

                // User Billing address and shipping
                CountryMeta::where('country_id',$id)->delete();
                if($filterdName){
                    foreach($filterdName as $key => $val){
                        CountryMeta::create([
                            'country_id'    => $id,
                            'name'          => $val,
                            'lang'          => $key,
                        ]);
                    }
                }
            }
            return response()->json(['result'=>200]);
        }
        return response()->json(['result'=>500]);
    }
    protected function importSubscribers($data){
        if($data){
            foreach($data as $subscriber){
                $id             = $subscriber['id'];                
                Subscribers::updateOrCreate(
                    ['id'=>$id],
                    $subscriber
                );
            }
            return response()->json(['result'=>200]);
            exit;
        }
        return response()->json(['result'=>500]);
    }
    // end



    private function unsetInputedData($data,$unsetLists){
        if($unsetLists){
            foreach($unsetLists as $key){
                unset( $data[$key] );
            }
        }
        return $data;
    }
    private function getExplodedIds($data){
        $json_array = json_decode( $data , true );
        if($json_array == NULL){
            $splited = explode(',',$data);
            $highStack = array();
            if($splited){
                foreach($splited as $data){
                    if((int)trim($data)<1){
                        continue;
                    }
                    $highStack[] = (int)trim($data);
                }
            }
            return $highStack;
        }
        $highStack = array();
        if($json_array){
            foreach( (array)$json_array as $data ){
                if((int)trim($data)<1){
                    continue;
                }
                $highStack[] = (int)trim($data);
            }
        }
        return $highStack;
    }
    private function getLangSplitedTexts($data){
        $json_array = json_decode( $data , true );

        $highStack = array();

        if($json_array){
            foreach( (array)$json_array as $key => $value ){

                if(is_array($value)){
                    $stKey  = '';
                    $stValue = '';
                    foreach($value as $key => $val){
                        $stKey = $key;
                        $stValue = $val;
                    }
                    $key = $stKey;
                    $value = $stValue;
                }
                $highStack[$key] = trim($value);
            }
        }
        return $highStack;
    }
}
