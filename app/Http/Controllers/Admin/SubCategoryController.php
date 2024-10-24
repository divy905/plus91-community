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
use App\Roles;
use App\Models\ExamCategory;
use App\Models\SubCategory;
use Storage;
use DB;
use Hash;



Class SubCategoryController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
       $data =[];
       $subcategories = SubCategory::where('is_delete',0)->orderBy('id','desc');

       $subcategories = $subcategories->paginate(10);
       $data['subcategories'] = $subcategories;

       return view('admin.subcategories.index',$data);
   }



   public function add(Request $request){
    $data = [];
    $id = (isset($request->id))?$request->id:0;

    $subcategories = '';
    if(!empty($id)){
        $subcategories = SubCategory::where('id',$id)->first();
        if(empty($subcategories)){
            return redirect($this->ADMIN_ROUTE_NAME.'/subcategories');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/subcategories';
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
        $alert_msg = 'SubCategory Category has been added successfully.';
        if(is_numeric($id) && $id > 0){
            $alert_msg = 'SubCategory Category has been updated successfully.';
        }
        return redirect(url($back_url))->with('alert-success', $alert_msg);
    } else {
        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }
}


$page_heading = 'Add SubCategory';

if(!empty($id)){
    $subcategories_name = $subcategories->name;
    $page_heading = 'Update SubCategory - '.$subcategories_name;
}  

$data['page_heading'] = $page_heading;
$data['id'] = $id;
$data['subcategories'] = $subcategories;

return view('admin.subcategories.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image','password']);

    $oldImg = '';

    $subcategory = new SubCategory;

    if(!empty($id)){
        $exist = SubCategory::where('id',$id)->first();

        if(isset($exist->id) && $exist->id == $id){
            $subcategory = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $subcategory->$key = $val;
    }

    $isSaved = $subcategory->save();

    if($isSaved){
        $this->saveImage($request, $subcategory, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $subcategory, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'subcategory/';
        $thumb_path = 'subcategory/thumb/';
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
            $subcategory->image = $image;
            $subcategory->save();         
        }

        if(!empty($uploaded_data)){   
            return $uploaded_data;
        }  

    }

}




public function delete(Request $request){

        //prd($request->toArray());

    $id = (isset($request->id))?$request->id:0;

    $is_delete = '';

    if(is_numeric($id) && $id > 0){
        $is_delete = SubCategory::where('id', $id)->update(['is_delete'=>1]);
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'SubCategory has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



public function change_subcategories_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $exam_categories = SubCategory::where('id',$id)->first();
  if(!empty($exam_categories)){

   SubCategory::where('id',$id)->update(['status'=>$status]);
   $response['success'] = true;
   $response['message'] = 'Status updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}



public function change_admins_role(Request $request){
  $admin_id = isset($request->admin_id) ? $request->admin_id :'';
  $role_id = isset($request->role_id) ? $request->role_id :'';

  $admins = Admin::where('id',$admin_id)->first();
  if(!empty($admins)){

   Admin::where('id',$admin_id)->update(['role_id'=>$role_id]);
   $response['success'] = true;
   $response['message'] = 'Role updated';


   return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}





public function change_admins_approve(Request $request){
 $admin_id = isset($request->admin_id) ? $request->admin_id :'';
 $approve = isset($request->approve) ? $request->approve :'';

 $admins = Admin::where('id',$admin_id)->first();
 if(!empty($admins)){

   Admin::where('id',$admin_id)->update(['is_approve'=>$approve]);
   $message ='';
   if($approve == 1){
    $message = 'Approved';
}else{
    $message = 'Not Approved';

}

$response['success'] = true;
$response['message'] = $message;


return response()->json($response);
}else{
   $response['success'] = false;
   $response['message'] = 'Not  Found';
   return response()->json($response);  
}

}



public function documents(Request $request){

 $society_id = isset($request->id) ? $request->id :0;
 $method = $request->method();

 $data = [];


 if($method == 'post' || $method == 'POST'){

    $rules = [];
    $rules['file'] = 'required';

    $this->validate($request,$rules);

    if($request->hasFile('file')) {

        $image_result = $this->saveImageMultiple($request,$society_id);
        if($image_result){
            return back()->with('alert-success', 'Image uploaded successfully.');

        }
    }


}

$documents = SocietyDocument::where('society_id',$society_id)->get();

$data['documents'] = $documents;

return view('admin.society.documents',$data);

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



}