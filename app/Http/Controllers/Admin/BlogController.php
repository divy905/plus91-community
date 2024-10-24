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
use App\Models\Category;
use App\Roles;
use App\Models\ExamCategory;
use Storage;
use DB;
use Hash;



Class BlogController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     $data =[];
     $blogs = Blogs::where('is_delete',0)->orderBy('id','desc');

     $blogs = $blogs->paginate(10);
     $data['blogs'] = $blogs;

     return view('admin.blogs.index',$data);
 }



 public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $blogs = '';
    if(!empty($id)){
        $blogs = Blogs::where('id',$id)->first();
        if(empty($blogs)){
            return redirect($this->ADMIN_ROUTE_NAME.'/blogs');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/blogs';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        // $rules['slug'] = 'nullable|sometimes|unique:blogs,slug';
        $rules['title'] = 'required';
        $rules['long_description'] = 'required';
        $rules['short_description'] = 'required';

        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'blogs has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'blogs has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Blogs';

    if(!empty($id)){
        $exam_categories_name = $blogs->title;
        $page_heading = 'Update Blogs - '.$exam_categories_name;
    } 

    $categories = DB::table('blog_category')->where('status',1)->get();
    // $categories = [];
    // if(!empty($blog_categories)){
    //     $categories = Category::whereIn('id',$blog_categories)->get();
    // } 

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['blogs'] = $blogs;
    $data['categories'] = $categories;


    return view('admin.blogs.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);
    
    if($id == 0){
        $slug = CustomHelper::GetSlug('blogs', 'id', '', $request->title);
        $data['slug'] = $slug;
    }
    $oldImg = '';

    $blogs = new Blogs;

    if(!empty($id)){
        $exist = Blogs::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $blogs = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $blogs->$key = $val;
    }

    $isSaved = $blogs->save();

    if($isSaved){
        $this->saveImage($request, $blogs, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $blogs, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'blogs/';
        $thumb_path = 'blogs/thumb/';
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
            $blogs->image = $image;
            $blogs->save();         
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






public function delete_tags(Request $request){

        //prd($request->toArray());

    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = DB::table('blog_tags')->where('id', $id)->delete();
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Tags has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}

public function add_tags(Request $request){

    if($request->method() == 'POST' || $request->method() == 'post'){

    $rules = [];
    $rules['blog_id'] = 'required';
    // $rules['tag_name'] = 'required';
    $this->validate($request,$rules);

    $dbArray = [];
    $tags =  explode(',',$request->tag_name);
    foreach($tags as $tag){
        $dbArray['blog_id'] = $request->blog_id;
        $dbArray['tag'] = $tag;
        $dbArray['meta_title'] = $request->meta_title;
        $dbArray['meta_description'] = $request->meta_description;
        DB::table('blog_tags')->insert($dbArray);
    }
    
    return back()->with('alert-success', 'Tags has been Added successfully.');


}
}


public function update_blog_category(Request $request){
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['id'] = 'required';
        // $rules['meta_title'] = 'required';
        // $rules['meta_description'] = 'required';
        $this->validate($request,$rules);

        $dbArray = [];
        $id = $request->id??'';
        $dbArray['meta_title'] = $request->meta_title??'';
        $dbArray['meta_description'] = $request->meta_description??'';
        $dbArray['og_title'] = $request->og_title??'';
        $dbArray['og_description'] = $request->og_description??'';
        $dbArray['canonical'] = $request->canonical??'';
        $dbArray['keywords'] = $request->keywords??'';
        $dbArray['robots'] = $request->robots??'';
        DB::table('blog_category')->where('id',$id)->update($dbArray);
        return back()->with('alert-success', ' Blog Category Update Successfully');

    }
}


}