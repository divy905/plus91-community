<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Foundation\Bus\DispatchesJobs;

use Illuminate\Http\Request;

use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Routing\Controller as BaseController;

use App\Http\Controllers\Controller;

use App\Helpers\CustomHelper;

use Auth;

use Illuminate\Support\Str;

use Validator;

use App\Models\Admin;

use App\Roles;

use App\Models\Category;

use App\Models\City;

use App\Models\CategorySEO;

use Storage;

use DB;

use Hash;



use Excel;

use App\Imports\CategorySEOImport;



Class CategorySEOController extends Controller

{





	private $ADMIN_ROUTE_NAME;



	public function __construct(){



		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();



	}







	public function index(Request $request){

     $data =[];

     $categories = Category::where('is_delete',0)->latest();

     $search = $request->search??'';

     $type = $request->type??'';

     if(!empty($search)){

        $categories->where('name','like','%'.$search.'%');

    }

    if(!empty($type)){

        $categories->where('type',$type);

    }

    $categories = $categories->paginate(50);

    $data['categories'] = $categories;



    return view('admin.categories_seo.index',$data);

}



public function view(Request $request){
    $data = [];

    $cities = [];

    $id = $request->id??'';

    $search = $request->search??'';

    $category = Category::where('id',$id)->first();

    $categories_seos = [];
    // print_r($request->all());die();
    $categories_seos = CategorySEO::where(['category_id'=>$category->slug,'city_id'=>$request->city_id]);

    $locality_slug = Str::slug($search);
    // print_r($locality_slug);die();
    if(!empty($locality_slug)){
        $categories_seos->where('locality_id','like','%'.$locality_slug.'%');
    }
$categories_seos = $categories_seos->paginate(10);



$cities = City::whereIn('id',['132005','133230','131853','132032'])->get();

$data['category'] = $category;

$data['cities'] = $cities;

$data['categories_seos'] = $categories_seos;
return view('admin.categories_seo.view',$data);

}


public function add(Request $request){

    $data = [];

    $id = (isset($request->id))?$request->id:0;



    $categories = '';

    if(!empty($id)){

        $categories = Category::where('id',$id)->first();

        if(empty($categories)){

            return redirect($this->ADMIN_ROUTE_NAME.'/categories');

        }

    }



    if($request->method() == 'POST' || $request->method() == 'post'){



        if(empty($back_url)){

            $back_url = $this->ADMIN_ROUTE_NAME.'/categories';

        }



        $name = (isset($request->name))?$request->name:'';





        $rules = [];

        if(is_numeric($id) && $id > 0){

         // $rules['name'] = 'required';



        }else{



        }







        $this->validate($request, $rules);



        $createdCat = $this->save($request, $id);



        if ($createdCat) {

            $alert_msg = 'Category has been added successfully.';

            if(is_numeric($id) && $id > 0){

                $alert_msg = 'Category has been updated successfully.';

            }

            return redirect(url($back_url))->with('alert-success', $alert_msg);

        } else {

            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');

        }

    }





    $page_heading = 'Add Category';



    if(!empty($id)){

        $categories_name = $categories->name;

        $page_heading = 'Update Category - '.$categories_name;

    }  



    $data['page_heading'] = $page_heading;

    $data['id'] = $id;

    $data['categories'] = $categories;



    return view('admin.categories.form', $data);



}













public function save(Request $request, $id=0){



    $data = $request->except(['_token', 'back_url', 'image','password','image_name','main_image','mainimage_name']);

    if($id == 0){



    }



    $categories = new Category;

    $old_img = '';

    $old_img1 = '';

    if(!empty($id)){

        $exist = Category::where('id',$id)->first();



        if(isset($exist->id) && $exist->id == $id){

            $categories = $exist;



            $old_img = $exist->image;

            $old_img1 = $exist->main_image;

        }

    }

        //prd($oldImg);



    foreach($data as $key=>$val){

        $categories->$key = $val;

    }



    $isSaved = $categories->save();



    if($isSaved){

        $this->saveImage($request , $categories , $old_img);

        $this->saveMainImage($request , $categories , $old_img1);

        $this->saveOgImage($request , $categories);

    }



    return $isSaved;

}





private function saveOgImage($request, $categories){



    $file = $request->file('og_image');

    //prd($file);

    if ($file) {

        $path = 'category/';

        $thumb_path = 'category/thumb/';

        $storage = Storage::disk('public');

            //prd($storage);

        $IMG_WIDTH = 768;

        $IMG_HEIGHT = 768;

        $THUMB_WIDTH = 336;

        $THUMB_HEIGHT = 336;



        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT,$request->image_name);



            // prd($uploaded_data);

        if($uploaded_data['success']){



            $image = $uploaded_data['file_name'];

            $categories->og_image = $image;

            $categories->save();         

        }



        if(!empty($uploaded_data)){   

            return  $uploaded_data;

        }  



    }



}

private function saveImage($request, $categories, $oldImg=''){



    $file = $request->file('image');

    //prd($file);

    if ($file) {

        $path = 'category/';

        $thumb_path = 'category/thumb/';

        $storage = Storage::disk('public');

            //prd($storage);

        $IMG_WIDTH = 768;

        $IMG_HEIGHT = 768;

        $THUMB_WIDTH = 336;

        $THUMB_HEIGHT = 336;



        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT,$request->image_name);



            // prd($uploaded_data);

        if($uploaded_data['success']){



            if(!empty($oldImg)){

                if($storage->exists($path.$oldImg)){

                    $storage->delete($path.$oldImg);

                }

                if($storage->exists($thumb_path.$oldImg)){

                    $storage->delete($thumb_path.$oldImg);

                }

            }

            $image = $uploaded_data['file_name'];



           // prd($image);

            $categories->image = $image;

            $categories->save();         

        }



        if(!empty($uploaded_data)){   

            return  $uploaded_data;

        }  



    }



}





private function saveMainImage($request, $categories, $oldImg=''){



    $file = $request->file('main_image');

    //prd($file);

    if ($file) {

        $path = 'category/';

        $thumb_path = 'category/thumb/';

        $storage = Storage::disk('public');

            //prd($storage);

        $IMG_WIDTH = 768;

        $IMG_HEIGHT = 768;

        $THUMB_WIDTH = 336;

        $THUMB_HEIGHT = 336;



        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT,$request->mainimage_name);



            // prd($uploaded_data);

        if($uploaded_data['success']){



            if(!empty($oldImg)){

                if($storage->exists($path.$oldImg)){

                    $storage->delete($path.$oldImg);

                }

                if($storage->exists($thumb_path.$oldImg)){

                    $storage->delete($thumb_path.$oldImg);

                }

            }

            $image = $uploaded_data['file_name'];

            $categories->main_image = $image;

            $categories->save();         

        }



        if(!empty($uploaded_data)){   

            return  $uploaded_data;

        }  



    }



}







public function delete_tags(Request $request){



        //prd($request->toArray());



    $id = (isset($request->id))?$request->id:0;



    $is_delete = '';



    if(is_numeric($id) && $id > 0){

        $is_delete = DB::table('category_tags')->where('id', $id)->delete();

    }



    if(!empty($is_delete)){

        return back()->with('alert-success', 'Tags has been deleted successfully.');

    }

    else{

        return back()->with('alert-danger', 'something went wrong, please try again...');

    }

}



public function delete(Request $request){



        //prd($request->toArray());



    $id = (isset($request->id))?$request->id:0;



    $is_delete = '';



    if(is_numeric($id) && $id > 0){

        $is_delete = DB::table('categories_seo')->where('id', $id)->delete();

    }



    if(!empty($is_delete)){

        return back()->with('alert-success', 'Data has been deleted successfully.');

    }

    else{

        return back()->with('alert-danger', 'something went wrong, please try again...');

    }

}



public function add_tags(Request $request){



    if($request->method() == 'POST' || $request->method() == 'post'){



        $rules = [];

        $rules['category_id'] = 'required';

        $rules['tag_name'] = 'required';

        $this->validate($request,$rules);



        $dbArray = [];

        $dbArray['category_id'] = $request->category_id;

        $dbArray['tag'] = $request->tag_name;

        DB::table('category_tags')->insert($dbArray);

        return back()->with('alert-success', 'Tags has been Added successfully.');





    }

}





public function update_popular(Request $request){

  $cat_id = isset($request->cat_id) ? $request->cat_id :'';

  $is_popular = isset($request->is_popular) ? $request->is_popular :'';



  $categories = Category::where('id',$cat_id)->first();

  if(!empty($categories)){



   Category::where('id',$cat_id)->update(['is_popular'=>$is_popular]);

   $response['success'] = true;

   $response['message'] = 'Status updated';





   return response()->json($response);

}else{

   $response['success'] = false;

   $response['message'] = 'Not  Found';

   return response()->json($response);  

}

}
public function save_seo_data(Request $request){

    $method = $request->method();

    if($method == 'post' || $method == 'POST'){

        $rules = [];

        $rules['city_id'] = 'required';

        $rules['locality'] = 'required';

        $rules['meta_title'] = 'required';

        $rules['meta_description'] = 'required';

        $rules['category_id'] = 'required';

        $this->validate($request,$rules);
        $is_already_exists = DB::table('categories_seo')->where('city_id',$request->city_id)->where('locality_id',$request->locality)->where('category_id',$request->category_id)->first();
        if(!empty($is_already_exists)){
            return back()->with('alert-danger', 'Locality already exists with this city');
        }
        $dbArray = [];

        $dbArray['city_id'] = $request->city_id??'';

        $dbArray['locality_id'] = $request->locality??'';

        $dbArray['meta_title'] = $request->meta_title??'';

        $dbArray['meta_description'] = $request->meta_description??'';

        $dbArray['meta_keyword'] = $request->meta_keyword??'';

        $dbArray['category_id'] = $request->category_id??'';

        $dbArray['about'] = $request->about??'';

        $dbArray['canonical'] = $request->canonical??'';

        $dbArray['robots'] = $request->robots??'';

        $file = $request->file('og_image');
        if ($file) {
            $path = 'category/';
            $thumb_path = 'category/thumb/';
            $storage = Storage::disk('public');
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;
            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT,$request->image_name);
            if($uploaded_data['success']){
                $image = $uploaded_data['file_name'];
                $dbArray['image'] = $image;
            }
        }
        DB::table('categories_seo')->insert($dbArray);
        return back()->with('alert-success', 'Added successfully.');
    }else{
        return back()->with('alert-danger', 'something went Wrong.');
    }

}


public function import(Request $request){



   $method = $request->method();

   if($method == 'post' || $method == 'POST'){

    $rules = [];

    $rules['importfile'] = 'required';

    $message = ['importfile.required' => 'You have to choose the file!',];

    $this->validate($request,$rules,$message);

    $sucess = Excel::import(new CategorySEOImport,request()->file('importfile'));

    return back()->with('alert-success', ' Imported Successfully');

}

}





public function update_category_seo(Request $request){

    $method = $request->method();

    if($method == 'post' || $method == 'POST'){

        $rules = [];

        $rules['id'] = 'required';

        $rules['meta_title'] = 'required';

        $rules['meta_description'] = 'required';

        $this->validate($request,$rules);



        $dbArray = [];

        $id = $request->id??'';

        $dbArray['meta_title'] = $request->meta_title??'';

        $dbArray['meta_keyword'] = $request->meta_keyword??'';

        $dbArray['meta_description'] = $request->meta_description??'';

        $dbArray['about'] = $request->about??'';

        $dbArray['canonical'] = $request->canonical??'';

        $dbArray['robots'] = $request->robots??'';
        $file = $request->file('og_image');
        if ($file) {
            $path = 'category/';
            $thumb_path = 'category/thumb/';
            $storage = Storage::disk('public');
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;
            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT,$request->image_name);
            if($uploaded_data['success']){
                $image = $uploaded_data['file_name'];
                $dbArray['image'] = $image;
            }
        }

        CategorySEO::where('id',$id)->update($dbArray);

        return back()->with('alert-success', ' CategorySEO Update Successfully');



    }

}



}