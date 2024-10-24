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
use App\Models\RootUpload;
use Storage;
use DB;
use Hash;



Class RootUploadController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
     $data =[];
    
     $method = $request->method();
     if($method == 'post' || $method == 'POST'){
        $dbArray = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = dirname(__DIR__, 6) . "/numberde/public_html/";
            $file_name = $this->upload_image($file, $path);
            $dbArray['name'] = $file_name;
            $exists = RootUpload::where('name',$file_name)->first();
            if(empty($exists)){
                RootUpload::insert($dbArray);
            }else{
                RootUpload::where('id',$exists->id)->update($dbArray);
            }
        }
     }


     $files = RootUpload::orderBy('id','desc');

     $files = $files->paginate(10);
     $data['files'] = $files;

     return view('admin.upload_on_root.index',$data);
 }


    public function upload_image($file, $path)
    {
        $side_name = $file->getClientOriginalName();
        $file->move($path, $side_name);
        return $side_name;
    }


}