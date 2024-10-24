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
use Validator;
use App\Models\Admin;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\SubCategory;
use App\Models\Blogs;
use App\Roles;
use App\Models\ExamCategory;
use App\Models\Business;
use Storage;
use DB;
use Hash;



Class BusinessController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     $data =[];
     $search = $request->search??'';
     $category_id = $request->category_id??'';
     $type = $request->type??'';
     $start_date = $request->start_date??'';
     $end_date = $request->end_date??'';
     $city_id = $request->city_id??'';
     $businesses = Business::where('is_delete',0)->whereIn('business_type',['service','services','shop','shops'])->orderBy('id','desc');
     if(!empty($search)){
        $businesses->where('business_name','like','%'.$search.'%');
    }
    if(!empty($category_id)){
        $businesses->whereRaw('FIND_IN_SET(?, category_ids)', [$category_id]);
    }
    if(!empty($type)){
        $businesses->where('business_type',$type);
    }
    if(!empty($city_id)){
        $businesses->where('city_id',$city_id);
    }
    if(!empty($start_date)){
        $businesses->whereDate('created_at','>=',$start_date);
    }
    if(!empty($end_date)){
        $businesses->whereDate('created_at','<=',$end_date);

    }
    $businesses = $businesses->paginate(50);
    $data['businesses'] = $businesses;

    return view('admin.businesses.index',$data);
}



public function add(Request $request){
    // print_r($request->all());die();
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $businesses = '';
    if(!empty($id)){
        $businesses = Business::where('id',$id)->first();
        if(empty($businesses)){
            return redirect($this->ADMIN_ROUTE_NAME.'/businesses');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/businesses';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        // $rules['slug'] = 'nullable|sometimes|unique:businesses,slug';
        // $rules['title'] = 'required';
        // $rules['long_description'] = 'required';
        // $rules['short_description'] = 'required';

        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Businesses has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Businesses has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Businesses';
    $business_gallery = [];
    if(!empty($id)){
        $exam_categories_name = $businesses->business_name;
        $page_heading = 'Update Businesses - '.$exam_categories_name;
        $business_gallery = DB::table('business_gallery')->where('business_id',$id)->get();
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['businesses'] = $businesses;
    $data['business_gallery'] = $business_gallery;

    return view('admin.businesses.form', $data);

}






public function save(Request $request, $id=0){
    // prd($request->toArray());

    $data = $request->except(['_token', 'back_url', 'image','password','image_name','galleryIds','titleArr','alt_tag']);
    
    if($id == 0){
        $slug = CustomHelper::GetSlug('businesses', 'id', '', $request->title);
        $data['slug'] = $slug;
    }
    $oldImg = '';

    $businesses = new Business;

    if(!empty($id)){
        $exist = Business::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $businesses = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $businesses->$key = $val;
    }

    $isSaved = $businesses->save();

    if($isSaved){
        $this->saveImage($request, $businesses, $oldImg);

        $galleryIds = $request->galleryIds??'';
        $titleArr = $request->titleArr??'';
        $alt_tag = $request->alt_tag??'';
        if(!empty($galleryIds)){
            foreach ($galleryIds as $key => $value) {
             $dbArray = [];
             $dbArray['title'] = $titleArr[$key]??'';
             $dbArray['alt_tag'] = $alt_tag[$key]??'';
             DB::table('business_gallery')->where('id',$value)->update($dbArray);
         }
     }



 }

 return $isSaved;
}


private function saveImage($request, $businesses, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'business_gallery/';
        $thumb_path = 'business_gallery/thumb/';
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
            $businesses->image = $image;
            $businesses->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}




public function delete(Request $request){
    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = Blogs::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Blogs has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_blog_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $data = Blogs::where('id',$id)->first();
  if(!empty($data)){

     Blogs::where('id',$id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'Not  Found';
     return response()->json($response);  
 }

}


private function saveImageMultiple($request,$society_id){

    $files = $request->file('file');
    $path = 'societydocument/';
    $storage = Storage::disk('public');
            //prd($storage);
    $IMG_WIDTH = 768;
    $IMG_HEIGHT = 768;
    $THUMB_WIDTH = 336;
    $THUMB_HEIGHT = 336;
    $dbArray = [];

    if (!empty($files)) {

        foreach($files as $file){
            $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
            if($uploaded_data['success']){
                $image = $uploaded_data['file_name'];
                $dbArray['file'] = $image;
                $dbArray['society_id'] = $society_id;

                $success = SocietyDocument::create($dbArray);
            }
        }
        return true;
    }else{
        return false;
    }
}

public function assign_types(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['course_id'] = 'required';
        $rules['type_ids'] = 'required';
        $this->validate($request,$rules);

        $type_ids = $request->type_ids??'';
        if(!empty($type_ids)){
            CourseType::where('course_id',$request->course_id)->delete();
            foreach($type_ids as $type_id =>$value){
                $dbArray = [];
                $dbArray['course_id'] = $request->course_id??'';
                $dbArray['type_id'] = $value;
                CourseType::insert($dbArray);
            }
        }
        return back()->with('alert-success', 'Course Type Assign successfully.');

    }
}


}