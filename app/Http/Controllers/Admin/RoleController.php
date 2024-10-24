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

use App\Models\Role;

use Storage;
use DB;
use Hash;



Class RoleController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


	}



	public function index(Request $request){
		$roles = Role::paginate(10);
		$data['roles'] = $roles;
		return view('admin.roles.index',$data);
	}

	

public function add(Request $request){
    $data = [];

    $id = (isset($request->id))?$request->id:0;

    $roles = '';
    if(is_numeric($id) && $id > 0){
        $roles = Role::find($id);
        if(empty($roles)){
            return redirect($this->ADMIN_ROUTE_NAME.'/roles');
        }
    }

    if($request->method() == 'POST' || $request->method() == 'post'){

        if(empty($back_url)){
            $back_url = $this->ADMIN_ROUTE_NAME.'/roles';
        }

        $name = (isset($request->name))?$request->name:'';


        $rules = [];

        $rules['name'] = 'required';
        
        $this->validate($request, $rules);

        $createdCat = $this->save($request, $id);

        if ($createdCat) {
            $alert_msg = 'Roles has been added successfully.';
            if(is_numeric($id) && $id > 0){
                $alert_msg = 'Roles has been updated successfully.';
            }
            return redirect(url($back_url))->with('alert-success', $alert_msg);
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
        }
    }


    $page_heading = 'Add Role';

    if(isset($roles->title)){
        $roles_name = $roles->title;
        $page_heading = 'Update Roles - '.$roles_name;
    }  

    $data['page_heading'] = $page_heading;
    $data['id'] = $id;
    $data['roles'] = $roles;

    return view('admin.roles.form', $data);

}






public function save(Request $request, $id=0){

    $data = $request->except(['_token', 'back_url', 'image']);

        //prd($request->toArray());

    $oldImg = '';

    $roles = new Role;

    if(is_numeric($id) && $id > 0){
        $exist = Role::find($id);

        if(isset($exist->id) && $exist->id == $id){
            $roles = $exist;

            $oldImg = $exist->image;
        }
    }
        //prd($oldImg);

    foreach($data as $key=>$val){
        $roles->$key = $val;
    }

    $isSaved = $roles->save();

    if($isSaved){
        $this->saveImage($request, $roles, $oldImg);
    }

    return $isSaved;
}


private function saveImage($request, $blockes, $oldImg=''){

    $file = $request->file('image');
    if ($file) {
        $path = 'blockes/';
        $thumb_path = 'blockes/thumb/';
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
            $blockes->image = $image;
            $blockes->save();         
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
        $is_delete = Role::where('id', $id)->delete();
    }

    if(!empty($is_delete)){
        return back()->with('alert-success', 'Roles has been deleted successfully.');
    }
    else{
        return back()->with('alert-danger', 'something went wrong, please try again...');
    }
}



	public function change_role_status(Request $request){
		$role_id = isset($request->role_id) ? $request->role_id :'';
		$status = isset($request->status) ? $request->status :'';

		$roles = Role::where('id',$role_id)->first();
		if(!empty($roles)){

			Role::where('id',$role_id)->update(['status'=>$status]);
			$response['success'] = true;
			$response['message'] = 'Status updated';


			return response()->json($response);
		}else{
			$response['success'] = false;
			$response['message'] = 'No Roles FOund';
			return response()->json($response);
		}


	}


}