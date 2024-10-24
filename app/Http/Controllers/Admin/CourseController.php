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
use App\Roles;
use App\Models\ExamCategory;
use App\Models\Content;
use App\Models\LiveClass;
use App\Models\Offer;
use Storage;
use DB;
use Hash;
use Image;


Class CourseController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
       $data =[];
       $courses = Course::where('is_delete',0)->orderBy('id','desc');

       $courses = $courses->paginate(10);
       $data['courses'] = $courses;

       return view('admin.courses.index',$data);
   }



   public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $courses = '';
    if(!empty($id)){
        $courses = Course::where('id',$id)->first();
        if(empty($courses)){
            return redirect($this->ADMIN_ROUTE_NAME.'/courses');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/courses';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        

        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Course has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Course has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Course';

    if(!empty($id)){
        $exam_categories_name = $courses->name;
        $page_heading = 'Update Course - '.$exam_categories_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['courses'] = $courses;

    $subcategories = [];
    $subcategories = SubCategory::where('status',1)->where('is_delete',0)->get();

    if(!empty($id)){
        $category_ids = explode(",", $courses->category_id);
        $subcategories = SubCategory::whereIn('category_id',$category_ids)->get();
    }

    $data['subcategories'] = $subcategories;

    return view('admin.courses.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);
    
    
    if(!empty($request->category_id)){
        $data['category_id'] = implode(",", $request->category_id);
    }
    if(!empty($request->subcategory_id)){
        $data['subcategory_id'] = implode(",", $request->subcategory_id);
    }

    $oldImg = '';

    $course = new Course;

    if(!empty($id)){
        $exist = Course::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $course = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $course->$key = $val;
    }

    $isSaved = $course->save();

    if($isSaved){
        $this->saveImage($request, $course, $oldImg);
        $this->saveThumbImage($request, $course, $oldImg);
        // $this->saveCategory($request, $course);

    }

    return $isSaved;
}

// private function saveImage($request, $course, $oldImg=''){
//     // prd($request->toArray());

//    $file = $request->file('image');
//    $imageType = $file->getClientOriginalExtension();

//    $image_resize = Image::make($file)->encode( $imageType );            
//    $course->new_image = $image_resize;
//    $course->new_image_type = $imageType;
//    $course->save();


//    // prd($course);

// }
// private function saveCategory($request, $course){

// }



private function saveImage($request, $course, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'course/';
        $thumb_path = 'course/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

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
            $course->image = $image;
            $course->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}


private function saveThumbImage($request, $course, $oldImg=''){

    $file = $request->file('thumbnail');
    if ($file) {
        $path = 'course/';
        $thumb_path = 'course/thumb/';
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

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
            $course->thumbnail = $image;
            $course->save();         
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
        $is_delete = Course::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Course has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_course_status(Request $request){
  $course_id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $data = Course::where('id',$course_id)->first();
  if(!empty($data)){

   Course::where('id',$course_id)->update(['status'=>$status]);
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




public function contents(Request $request){
   $data =[];
   $id = $request->id??'';
   $type = $request->type??'';
   $course = Course::where('id',$id)->first();

   $data['course'] = $course;

   $videos = Content::where('hls_type','video')->where('course_id',$id)->paginate(10, ['*'], 'video');
   $notes = Content::where('hls_type','notes')->where('course_id',$id)->paginate(10, ['*'], 'notes');

   $coupons = Offer::where('course_id',$id)->paginate(10, ['*'], 'coupons');

   $live_classes = LiveClass::where('course_id',$id)->paginate(10, ['*'], 'live_classes');

   $data['videos'] = $videos;
   $data['notes'] = $notes;
   $data['coupons'] = $coupons;
   $data['live_classes'] = $live_classes;


   return view('admin.courses.contents',$data);
}

public function upload_content(Request $request){
    $method = $request->method();
    if($method == 'POST' || $method == 'post'){


     $id = $request->id??0;
     if($id == 0){
        $rules = [
         'course_id' => 'required',
         'title' => 'required',
         'hls_type' => 'required',
         'hls' => 'required',
     ];
 }else{
    $rules = [
     'course_id' => 'required',
     'title' => 'required',
 ];
}



$validator =  Validator::make($request->all(), $rules);
if($validator->fails()){
    $messages = $validator->messages();
    foreach ($rules as $key => $value)
    {
        $verrors[] = $messages->first($key);
    }
    return json_encode(['status'=>false,'message'=>$verrors]);
}else{

    $success = $this->save_contents($request,$id);
    if($success){
        return json_encode(['status'=>true,'message'=>'Added Successfully']);

    }else{

        return json_encode(['status'=>false,'message'=>'Something Went Wrong']);
    }

}
}
}

public function save_contents($request,$id){
    $dbArray = [];
    $dbArray['course_id'] = $request->course_id;
    $dbArray['hls_type'] = $request->hls_type;
    $dbArray['type'] = $request->type;
    $dbArray['title'] = $request->title;
    if($request->hls_type == 'video'){
        $dbArray['hls'] = $request->hls;
    }
    if($request->hls_type == 'notes'){
        $file = $request->file('hls');
        if($file){
            $dbArray['hls'] = $this->save_file($request);
        }
    }

    if($id == 0){
        $success = Content::insert($dbArray);

    }else{
        $success = Content::where('id',$id)->update($dbArray);

    }

    return $success;

}


private function save_file($request){
    $file = $request->file('hls');
    if ($file) {
        $path = 'contents/';
        $storage = Storage::disk('public');
        $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
        if($uploaded_data['success']){
            $image = $uploaded_data['file_name'];
            return $image;   
        }

    }else{
        return '';
    }

}



public function update_live_class(Request $request){

    $method = $request->method();

    $id = $request->id??0;
    if($method == 'post' || $method == 'POST'){
        if($id == 0){
         $rules = [
             'type' => 'required',
             'title' => 'required',
             'youtube_link' => 'required',
             'course_id' => 'required',
             'start_date' => 'required',
             'start_time' => 'required',
             'end_date' => 'required',
             'end_time' => 'required',
             'image' => 'required',
         ]; 
     }else{
        $rules = [
         'title' => 'required',
         'youtube_link' => 'required',
         'course_id' => 'required',
     ]; 
 }


 $validator =  Validator::make($request->all(), $rules);
 if($validator->fails()){
    $messages = $validator->messages();
    foreach ($rules as $key => $value)
    {
        $verrors[] = $messages->first($key);
    }
    return json_encode(['status'=>false,'message'=>$verrors]);
}else{

    $success = $this->save_live_class($request,$id);
    if($success){
        return json_encode(['status'=>true,'message'=>'Added Successfully']);

    }else{

        return json_encode(['status'=>false,'message'=>'Something Went Wrong']);
    }

}
}

}


public function save_live_class($request ,$id = 0){
    $dbArray = [];
    if(!empty($request->type)){
        $dbArray['type'] = $request->type;
    }
    if(!empty($request->title)){
        $dbArray['title'] = $request->title;
    }
    if(!empty($request->youtube_link)){
        $dbArray['youtube_link'] = $request->youtube_link;
    }
    if(!empty($request->course_id)){
        $dbArray['course_id'] = $request->course_id;
    }
    if(!empty($request->start_date)){
        $dbArray['start_date'] = $request->start_date;
    }
    if(!empty($request->start_time)){
        $dbArray['start_time'] = $request->start_time;
    }
    if(!empty($request->faculty_id)){
        $dbArray['faculty_id'] = $request->faculty_id;
    }
    if(!empty($request->end_date)){
        $dbArray['end_date'] = $request->end_date;
    }
    if(!empty($request->end_time)){
        $dbArray['end_time'] = $request->end_time;
    }
    $file = $request->file('image');
    if($file){
        $dbArray['image'] = $this->save_live_class_file($request);
    }
    if($id == 0){
     $success = LiveClass::insert($dbArray);
 }else{
     $success = LiveClass::where('id',$id)->update($dbArray);
 }

 return $success;
}


private function save_live_class_file($request){
    $file = $request->file('image');
    if ($file) {
        $path = 'live_class/';
        $storage = Storage::disk('public');
        $uploaded_data = CustomHelper::UploadFile($file, $path, $ext='');
        if($uploaded_data['success']){
            $image = $uploaded_data['file_name'];
            return $image;   
        }
    }else{
        return '';
    }
}


















public function add_coupon(Request $request){
    $method = $request->method();
    $id = $request->id??0;
    if($method == 'post' || $method == 'POST'){
        if($id == 0){
         $rules = [
             'offer_code' => 'required',
             'offer_type' => 'required',
             'offer_value' => 'required',
             'description' => 'required',
             'terms' => 'required',
             'min_cart_value' => 'required',
             'max_discount' => 'required',
             'allowed_user_times' => 'required',
             'no_of_times' => 'required',
             'start_date' => 'required',
             'end_date' => 'required',
         ]; 
     }else{
        $rules = [
            'id'=>'required',
            'offer_code' => 'required',
            'offer_type' => 'required',
            'offer_value' => 'required',
            'description' => 'required',
            'terms' => 'required',
            'min_cart_value' => 'required',
            'max_discount' => 'required',
            'allowed_user_times' => 'required',
            'no_of_times' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]; 
    }


    $validator =  Validator::make($request->all(), $rules);
    if($validator->fails()){
        $messages = $validator->messages();
        foreach ($rules as $key => $value)
        {
            $verrors[] = $messages->first($key);
        }
        return json_encode(['status'=>false,'message'=>$verrors]);
    }else{

        $success = $this->save_offers($request,$id);
        if($success){
            return json_encode(['status'=>true,'message'=>'Added Successfully']);

        }else{

            return json_encode(['status'=>false,'message'=>'Something Went Wrong']);
        }

    }
}

}


public function save_offers($request ,$id = 0){
     $dbArray = [];
    if(!empty($request->type)){
        $dbArray['type'] = $request->type;
    }
    if(!empty($request->title)){
        $dbArray['title'] = $request->title;
    }
    if(!empty($request->youtube_link)){
        $dbArray['youtube_link'] = $request->youtube_link;
    }
    if(!empty($request->course_id)){
        $dbArray['course_id'] = $request->course_id;
    }
    if(!empty($request->start_date)){
        $dbArray['start_date'] = $request->start_date;
    }
    if(!empty($request->start_time)){
        $dbArray['start_time'] = $request->start_time;
    }
    if(!empty($request->faculty_id)){
        $dbArray['faculty_id'] = $request->faculty_id;
    }
    if(!empty($request->end_date)){
        $dbArray['end_date'] = $request->end_date;
    }
    if(!empty($request->end_time)){
        $dbArray['end_time'] = $request->end_time;
    }
    $file = $request->file('image');
    if($file){
        $dbArray['image'] = $this->save_live_class_file($request);
    }
    if($id == 0){
     $success = LiveClass::insert($dbArray);
 }else{
     $success = LiveClass::where('id',$id)->update($dbArray);
 }

 return $success;
}




}