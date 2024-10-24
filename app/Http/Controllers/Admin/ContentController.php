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
use App\Models\Banner;
use App\Roles;
use App\Models\ExamCategory;
use App\Models\Content;
use Storage;
use DB;
use Hash;



Class ContentController extends Controller
{


    private $ADMIN_ROUTE_NAME;

    public function __construct(){

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

    }


    public function index(Request $request)
    {
        $contents = Content::where('is_delete',0)->paginate(10);
        $data['contents'] = $contents;
        return view('admin.contents.index',$data);
    }


    public function add(Request $request)
    {
       $details = [];
       
       $id = isset($request->id) ? $request->id : 0;

       $contents = '';

       if(is_numeric($id) && $id > 0)
       {

        $contents = Content::find($id);

        if(empty($contents))
        {
            return redirect($this->ADMIN_ROUTE_NAME.'/contents');
        }
    }
    

    if($request->method() == "POST" || $request->method() == "post")
    {
        
            // prd($request->toArray());

        if(empty($back_url))
        {
           $back_url = $this->ADMIN_ROUTE_NAME.'/contents';
       }


       if(is_numeric($request->id) && $request->id > 0)
       {                 
         
        $details['image'] = '';               
        
        
    }else{

        // $details['image'] = 'required';               
        
    }

    $this->validate($request , $details); 

    $createdDetails = $this->save($request , $id);

    if($createdDetails)
    {
        $alert_msg = "Contents Created Successfully";

        if(is_numeric($id) & $id > 0)
        {
            $alert_msg = "Contents Updated Successfully";
        } 
        return redirect(url($back_url))->with('alert-success',$alert_msg);
    }else{

        return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
    }

}

$page_Heading = "Add Contents";


if(is_numeric($id) && $id > 0){
    $page_Heading = 'Update Contents';
}

$details['page_Heading'] = $page_Heading;

$details['contents'] = $contents;      

return view('admin.contents.form',$details);

}


public function save(Request $request, $id = 0)
{
        // prd($request->toArray());

    $details = $request->except(['_token', 'back_url']);


    $old_img = '';

    $contents = new Content;

    if(is_numeric($id) && $id > 0)
    {
        $exist = Content::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $contents = $exist;
            $old_img = $exist->image;
        }
    }

    foreach($details as $key => $val)
    {
        $contents->$key = $val;
    }

    $isSaved = $contents->save();

    if($isSaved)
    {
        $this->saveImage($request , $contents , $old_img);
    }

    return $isSaved;
}

private function saveImage($request, $contents, $oldImg=''){

    $file = $request->file('image');

    //prd($file);
    if ($file) {
        $path = 'contents/';        
        $thumb_path = 'contents/thumb/';        
        $storage = Storage::disk('public');
            //prd($storage);
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $THUMB_WIDTH, $THUMB_HEIGHT);

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

            
            $contents->image = $image;
            $contents->save();         
        }
        if(!empty($uploaded_data)){   
            return  $uploaded_data;
        }  

    }

}

public function change_contents_status(Request $request){
  $id = isset($request->id) ? $request->id :'';
  $status = isset($request->status) ? $request->status :'';

  $contents = Content::where('id',$id)->first();
  if(!empty($contents)){

     Content::where('id',$id)->update(['status'=>$status]);
     $response['success'] = true;
     $response['message'] = 'Status updated';


     return response()->json($response);
 }else{
     $response['success'] = false;
     $response['message'] = 'Not  Found';
     return response()->json($response);  
 }

}

public function delete(Request $request)
{
   $id = isset($request->id) ? $request->id : 0;



   $is_delete = 0;

   if(empty($back_url))
   {
    $back_url = $this->ADMIN_ROUTE_NAME.'/contents';
}

if(is_numeric($id) && $id > 0)
{
        //echo $id;
    $is_delete = Content::where('id', $id)->update(['is_delete'=> '1']);
}

     //die;

if(!empty($is_delete))
{
    return back()->with('alert-success', 'Content Deleted Successfully');
}else{

    return back()->with('alert-danger', 'something went wrong, please try again...');
}

}


}