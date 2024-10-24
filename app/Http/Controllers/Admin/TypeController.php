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
use App\Models\Types;
use App\Roles;
use App\Models\ExamCategory;
use Storage;
use DB;
use Hash;



Class TypeController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     $data =[];
     $types = Types::where('is_delete',0)->orderBy('id','desc');

     $types = $types->paginate(10);
     $data['types'] = $types;

     return view('admin.types.index',$data);
 }



 public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $types = '';
    if(!empty($id)){
        $types = Types::where('id',$id)->first();
        if(empty($types)){
            return redirect($this->ADMIN_ROUTE_NAME.'/types');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/types';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];
        

        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Types has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Types has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Types';

    if(!empty($id)){
        $exam_categories_name = $types->name;
        $page_heading = 'Update Types - '.$exam_categories_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['types'] = $types;

    return view('admin.types.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);
    

    $oldImg = '';

    $types = new Types;

    if(!empty($id)){
        $exist = Types::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $types = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $types->$key = $val;
    }

    $isSaved = $types->save();

    if($isSaved){
        $this->saveImage($request, $types, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $types, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'types/';
        $thumb_path = 'types/thumb/';
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
            $types->image = $image;
            $types->save();         
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
        $is_delete = Types::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Types has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_type_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $data = Types::where('id',$id)->first();
  if(!empty($data)){

     Types::where('id',$id)->update(['status'=>$status]);
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