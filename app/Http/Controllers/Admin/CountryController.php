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
use App\Models\State;
use App\Models\Country;
use App\Models\Role;
use App\Models\City;
use App\Models\User;
use App\Roles;
use App\Models\ExamCategory;
use Storage;
use DB;
use Hash;

class CountryController extends Controller{

    private $limit;
    private $ADMIN_ROUTE_NAME;

    public function __construct(){
        $this->limit = 100;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request){

        $data = [];
        $countries = Country::where('is_delete',0)->orderBy('name', 'asc')->paginate(10);       
        $data['countries'] = $countries;       
        return view('admin.countries.index', $data);

    }

    public function add(Request $request)
    {
        $details = [];    
        $id = isset($request->id) ? $request->id : 0;
        $countries = '';
        if(is_numeric($id) && $id > 0)
        {
             $countries = Country::find($id);
            if(empty($countries))
            {
                return redirect($this->ADMIN_ROUTE_NAME.'/countries');
            }
        } 
        if($request->method() == "POST" || $request->method() == "post")
        {
            if(empty($back_url))
            {
                 $back_url = $this->ADMIN_ROUTE_NAME.'/countries';
            }
            if(is_numeric($request->id) && $request->id > 0)
            {
              $details['name'] = 'required';           

            }else{

                 $details['name'] = 'required';                              
            }
                $this->validate($request , $details); 
           $createdDetails = $this->save($request , $id);
           if($createdDetails)
           {
              $alert_msg = "Country Created Successfully";
                if(is_numeric($id) & $id > 0)
                {
                    $alert_msg = "Country Updated Successfully";
                } 
                return redirect(url($back_url))->with('alert-success',$alert_msg);
           }else{
            return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
           }
        }
        $page_Heading = "Add Country";
        if(isset($countries->id))
        {
            $country_name = $countries->name;
            $page_Heading = 'Update -'.$country_name;
        }
        $details['page_Heading'] = $page_Heading;
        $details['id'] = $id;
        $details['countries'] = $countries;
        return view('admin.countries.form',$details);
    }

     public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);
        $countries = new Country;

        if(is_numeric($id) && $id > 0)
        {
            $exist = Country::find($id);

            if(isset($exist->id) && $exist->id == $id)
            {   
                $countries = $exist;
            }
        }
        foreach($details as $key => $val)
        {
            $countries->$key = $val;
        }
        $isSaved = $countries->save();
        return $isSaved;
    }

    public function delete(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;    

         $is_delete = 0;
         if(empty($back_url))
        {
            $back_url = $this->ADMIN_ROUTE_NAME.'/countries';
        }
         if(is_numeric($id) && $id > 0)
         {          

             $is_delete = Country::where('id', $id)->update(['is_delete'=> '1']);

         }       
         if(!empty($is_delete))
         {
            return back()->with('alert-success', 'Country Deleted Successfully');
         }else{
            return back()->with('alert-danger', 'something went wrong, please try again...');
         }    
    }





    // public function saveImages($files, $ext='jpg,jpeg,png,gif'){

    //     $filename = '';

    //         $path = 'countries/';
    //         $thumb_path = 'countries/thumb/';

    //         $IMG_WIDTH = 1600;
    //         $IMG_HEIGHT = 640;
    //         $THUMB_WIDTH = 400;
    //         $THUMB_HEIGHT = 400;

    //         $images_data = [];

    //         $upload_result = CustomHelper::UploadImage($files, $path, $ext, $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

    //             if($upload_result['success']){
                   
    //                 $filename = $upload_result['file_name'];
    //             }

           
    //     return $filename;

    // }


/* end of controller */
}