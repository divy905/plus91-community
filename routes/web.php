<?php

use App\Helpers\CustomHelper;
use App\Models\SubCategory;
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

Route::get('/', function () {
    return view('welcome');
});


// Admin
Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::match(['get', 'post'], 'admin/login', 'Admin\LoginController@index')->name('admin.login');

    Route::match(['get', 'post'], 'get_city', 'Admin\HomeController@get_city')->name('get_city');
    Route::match(['get', 'post'], 'get_locality', 'Admin\HomeController@get_locality')->name('get_locality');
    Route::match(['get', 'post'], 'get_state', 'Admin\HomeController@get_state')->name('get_state');
    Route::match(['get', 'post'], 'admin/logout', 'Admin\LoginController@logout');
    Route::match(['get', 'post'], 'generate_slug', 'Admin\HomeController@generate_slug')->name('generate_slug');
    Route::match(['get', 'post'], 'image_upload', 'Admin\HomeController@image_upload')->name('image_upload');

    $ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

    Route::group(['namespace' => 'Admin', 'prefix' => $ADMIN_ROUTE_NAME, 'as' => $ADMIN_ROUTE_NAME . '.', 'middleware' => ['authadmin']], function () {

        Route::match(['get', 'post'], '/',  'HomeController@index')->name('home');
        Route::match(['get', 'post'], '/profile',  'HomeController@profile')->name('profile');
        Route::match(['get', 'post'], '/change_password',  'HomeController@change_password')->name('change_password');
        Route::match(['get', 'post'], '/set_tab_in_session',  'HomeController@set_tab_in_session')->name('set_tab_in_session');





        Route::match(['get', 'post'], '/get_sub_category',  'HomeController@get_sub_category')->name('get_sub_category');
        Route::match(['get', 'post'], '/get_state',  'HomeController@get_state')->name('get_state');
        Route::match(['get', 'post'], '/get_city',  'HomeController@get_city')->name('get_city');



        ////admins
        Route::group(['prefix' => 'admins', 'as' => 'admins', 'middleware' => ['allowedmodule:admins,list']], function () {

            Route::get('/', 'AdminController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'AdminController@add')->name('.add');

            Route::match(['get', 'post'], 'get_admins', 'AdminController@get_admins')->name('.get_admins');
            Route::match(['get', 'post'], 'change_admins_status', 'AdminController@change_admins_status')->name('.change_admins_status');
            Route::match(['get', 'post'], 'change_admins_approve', 'AdminController@change_admins_approve')->name('.change_admins_approve');
            Route::match(['get', 'post'], 'edit', 'AdminController@add')->name('.edit');
            Route::post('ajax_delete_image', 'AdminController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get', 'post'], 'delete/{id}', 'AdminController@delete')->name('.delete');
            Route::match(['get', 'post'], 'change_admins_role', 'AdminController@change_admins_role')->name('.change_admins_role');
        });

        // roles

        Route::group(['prefix' => 'roles', 'as' => 'roles', 'middleware' => ['allowedmodule:roles,list']], function () {
            Route::get('/', 'RoleController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'RoleController@add')->name('.add');
            Route::match(['get', 'post'], 'get_roles', 'RoleController@get_roles')->name('.get_roles');
            Route::match(['get', 'post'], 'change_role_status', 'RoleController@change_role_status')->name('.change_role_status');
            Route::match(['get', 'post'], 'edit/{id}', 'RoleController@add')->name('.edit');
            Route::post('ajax_delete_image', 'RoleController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get', 'post'], 'delete/{id}', 'RoleController@delete')->name('.delete');
        });

        // permission

        Route::group(['prefix' => 'permission', 'as' => 'permission', 'middleware' => ['allowedmodule:permission,list']], function () {
            Route::match(['get', 'post'], '/', 'PermissionController@index')->name('.index');
            Route::match(['get', 'post'], '/update_permission', 'PermissionController@update_permission')->name('.update_permission');
        });






        ////groups
        Route::group(['prefix' => 'groups', 'as' => 'groups', 'middleware' => ['allowedmodule:groups,list']], function () {

            Route::match(['get', 'post'], '/', 'CategoryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'CategoryController@add')->name('.add');
            Route::match(['get', 'post'], 'add_tags', 'CategoryController@add_tags')->name('.add_tags');

            Route::match(['get', 'post'], 'change_subcategories_status', 'CategoryController@change_subcategories_status')->name('.change_subcategories_status');
            Route::match(['get', 'post'], 'edit/{id}', 'CategoryController@add')->name('.edit');
            Route::post('ajax_delete_image', 'CategoryController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get', 'post'], 'delete/{id}', 'CategoryController@delete')->name('.delete');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'CategoryController@delete_tags')->name('.delete_tags');
            Route::match(['get', 'post'], 'update_popular', 'CategoryController@update_popular')->name('.update_popular');
        });

        ////trustee
        Route::group(['prefix' => 'trustee', 'as' => 'trustee', 'middleware' => ['allowedmodule:gallery,list']], function () {
            Route::match(['get', 'post'], '/', 'CommonController@trusteeList')->name('.index');
        });
       
       
        ////commitee
        Route::group(['prefix' => 'commitee', 'as' => 'commitee', 'middleware' => ['allowedmodule:gallery,list']], function () {
            Route::match(['get', 'post'], '/', 'CommonController@commiteeList')->name('.index');
        });

        // SubCategory
        Route::group(['prefix' => 'subcategories', 'as' => 'subcategories', 'middleware' => ['allowedmodule:subcategories,list']], function () {

            Route::match(['get', 'post'], '/', 'SubCategoryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'SubCategoryController@add')->name('.add');
            Route::match(['get', 'post'], 'add_tags', 'SubCategoryController@add_tags')->name('.add_tags');

            Route::match(['get', 'post'], 'change_subcategories_status', 'SubCategoryController@change_subcategories_status')->name('.change_subcategories_status');
            Route::match(['get', 'post'], 'edit/{id}', 'SubCategoryController@add')->name('.edit');
            Route::post('ajax_delete_image', 'SubCategoryController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get', 'post'], 'delete/{id}', 'SubCategoryController@delete')->name('.delete');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'SubCategoryController@delete_tags')->name('.delete_tags');
            Route::match(['get', 'post'], 'update_popular', 'SubCategoryController@update_popular')->name('.update_popular');
        });

        //settings 
        Route::group(['prefix' => 'settings', 'as' => 'settings', 'middleware' => ['allowedmodule:settings,list']], function () {
            Route::match(['get', 'post'], '/', 'CommonController@settingsForm')->name('.index');
            Route::match(['get', 'post'], 'add', 'CommonController@settingsUpdate')->name('.add');
        });


        // products

        Route::group(['prefix' => 'products', 'as' => 'products', 'middleware' => ['allowedmodule:products,list']], function () {
            Route::match(['get', 'post'], '/', 'ProductController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'ProductController@add')->name('.add');
            Route::match(['get', 'post'], 'change_product_status', 'ProductController@change_product_status')->name('.change_product_status');
            Route::get('fetchSubCat', 'ProductController@fetchSubCat')->name('.fetchSubCat');
            Route::match(['get', 'post'], 'edit/{id}', 'ProductController@add')->name('.edit');
            Route::post('ajax_delete_image', 'ProductController@ajax_delete_image')->name('.ajax_delete_image');
            Route::match(['get', 'post'], 'delete/{id}', 'ProductController@delete')->name('.delete');
        });

        ////transactions
        Route::group(['prefix' => 'transactions', 'as' => 'transactions', 'middleware' => ['allowedmodule:transactions,list']], function () {

            Route::match(['get', 'post'], '/', 'TransactionController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'TransactionController@add')->name('.add');
            Route::match(['get', 'post'], 'change_banner_status', 'TransactionController@change_banner_status')->name('.change_banner_status');
            Route::match(['get', 'post'], 'edit/{id}', 'TransactionController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'TransactionController@delete')->name('.delete');
            Route::match(['get', 'post'], 'add_tags', 'TransactionController@add_tags')->name('.add_tags');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'TransactionController@delete_tags')->name('.delete_tags');
        });


        // user
        Route::group(['prefix' => 'user', 'as' => 'user', 'middleware' => ['allowedmodule:user,list']], function () {
            Route::match(['get', 'post'], '/', 'UserController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'UserController@add')->name('.add');
            Route::match(['get', 'post'], 'profile', 'UserController@profile')->name('.profile');
            Route::match(['get', 'post'], 'change_users_status', 'UserController@change_users_status')->name('.change_users_status');
            Route::get('fetchSubCat', 'UserController@fetchSubCat')->name('.fetchSubCat');
            Route::get('export', 'UserController@exportuser')->name('.export');
            Route::post('import', 'UserController@importuser')->name('.import');
            Route::match(['get', 'post'], 'edit/{id}', 'UserController@add')->name('.edit');
            Route::post('ajax_delete_image', 'UserController@ajax_delete_image')->name('.ajax_delete_image');
            Route::post('free_subscription', 'UserController@free_subscription')->name('.free_subscription');
            Route::post('credit_update', 'UserController@credit_update')->name('.credit_update');
            Route::post('update_profile', 'UserController@update_profile')->name('.update_profile');
            Route::match(['get', 'post'], 'delete/{id}', 'UserController@delete')->name('.delete');
            Route::match(['get', 'post'], 'update-group-name', 'UserController@updateGroupName')->name('.updateGroupName');
        });
        
        // contact_us
        Route::group(['prefix' => 'contact_us', 'as' => 'contact_us', 'middleware' => ['allowedmodule:contact_us,list']], function () {
            Route::match(['get', 'post'], '/', 'CommonController@contactUs')->name('.index');
            Route::match(['get', 'post'], 'change_contact_status', 'CommonController@changeCotactStatus')->name('.change_contact_status');
        });

        //Community Info 
        Route::group(['prefix' => 'community_info', 'as' => 'community_info' , 'middleware' => ['allowedmodule:community_info,list'] ], function() {
            Route::match(['get', 'post'],'/', 'CommonController@aboutUsForm')->name('.index');
            Route::match(['get', 'post'], 'add', 'CommonController@aboutUpdate')->name('.add');        
        });
        
        // booking_event_list
        Route::group(['prefix' => 'booking_event_list', 'as' => 'booking_event_list', 'middleware' => ['allowedmodule:booking_event_list,list']], function () {
            Route::match(['get', 'post'], '/', 'CommonController@bookingEventList')->name('.index');
        });

        ////banners
        Route::group(['prefix' => 'banners', 'as' => 'banners', 'middleware' => ['allowedmodule:banners,list']], function () {

            Route::match(['get', 'post'], '/', 'BannerController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'BannerController@add')->name('.add');
            Route::match(['get', 'post'], 'change_banner_status', 'BannerController@change_banner_status')->name('.change_banner_status');
            Route::match(['get', 'post'], 'edit/{id}', 'BannerController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'BannerController@delete')->name('.delete');
            Route::match(['get', 'post'], 'add_tags', 'BannerController@add_tags')->name('.add_tags');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'BannerController@delete_tags')->name('.delete_tags');
        });


        ////categories
        Route::group(['prefix' => 'blogs', 'as' => 'blogs', 'middleware' => ['allowedmodule:blogs,list']], function () {
            Route::match(['get', 'post'], '/', 'BlogController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'BlogController@add')->name('.add');
            Route::match(['get', 'post'], 'change_blog_status', 'BlogController@change_blog_status')->name('.change_blog_status');
            Route::match(['get', 'post'], 'edit/{id}', 'BlogController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'BlogController@delete')->name('.delete');
            Route::match(['get', 'post'], 'add_tags', 'BlogController@add_tags')->name('.add_tags');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'BlogController@delete_tags')->name('.delete_tags');
        });
        
        ////Events
        Route::group(['prefix' => 'events', 'as' => 'events', 'middleware' => ['allowedmodule:events,list']], function () {
            Route::match(['get', 'post'], '/', 'EventsController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'EventsController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'EventsController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'EventsController@delete')->name('.delete');
            Route::match(['get', 'post'], 'details/{id}', 'EventsController@details')->name('.details');
        });
        
        ////notifications
        Route::group(['prefix' => 'notifications', 'as' => 'notifications', 'middleware' => ['allowedmodule:notifications,list']], function () {
            Route::match(['get', 'post'], '/', 'NotificationController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'NotificationController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'NotificationController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'NotificationController@delete')->name('.delete');
        });
        
        ////news
        Route::group(['prefix' => 'news', 'as' => 'news', 'middleware' => ['allowedmodule:news,list']], function () {
            Route::match(['get', 'post'], '/', 'NewsController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'NewsController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'NewsController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'NewsController@delete')->name('.delete');
        });

        ////gallery
        Route::group(['prefix' => 'gallery', 'as' => 'gallery', 'middleware' => ['allowedmodule:gallery,list']], function () {

            Route::match(['get', 'post'], '/', 'GalleryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'GalleryController@add')->name('.add');
            Route::match(['get', 'post'], 'change_blog_status', 'GalleryController@change_blog_status')->name('.change_blog_status');
            Route::match(['get', 'post'], 'edit/{id}', 'GalleryController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'GalleryController@delete')->name('.delete');
            Route::match(['get', 'post'], 'add_tags', 'GalleryController@add_tags')->name('.add_tags');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'GalleryController@delete_tags')->name('.delete_tags');
        });

        Route::group(['prefix' => 'blog_category', 'as' => 'blog_category', 'middleware' => ['allowedmodule:blog_category,list']], function () {

            Route::match(['get', 'post'], '/', 'BlogCategoryController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'BlogController@add')->name('.add');
            Route::match(['get', 'post'], 'change_blog_category_status', 'BlogCategoryController@change_blog_category_status')->name('.change_blog_category_status');
            Route::match(['get', 'post'], 'edit/{id}', 'BlogController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'BlogCategoryController@delete')->name('.delete');
            Route::match(['get', 'post'], 'add_tags', 'BlogController@add_tags')->name('.add_tags');
            Route::match(['get', 'post'], 'delete_tags/{id}', 'BlogController@delete_tags')->name('.delete_tags');
            Route::match(['get', 'post'], 'update_blog_category', 'BlogController@update_blog_category')->name('.update_blog_category');
            Route::match(['get', 'post'], 'get_category_name', 'BlogCategoryController@get_category_name')->name('.get_category_name');
        });


        ////categories_seo
        Route::group(['prefix' => 'categories_seo', 'as' => 'categories_seo', 'middleware' => ['allowedmodule:categories_seo,list']], function () {

            Route::match(['get', 'post'], '/', 'CategorySEOController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'CategorySEOController@add')->name('.add');
            Route::match(['get', 'post'], 'change_blog_status', 'CategorySEOController@change_blog_status')->name('.change_blog_status');
            Route::match(['get', 'post'], 'view/{id}', 'CategorySEOController@view')->name('.view');
            Route::match(['get', 'post'], 'delete/{id}', 'CategorySEOController@delete')->name('.delete');
            Route::match(['get', 'post'], 'save_seo_data', 'CategorySEOController@save_seo_data')->name('.save_seo_data');
            Route::match(['get', 'post'], 'import', 'CategorySEOController@import')->name('.import');
            Route::match(['get', 'post'], 'update_category_seo', 'CategorySEOController@update_category_seo')->name('.update_category_seo');
        });


        ////locality
        Route::group(['prefix' => 'locality', 'as' => 'locality', 'middleware' => ['allowedmodule:locality,list']], function () {

            Route::get('/', 'LocalityController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'LocalityController@add')->name('.add');
            Route::match(['get', 'post'], '/edit/{id?}', 'LocalityController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'LocalityController@delete')->name('.delete');
            Route::match(['get', 'post'], 'get_state', 'LocalityController@get_state')->name('.get_state');
            Route::match(['get', 'post'], 'get_city', 'LocalityController@get_city')->name('.get_city');
            Route::match(['get', 'post'], 'export', 'LocalityController@export')->name('.export');
            Route::match(['get', 'post'], 'import', 'LocalityController@import')->name('.import');
        });
        ////businesses
        Route::group(['prefix' => 'businesses', 'as' => 'businesses', 'middleware' => ['allowedmodule:businesses,list']], function () {

            Route::match(['get', 'post'], '/', 'BusinessController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'BusinessController@add')->name('.add');
            Route::match(['get', 'post'], 'change_blog_status', 'BusinessController@change_blog_status')->name('.change_blog_status');
            Route::match(['get', 'post'], 'edit/{id}', 'BusinessController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'BusinessController@delete')->name('.delete');
        });
        //vendor
        Route::group(['prefix' => 'vendor_management', 'as' => 'vendor_management', 'middleware' => ['allowedmodule:vendor_management,list']], function () {

            Route::match(['get', 'post'], '/', 'VendorController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'VendorController@add')->name('.add');
            Route::match(['get', 'post'], 'save-vendor', 'VendorController@saveVendor')->name('.save-vendor');
            Route::match(['get', 'post'], 'change_blog_status', 'VendorController@change_blog_status')->name('.change_blog_status');
            Route::match(['get', 'post'], 'edit/{id}', 'VendorController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'VendorController@delete')->name('.delete');
            Route::match(['get', 'post'], 'fetchBusiness', 'VendorController@fetchBusiness')->name('.fetchBusiness');
            Route::match(['get', 'post'], 'sendAadharOTP', 'VendorController@sendAadharOTP')->name('.sendAadharOTP');
            Route::match(['get', 'post'], 'verifyAadharOTP', 'VendorController@verifyAadharOTP')->name('.verifyAadharOTP');


            // Route::post('saveVendor',[VendorController::class,'saveVendor'])->name('saveVendor');
            // Route::get('sendAadharOTP',[VendorController::class,'sendAadharOTP'])->name('sendAadharOTP');
            // Route::get('verifyAadharOTP',[VendorController::class,'verifyAadharOTP'])->name('verifyAadharOTP');


        });


        ////pages
        Route::group(['prefix' => 'pages', 'as' => 'pages', 'middleware' => ['allowedmodule:pages,list']], function () {

            Route::match(['get', 'post'], '/', 'PageController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'PageController@add')->name('.add');
            Route::match(['get', 'post'], 'change_page_status', 'PageController@change_page_status')->name('.change_page_status');
            Route::match(['get', 'post'], 'edit/{id}', 'PageController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'PageController@delete')->name('.delete');
        });
        ////pages
        Route::group(['prefix' => 'upload_on_root', 'as' => 'upload_on_root', 'middleware' => ['allowedmodule:upload_on_root,list']], function () {

            Route::match(['get', 'post'], '/', 'RootUploadController@index')->name('.index');
            Route::match(['get', 'post'], 'add', 'RootUploadController@add')->name('.add');
            Route::match(['get', 'post'], 'edit/{id}', 'RootUploadController@add')->name('.edit');
            Route::match(['get', 'post'], 'delete/{id}', 'RootUploadController@delete')->name('.delete');
        });
    });
});
