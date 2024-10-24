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
use App\Models\Permission;

use Storage;
use DB;
use Hash;



Class PermissionController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();


	}



	public function index(Request $request){
        $data = [];
        $roles = Role::where('status',1)->get();
        $data['roles'] = $roles;

        $role_id = isset($request->role_id) ? $request->role_id :'';
         $data['singlerole'] = [];
        if(!empty($role_id)){
            $sectionArr = config('modules.allowedwithval');
            $data['sectionArr'] = $sectionArr;
            $roles = Role::where('status',1)->get();
            $data['roles'] = $roles;
            $data['role_id'] = $role_id;
            $data['singlerole'] = Role::where('id',$role_id)->first();
        }


        return view('admin.permission.index',$data);
    }


    public function update_permission(Request $request){
    $key = isset($request->key) ? $request->key :'';
    $section = isset($request->section) ? $request->section :'';
    $permission = isset($request->permission) ? $request->permission :'';
    $role_id = isset($request->role_id) ? $request->role_id :'';
    $dbArray = [];
    $exist = Permission::where(['role_id'=>$role_id,'section'=>$key])->first();
    if(!empty($exist)){
        $dbArray[$section] = $permission;
        Permission::where('id',$exist->id)->update($dbArray);
    }else{
        $dbArray['role_id'] = $role_id;
        $dbArray['section'] = $key;
        $dbArray[$section] = $permission;
        Permission::insert($dbArray);
    }


}




}