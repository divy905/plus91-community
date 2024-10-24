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
use App\Models\Category;
use App\Models\Locality;
use App\Models\Country;
use App\Models\City;
use App\Models\State;
use Storage;
use DB;
use Hash;



Class LocalityController extends Controller
{


	private $ADMIN_ROUTE_NAME;

	public function __construct(){

		$this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

	}



	public function index(Request $request){
       $data =[];
       $locality = Locality::where('is_delete',0)->latest();
       $search = $request->search??'';
       $type = $request->type??'';
       if(!empty($search)){
        $locality->where('locality','like','%'.$search.'%');
        $locality->orWhere('slug','like','%'.$search.'%');
       }
       if(!empty($type)){
        $locality->where('type',$type);
       }
       $locality = $locality->paginate(50);
       $data['locality'] = $locality;

       return view('admin.locality.index',$data);
   }


 public function add(Request $request)
    {
        $details = [];    
        $id = isset($request->id) ? $request->id : 0;
        $locality = '';
        if(is_numeric($id) && $id > 0)
        {
         $locality = Locality::find($id);
         if(empty($locality))
         {
            return redirect($this->ADMIN_ROUTE_NAME.'/locality');
        }
    } 
    if($request->method() == "POST" || $request->method() == "post")
    {
        if(empty($back_url))
        {
         $back_url = $this->ADMIN_ROUTE_NAME.'/locality';
     }
     if(is_numeric($request->id) && $request->id > 0)
     {
      $details['locality'] = 'required';           

  }else{

     $details['locality'] = 'required';                              
 }
 $this->validate($request , $details); 
 $createdDetails = $this->save($request , $id);
 if($createdDetails)
 {
  $alert_msg = "Locality Created Successfully";
  if(is_numeric($id) & $id > 0)
  {
    $alert_msg = "Locality Updated Successfully";
} 
return redirect(url($back_url))->with('alert-success',$alert_msg);
}else{
    return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
}
}
$page_heading = "Add Locality";
if(isset($locality->id))
{
    $city_name = $locality->locality;
    $page_heading = 'Update -'.$city_name;
}
$countries = Country::select('id','name')->where('status',1)->get();
$states = [];       
$cities = [];       

if(is_numeric($id) && $id > 0){
    $states = State::where('country_id',$locality->country_id)->get();        
    $cities = City::where('state_id',$locality->state_id)->get();        

}

$details['page_heading'] = $page_heading;
$details['id'] = $id;
$details['countries'] = $countries;
$details['locality'] = $locality;
$details['states'] = $states;
$details['cities'] = $cities;

return view('admin.locality.form',$details);
}

public function save(Request $request, $id = 0)
{
    $details = $request->except(['_token', 'back_url','icon']);
    $cities = new Locality;
    $files = $request->file('icon');


    if($id == 0){
        $slug = CustomHelper::GetSlug('locality', 'id', '', $request->locality);
        $details['slug'] = $slug;
    }

    if(is_numeric($id) && $id > 0)
    {
        $exist = Locality::find($id);

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
        // $icon = $this->saveImages($files,$ext='jpg,jpeg,png,gif');
        // $cities->icon = $icon;
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

// public function get_city(Request $request)
// {
//     $states = isset($request->state_id) ? $request->state_id : '';
//     $html='';

//     $cities = [];
//     if(!empty($states))
//     {
//         $city = City::where('state_id',$states)->first();
//         if(!empty($city))
//         {
//             foreach($city as $ct)
//             {
//                 $html.='<option value='.$ct->id.'>'.$ct->name.'</option';

//             }
//         }

//     }
//     echo $html;
// }


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
    $back_url = $this->ADMIN_ROUTE_NAME.'/locality';
}

if(is_numeric($id) && $id > 0)
{ 
    $is_delete = DB::table('locality')->where('id', $id)->update(['is_delete'=> '1']);
} 

if(!empty($is_delete))
{
    return back()->with('alert-success', 'Locality Deleted Successfully');
}else{
    return back()->with('alert-danger', 'something went wrong, please try again...');
}    
}






public function export(Request $request){
    $exportArr = [];
    $localities = Locality::where('is_delete',0)->get();
    if(!empty($localities)){
        foreach($localities as $cat){
            $state = State::where('id',$cat->state_id)->first();
            $city = City::where('id',$cat->city_id)->first();
            $catArr = [];
            $catArr['ID'] = $cat->id ?? '';
            $catArr['State'] = $state->name ?? '';
            $catArr['City'] = $city->name ?? '';
            $catArr['Locality'] = $cat->locality ?? '';
            $catArr['Time'] = date('H:i A',strtotime($cat->created_at)) ?? '';
            $exportArr[] = $catArr;
        }
    }

    if(!empty($exportArr)){
        $fileNames = array_keys($exportArr[0]);
        $fileName = 'locality_'.date('Y-m-d-H-i-s').'.xlsx';
        return Excel::download(new BusinessExport($exportArr, $fileNames), $fileName);
    }
}


public function import(Request $request){

 $method = $request->method();
 if($method == 'post' || $method == 'POST'){
    $rules = [];
    $rules['importfile'] = 'required';
    $message = ['importfile.required' => 'You have to choose the file!',];
    $this->validate($request,$rules,$message);

    $sucess = Excel::import(new LocalityImport,request()->file('importfile'));


    return back()->with('alert-success', 'Locality Imported Successfully');

}
}




}