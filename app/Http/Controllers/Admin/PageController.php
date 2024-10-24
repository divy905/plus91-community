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
use App\Models\Page;
use App\Roles;
use App\Models\ExamCategory;
use Storage;
use DB;
use Hash;



Class PageController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     $data =[];
     $search = $request->search??'';
     $pages = Page::where('is_delete',0)->orderBy('id','desc');
     if(!empty($search)){
        $pages->where('name','like','%'.$search.'%');
     }
     $pages = $pages->paginate(10);
     $data['pages'] = $pages;

     return view('admin.pages.index',$data);
 }



 public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $pages = '';
    if(!empty($id)){
        $pages = Page::where('id',$id)->first();
        if(empty($pages)){
            return redirect($this->ADMIN_ROUTE_NAME.'/pages');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/pages';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        // $rules['slug'] = 'nullable|sometimes|unique:pages,slug';
        $rules['name'] = 'required';
        $rules['slug'] = 'required';
        // $rules['long_description'] = 'required';
        // $rules['short_description'] = 'required';

        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Pages has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Pages has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Pages';

    if(!empty($id)){
        $exam_categories_name = $pages->name;
        $page_heading = 'Update Pages - '.$exam_categories_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['pages'] = $pages;

    return view('admin.pages.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);
    
    if($id == 0){
        if(empty($request->slug)){
            $slug = CustomHelper::GetSlug('pages', 'id', '', $request->title);
            $data['slug'] = $slug;
        }
        
    }
    $oldImg = '';

    $pages = new Page;

    if(!empty($id)){
        $exist = Page::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $pages = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $pages->$key = $val;
    }

    $isSaved = $pages->save();

    if($isSaved){
        // $this->saveImage($request, $pages, $oldImg);
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
        $is_delete = Page::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Page has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_page_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $data = Page::where('id',$id)->first();
  if(!empty($data)){

     Page::where('id',$id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'Not  Found';
     return response()->json($response);  
 }

}




}