<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// // Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// Manager login
Route::any('/manager/login',[App\Http\Controllers\Admin\AdminController::class, 'login'])->name('admin_login');
Route::any('/manager/logout',[App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('admin_logout');
Route::group(['middleware' => 'adminAuth'], function () {
    Route::any('/manager',[App\Http\Controllers\Admin\AdminController::class, 'dashboard']);

    // Admins
    Route::get('/manager/admins',[App\Http\Controllers\Admin\AdminController::class, 'admins']);
    Route::get('/manager/admins/create',[App\Http\Controllers\Admin\AdminController::class, 'create']);
    Route::get('/manager/admins/edit/{id}',[App\Http\Controllers\Admin\AdminController::class, 'edit']);
    Route::get('/manager/admins/profile',[App\Http\Controllers\Admin\AdminController::class, 'profile']);
    Route::post('/manager/admins/save',[App\Http\Controllers\Admin\AdminController::class, 'save']);
    Route::post('/manager/admins/delete',[App\Http\Controllers\Admin\AdminController::class, 'deleteAdmin']);

    // Product route
    Route::get('/manager/products',[App\Http\Controllers\Admin\ProductController::class, 'products']);
    Route::get('/manager/products/{id}',[App\Http\Controllers\Admin\ProductController::class, 'edit'])->where('id', '[0-9]+');
    Route::get('/manager/products/add',[App\Http\Controllers\Admin\ProductController::class, 'add']);
    Route::post('/manager/products/save',[App\Http\Controllers\Admin\ProductController::class, 'save']);
    Route::get('/manager/products/delete/{id}',[App\Http\Controllers\Admin\ProductController::class, 'deleteProduct']);
    Route::get('/manager/products/get',[App\Http\Controllers\Admin\ProductController::class, 'getProduct']);
    Route::any('/manager/products/settings',[App\Http\Controllers\Admin\ProductController::class, 'settings']);
    // Securities
    Route::any('/manager/products/securities',[App\Http\Controllers\Admin\ProductController::class, 'securities']);
    Route::get('/manager/products/securities/{id}',[App\Http\Controllers\Admin\ProductController::class, 'securities']);
    Route::get('/manager/products/securities/delete/{id}',[App\Http\Controllers\Admin\ProductController::class, 'deleteSecurity']);
    Route::get('/manager/products/getSecurity',[App\Http\Controllers\Admin\ProductController::class, 'getSecurity']);
    // Delivery options
    Route::any('/manager/products/delivery-options',[App\Http\Controllers\Admin\ProductController::class, 'deliveryOptions']);
    Route::any('/manager/products/delivery-options/{id}',[App\Http\Controllers\Admin\ProductController::class, 'deliveryOptions']);
    Route::any('/manager/products/delivery-options/delete/{id}',[App\Http\Controllers\Admin\ProductController::class, 'delete']);
    Route::post('/manager/products/deleteDeliveryOption',[App\Http\Controllers\Admin\ProductController::class, 'deleteDeliveryOption']);
    Route::get('/manager/products/getDeliveryOption',[App\Http\Controllers\Admin\ProductController::class, 'getDeliveryOption']);
    Route::post('/manager/products/removeOptionFile',[App\Http\Controllers\Admin\ProductController::class, 'removeOptionFile']);
    Route::get('/manager/export-excel',[App\Http\Controllers\Admin\ExcelImportExportController::class, 'exportExcel']);
    // // Coupons
    Route::any('/manager/products/coupons',[App\Http\Controllers\Admin\CouponController::class, 'coupons']);
    Route::get('/manager/products/coupons/{id}',[App\Http\Controllers\Admin\CouponController::class, 'coupons']);
    Route::get('/manager/products/coupons/delete/{id}',[App\Http\Controllers\Admin\CouponController::class, 'delete']);
    Route::get('/manager/products/coupons/get',[App\Http\Controllers\Admin\CouponController::class, 'getCoupon']);
    // // Orders
    Route::get('/manager/products/orders',[App\Http\Controllers\Admin\OrdersController::class, 'orders']);
    Route::get('/manager/products/orders/{id}',[App\Http\Controllers\Admin\OrdersController::class, 'view']);
    Route::post('/manager/products/orders/changeStatus',[App\Http\Controllers\Admin\OrdersController::class, 'changeStatus']);
    Route::get('/manager/products/orders/delete/{id}',[App\Http\Controllers\Admin\OrdersController::class, 'delete']);
    Route::post('/manager/products/orders/change-status',[App\Http\Controllers\Admin\OrdersController::class, 'change_status']);
    // // Customers
    Route::get('/manager/products/customers',[App\Http\Controllers\Admin\CustomersController::class, 'customers']);
    Route::get('/manager/products/customers/{id}',[App\Http\Controllers\Admin\CustomersController::class, 'view'])->where('id', '[0-9]+');
    Route::post('/manager/products/customers/save',[App\Http\Controllers\Admin\CustomersController::class, 'save']);
    Route::post('/manager/products/customers/changeStatus',[App\Http\Controllers\Admin\CustomersController::class, 'changeStatus']);

    // // Page route
    Route::get('/manager/pages',[App\Http\Controllers\Admin\PageController::class, 'pages']);
    Route::get('/manager/pages/create',[App\Http\Controllers\Admin\PageController::class, 'create']);
    Route::get('/manager/pages/delete/{id}',[App\Http\Controllers\Admin\PageController::class, 'delete']);
    Route::get('/manager/pages/{id}',[App\Http\Controllers\Admin\PageController::class, 'edit']);
    Route::get('/manager/pages/get',[App\Http\Controllers\Admin\PageController::class, 'getPage']);
    Route::post('/manager/pages/save',[App\Http\Controllers\Admin\PageController::class, 'save']);
    Route::get('/manager/pages/fetchData',[App\Http\Controllers\Admin\PageController::class, 'fetchData']);
    Route::get('/manager/pages/fetchPageUrl',[App\Http\Controllers\Admin\PageController::class, 'fetchPageUrl']);


    // // Static page
    Route::any('/manager/static-pages/home',[App\Http\Controllers\Admin\StaticPageController::class, 'home']);
    Route::any('/manager/static-pages/store',[App\Http\Controllers\Admin\StaticPageController::class, 'store']);
    // Route::any('/manager/static-pages/category',[App\Http\Controllers\Admin\StaticPageController::class, 'category']);
    // Route::any('/manager/static-pages/brand',[App\Http\Controllers\Admin\StaticPageController::class, 'brand']);
    // Route::any('/manager/static-pages/search',[App\Http\Controllers\Admin\StaticPageController::class, 'search']);
    Route::any('/manager/static-pages/contact',[App\Http\Controllers\Admin\StaticPageController::class, 'contact']);
    Route::any('/manager/static-pages/about',[App\Http\Controllers\Admin\StaticPageController::class, 'about']);
    Route::any('/manager/static-pages/terms-conditions',[App\Http\Controllers\Admin\StaticPageController::class, 'terms_conditions']);
    // Contacts
    Route::get('/manager/contacts',[App\Http\Controllers\Admin\ContactsController::class, 'contacts']);
    Route::get('/manager/contacts/{id}',[App\Http\Controllers\Admin\ContactsController::class, 'view']);
    Route::post('/manager/contacts/delete/{id}',[App\Http\Controllers\Admin\ContactsController::class, 'delete']);
    // subscribers
    Route::get('/manager/subscribers',[App\Http\Controllers\Admin\SubscribersController::class, 'subscribers']);
    Route::get('/manager/subscribers/delete/{id}',[App\Http\Controllers\Admin\SubscribersController::class, 'delete']);
    Route::get('/manager/subscribers/change/{id}',[App\Http\Controllers\Admin\SubscribersController::class, 'change']);
    // // Brand route
    Route::get('/manager/brands',[App\Http\Controllers\Admin\BrandController::class, 'brands']);
    Route::get('/manager/brands/add',[App\Http\Controllers\Admin\BrandController::class, 'add']);
    Route::get('/manager/brands/{id}',[App\Http\Controllers\Admin\BrandController::class, 'edit']);
    Route::post('/manager/brands/save',[App\Http\Controllers\Admin\BrandController::class, 'save']);
    Route::get('/manager/brands/delete/{id}',[App\Http\Controllers\Admin\BrandController::class, 'delete']);
    Route::get('/manager/brands/fetchData',[App\Http\Controllers\Admin\BrandController::class, 'fetchData']);
    // // Category route
    Route::get('/manager/category',[App\Http\Controllers\Admin\CategoryController::class, 'categories']);
    Route::get('/manager/category/add',[App\Http\Controllers\Admin\CategoryController::class, 'add']);
    Route::get('/manager/category/{id}',[App\Http\Controllers\Admin\CategoryController::class, 'edit']);
    Route::post('/manager/category/save',[App\Http\Controllers\Admin\CategoryController::class, 'save']);
    Route::get('/manager/category/delete/{id}',[App\Http\Controllers\Admin\CategoryController::class, 'delete']);
    Route::get('/manager/category/get',[App\Http\Controllers\Admin\CategoryController::class, 'getCategory']);
    Route::get('/manager/category/fetchData',[App\Http\Controllers\Admin\CategoryController::class, 'fetchData']);
    Route::get('/manager/category/fetchCategoryUrl',[App\Http\Controllers\Admin\CategoryController::class, 'fetchCategoryUrl']);
    // // Models
    Route::get('/manager/models',[App\Http\Controllers\Admin\ModelController::class, 'models']);
    Route::get('/manager/models/add',[App\Http\Controllers\Admin\ModelController::class, 'add']);
    Route::get('/manager/models/{id}',[App\Http\Controllers\Admin\ModelController::class, 'edit'])->where('id', '[0-9]+');
    Route::post('/manager/models/save',[App\Http\Controllers\Admin\ModelController::class, 'save']);
    Route::get('/manager/models/delete/{id}',[App\Http\Controllers\Admin\ModelController::class, 'delete'])->where('id', '[0-9]+');
    Route::post('/manager/models/exists',[App\Http\Controllers\Admin\ModelController::class, 'isModelExists']);
    // Route::get('/manager/models/get',[App\Http\Controllers\Admin\ModelController::class, 'getModel']);
    Route::get('/manager/models/get-models',[App\Http\Controllers\Admin\ModelController::class, 'fetchData']);
    // // Color
    Route::get('/manager/colors',[App\Http\Controllers\Admin\ColorController::class, 'colors']);
    Route::get('/manager/colors/{id}',[App\Http\Controllers\Admin\ColorController::class, 'colors']);
    Route::post('/manager/colors/save',[App\Http\Controllers\Admin\ColorController::class, 'save']);
    Route::get('/manager/colors/delete/{id}',[App\Http\Controllers\Admin\ColorController::class, 'delete']);
    Route::get('/manager/colors/get',[App\Http\Controllers\Admin\ColorController::class, 'getColor']);
    Route::get('/manager/colors/fetchData',[App\Http\Controllers\Admin\ColorController::class, 'fetchData']);
    // // Size
    Route::get('/manager/sizes',[App\Http\Controllers\Admin\SizeController::class, 'sizes']);
    Route::get('/manager/sizes/{id}',[App\Http\Controllers\Admin\SizeController::class, 'sizes']);
    Route::post('/manager/sizes/save',[App\Http\Controllers\Admin\SizeController::class, 'save']);
    Route::get('/manager/sizes/delete/{id}',[App\Http\Controllers\Admin\SizeController::class, 'delete']);
    Route::get('/manager/sizes/get',[App\Http\Controllers\Admin\SizeController::class, 'getSize']);
    Route::get('/manager/sizes/fetchData',[App\Http\Controllers\Admin\SizeController::class, 'fetchData']);


    Route::any('/manager/settings',[App\Http\Controllers\Admin\SettingsController::class, 'settings']);
    Route::any('/manager/settings/header',[App\Http\Controllers\Admin\SettingsController::class, 'header']);
    Route::any('/manager/settings/footer',[App\Http\Controllers\Admin\SettingsController::class, 'footer']);


    Route::get('/manager/import-export',[App\Http\Controllers\Admin\ExcelImportExportController::class, 'importExportExcel']);
    Route::post('/manager/import',[App\Http\Controllers\Admin\ExcelImportExportController::class, 'importExcel']);
    Route::post('/manager/export',[App\Http\Controllers\Admin\ExcelImportExportController::class, 'exportExcel']);

    // Route::post('/manager/save-country',[App\Http\Controllers\Admin\CountryController::class, 'updateCreateCountry']);
    // Route::get('/manager/get-countries',[App\Http\Controllers\Admin\CountryController::class, 'getCountries']);
    // Route::get('/manager/get-country-meta',[App\Http\Controllers\Admin\CountryController::class, 'getCountryMeta']);
    // Route::post('/manager/change-country-default',[App\Http\Controllers\Admin\CountryController::class, 'changeCountryDefault']);


    Route::post('/manager/ajaxFileUpload',[App\Http\Controllers\Admin\UploadController::class, 'ajaxUpload']);

});






// // Fron area routes
// Route::get('/get-header-footer',[App\Http\Controllers\PageController::class, 'getHeaderFooter'])->middleware('throttle:100,60');
Route::get('/',[App\Http\Controllers\PageController::class, 'home'])->name('home');
Route::get('/store',[App\Http\Controllers\ProductsController::class, 'store'])->name('store');
Route::get('/get-ajax-products',[App\Http\Controllers\ProductsController::class, 'get_ajax_products']);
Route::get('/store/{slug}',[App\Http\Controllers\ProductsController::class, 'product']);
Route::get('/contact-us',[App\Http\Controllers\PageController::class, 'contact'])->name('contact_us');

Route::any('/login',[App\Http\Controllers\CustomersController::class, 'login'])->name('login');
Route::any('/forgot-password',[App\Http\Controllers\CustomersController::class, 'forgot_password'])->name('forgot_password');
Route::any('/register',[App\Http\Controllers\CustomersController::class, 'register'])->name('register');
Route::group(['middleware' => 'auth'], function () {
    Route::any('/my-account',[App\Http\Controllers\CustomersController::class, 'dashboard']);
    Route::any('/my-account/orders',[App\Http\Controllers\CustomersController::class, 'orders']);
    Route::get('/my-account/orders/delete/{order_number}',[App\Http\Controllers\CustomersController::class, 'delete_order']);
    Route::any('/my-account/shipping-address',[App\Http\Controllers\CustomersController::class, 'shipping_address']);
    Route::any('/my-account/billing-address',[App\Http\Controllers\CustomersController::class, 'billing_address']);
    Route::any('/my-account/settings',[App\Http\Controllers\CustomersController::class, 'settings']);
    Route::any('/my-account/password-update',[App\Http\Controllers\CustomersController::class, 'password_update']);
    Route::any('/logout',[App\Http\Controllers\CustomersController::class, 'logout']);
});

Route::post('/add-to-cart',[App\Http\Controllers\CartController::class, 'add_to_cart']);
Route::post('/remove-cart-item',[App\Http\Controllers\CartController::class, 'remove_cart_item']);
Route::post('/update-cart-item',[App\Http\Controllers\CartController::class, 'update_cart_item']);
Route::get('/cart',[App\Http\Controllers\CartController::class, 'cart']);
Route::any('/apply-coupon',[App\Http\Controllers\OrderController::class, 'apply_coupon']);
Route::any('/checkout',[App\Http\Controllers\OrderController::class, 'checkout']);
Route::post('/process-order',[App\Http\Controllers\OrderController::class, 'process_order']);
Route::get('/checkout/success',[App\Http\Controllers\OrderController::class, 'success_and_confirm_payment']);
Route::get('/checkout/error',[App\Http\Controllers\OrderController::class, 'payment_error']);
Route::any('/contact',[App\Http\Controllers\PageController::class, 'contact']);
Route::any('/about',[App\Http\Controllers\PageController::class, 'about']);
Route::any('/terms-conditions',[App\Http\Controllers\PageController::class, 'terms_conditions']);


// Route::get('/homePage',[App\Http\Controllers\PageController::class, 'homePage'])->middleware('throttle:100,60');
// Route::get('/shopPage',[App\Http\Controllers\PageController::class, 'shopPage'])->middleware('throttle:100,60');
// Route::get('/categoryPage',[App\Http\Controllers\PageController::class, 'categoryPage'])->middleware('throttle:100,60');
// Route::get('/brandPage',[App\Http\Controllers\PageController::class, 'brandPage'])->middleware('throttle:100,60');
// Route::get('/searchPage',[App\Http\Controllers\PageController::class, 'searchPage'])->middleware('throttle:100,60');
// Route::get('/get-page',[App\Http\Controllers\PageController::class, 'getPage'])->middleware('throttle:100,60');
// Route::any('/contact',[App\Http\Controllers\PageController::class, 'contactUs'])->middleware('throttle:100,60');
// Route::post('/subscribers/checkSubscribedEmail',[App\Http\Controllers\SubscribersController::class, 'checkSubscribedEmail'])->middleware('throttle:100,60');
// Route::post('/subscribers/subscribe',[App\Http\Controllers\SubscribersController::class, 'subscribe'])->middleware('throttle:100,60');



// Route::post('/customer/isExists',[App\Http\Controllers\CustomersController::class, 'checkEmail'])->middleware('throttle:100,60');
// Route::get('/customer/getCountries',[App\Http\Controllers\CustomersController::class, 'getCountries'])->middleware('throttle:100,60');
// Route::post('/customer/register',[App\Http\Controllers\CustomersController::class, 'register'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/update',[App\Http\Controllers\CustomersController::class, 'update'])->middleware('throttle:100,60');
// Route::post('/customer/login',[App\Http\Controllers\CustomersController::class, 'login'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/loginout',[App\Http\Controllers\CustomersController::class, 'login'])->middleware('throttle:100,60');

// Route::get('/customer/fetchCarts',[App\Http\Controllers\CustomersController::class, 'fetchCarts'])->middleware('throttle:100,60');
// Route::post('/customer/insertUpdateCarts',[App\Http\Controllers\CustomersController::class, 'insertUpdateCarts'])->middleware('throttle:100,60');
// Route::post('/customer/isLoggedIn',[App\Http\Controllers\CustomersController::class, 'isLoggedIn'])->middleware('throttle:100,60');
// Route::post('/customer/updateCartIdLocalToReal',[App\Http\Controllers\CustomersController::class, 'updateCartIdLocalToReal'])->middleware('throttle:100,60');
// Route::post('/customer/checkout',[App\Http\Controllers\CustomersController::class, 'checkout'])->middleware('throttle:100,60');
// Route::post('/customer/checkout/paymentConfirm',[App\Http\Controllers\CustomersController::class, 'paymentConfirm'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/fetchOrders',[App\Http\Controllers\CustomersController::class, 'fetchOrders'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/orderTrash',[App\Http\Controllers\CustomersController::class, 'orderTrash'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->any('/customer/billingAddress',[App\Http\Controllers\CustomersController::class, 'billingAddress'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->any('/customer/shippingAddress',[App\Http\Controllers\CustomersController::class, 'shippingAddress'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->any('/customer/account',[App\Http\Controllers\CustomersController::class, 'account'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/sendOtpMail',[App\Http\Controllers\CustomersController::class, 'sendOtpMail'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/verifyOtp',[App\Http\Controllers\CustomersController::class, 'verifyOtp'])->middleware('throttle:100,60');
// Route::middleware('auth:sanctum')->post('/customer/updatePassword',[App\Http\Controllers\CustomersController::class, 'updatePassword'])->middleware('throttle:100,60');

// Route::get('/products/fetchRelatives',[App\Http\Controllers\ProductsController::class, 'fetchRelatives'])->middleware('throttle:100,60');
// Route::get('/products/fetch',[App\Http\Controllers\ProductsController::class, 'fetch'])->middleware('throttle:100,60');
// Route::post('/products/fetchProduct',[App\Http\Controllers\ProductsController::class, 'fetchProduct'])->middleware('throttle:100,60');
// Route::get('/products/getMiniSearchResult',[App\Http\Controllers\ProductsController::class, 'getMiniSearchResult'])->middleware('throttle:100,60');
// Route::get('/products/fetchDeliveryOptions',[App\Http\Controllers\ProductsController::class, 'fetchDeliveryOptions'])->middleware('throttle:100,60');
// Route::get('/products/fetchDeliveryOption',[App\Http\Controllers\ProductsController::class, 'fetchDeliveryOption'])->middleware('throttle:100,60');
// Route::get('/products/fetchCoupon',[App\Http\Controllers\ProductsController::class, 'fetchCoupon'])->middleware('throttle:100,60');
// Route::get('/products/fetchCustomerDashboardProducts',[App\Http\Controllers\ProductsController::class, 'fetchCustomerDashboardProducts'])->middleware('throttle:100,60');

// Route::post('/category/fetchProductCategoryBySlug',[App\Http\Controllers\CategoryController::class, 'getProductCategoryBySlug'])->middleware('throttle:100,60');
// Route::post('/brand/fetchProductBrandBySlug',[App\Http\Controllers\BrandController::class, 'getProductBrandBySlug'])->middleware('throttle:100,60');
