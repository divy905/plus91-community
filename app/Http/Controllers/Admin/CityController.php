<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\State;
use App\Models\City;

use Validator;
use Storage;

use App\Helpers\CustomHelper;

use Image;
use DB;

class CityController extends Controller{


    private $limit;
    private $ADMIN_ROUTE_NAME;

    public function __construct(){
        $this->limit = 100;
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request){
        $data = [];
        $search = $request->search??'';

        $cities = City::where('is_delete',0)->latest();
        if(!empty($search)){
            $cities->where('name', 'like', '%' . $search . '%');
        }

        $cities = $cities->paginate(10);

        $data['cities'] = $cities;

        return view('admin.cities.index', $data);
    }

    public function add(Request $request)
    {
        $details = [];    
        $id = isset($request->id) ? $request->id : 0;
        $cities = '';
        if(is_numeric($id) && $id > 0)
        {
         $cities = City::find($id);
         if(empty($cities))
         {
            return redirect($this->ADMIN_ROUTE_NAME.'/cities');
        }
    } 
    if($request->method() == "POST" || $request->method() == "post")
    {
        if(empty($back_url))
        {
         $back_url = $this->ADMIN_ROUTE_NAME.'/cities';
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
  $alert_msg = "City Created Successfully";
  if(is_numeric($id) & $id > 0)
  {
    $alert_msg = "City Updated Successfully";
} 
return redirect(url($back_url))->with('alert-success',$alert_msg);
}else{
    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}
}
$page_Heading = "Add City";
if(isset($cities->id))
{
    $city_name = $cities->name;
    $page_Heading = 'Update -'.$city_name;
}
$countries = Country::select('id','name')->where('status',1)->get();
$states = [];       

if(is_numeric($id) && $id > 0){
    $states = State::where('country_id',$cities->country_id)->get();        

}

$details['page_Heading'] = $page_Heading;
$details['id'] = $id;
$details['countries'] = $countries;
$details['states'] = $states;
$details['cities'] = $cities;

return view('admin.cities.form',$details);
}

public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url','icon']);
    $cities = new City;
    $files = $request->file('icon');
    if(is_numeric($id) && $id > 0)
    {
        $exist = City::find($id);

        if(isset($exist->id) && $exist->id == $id)
        {   
            $cities = $exist;
        }
    }
    foreach($details as $key => $val)
    {
        $cities->$key = $val;
    }
    if(!empty($files)){
        $icon = $this->saveImages($files,$ext='jpg,jpeg,png,gif');
        $cities->icon = $icon;
    }

    $isSaved = $cities->save();
    
    return $isSaved;
}

public function get_state(Request $request)
{
    $country_id = isset($request->country_id) ? $request->country_id : '';
    $html='';
    $state = [];
    if(!empty($country_id))
    {
        $state = State::where('country_id',$country_id)->get();
        if(!empty($state))
        {
            foreach($state as $st)
            {
                    // return  $state;
                $html.='<option value='.$st->id.'>'.$st->name.'</option>';

            }
        }

    }
    echo $html;
}

public function get_city(Request $request)
{
    $states = isset($request->state_id) ? $request->state_id : '';
    $html ='<option value="" selected disabled>Select City Name</option>';

    $cities = [];
    if(!empty($states))
    {
        $city = City::where('state_id',$states)->get();
        if(!empty($city))
        {
            foreach($city as $ct)
            {
                // echo $ct->name."zxczxczxczxc";
                $html.='<option value='.$ct->id.'>'.$ct->name.'</option>';

            }
        }

    }
    echo $html;
}


public function saveImages($files, $ext='jpg,jpeg,png,gif'){

    $filename = '';

    $path = 'cities/';
    $thumb_path = 'cities/thumb/';

    $IMG_WIDTH = 1600;
    $IMG_HEIGHT = 640;
    $THUMB_WIDTH = 400;
    $THUMB_HEIGHT = 400;

    $images_data = [];

    $upload_result = CustomHelper::UploadImage($files, $path, $ext, $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);

    if($upload_result['success']){

        $filename = $upload_result['file_name'];
    }


    return $filename;

}


public function delete(Request $request)
{
   $id = isset($request->id) ? $request->id : 0;   

   $is_delete = 0;
   if(empty($back_url))
   {
    $back_url = $this->ADMIN_ROUTE_NAME.'/cities';
}

if(is_numeric($id) && $id > 0)
{ 
    $is_delete = DB::table('cities')->where('id', $id)->update(['is_delete'=> '1']);
} 

if(!empty($is_delete))
{
    return back()->with('alert-success', 'City Deleted Successfully');
}else{
    return back()->with('alert-danger', 'something went wrong, please try again...');
}    
}





/* end of controller */
}