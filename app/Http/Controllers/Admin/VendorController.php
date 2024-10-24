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
use App\Models\Business;
use Storage;
use DB;
use Illuminate\Support\Carbon;



class VendorController extends Controller
{


    private $ADMIN_ROUTE_NAME;

    public function __construct()
    {

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }



    public function index(Request $request)
    {
        $data = [];
        $search = $request->search ?? '';
        $category_id = $request->category_id ?? '';
        $type = $request->type ?? '';
        $start_date = $request->start_date ?? '';
        $end_date = $request->end_date ?? '';
        $city_id = $request->city_id ?? '';
        $businesses = Business::where('is_delete', 0)->whereIn('business_type', ['service', 'services', 'shop', 'shops'])->orderBy('id', 'desc');
        if (!empty($search)) {
            $businesses->where('business_name', 'like', '%' . $search . '%');
        }
        if (!empty($category_id)) {
            $businesses->whereRaw('FIND_IN_SET(?, category_ids)', [$category_id]);
        }
        if (!empty($type)) {
            $businesses->where('business_type', $type);
        }
        if (!empty($city_id)) {
            $businesses->where('city_id', $city_id);
        }
        if (!empty($start_date)) {
            $businesses->whereDate('created_at', '>=', $start_date);
        }
        if (!empty($end_date)) {
            $businesses->whereDate('created_at', '<=', $end_date);
        }
        $businesses = $businesses->paginate(50);
        $data['businesses'] = $businesses;

        return view('admin.businesses.index', $data);
    }



    public function add(Request $request)
    {
        // print_r($request->all());die();
        $data = [];
        $data['categories'] = DB::table('service_category')->get();
        $data['sub_categories'] = DB::table('service_sub_category')->get();
        $data['service_area'] = DB::table('locality')->where('is_delete',0)->where('status',1)->get();
        $data['page_heading'] = 'Manage Vendor';
        return view('admin.vendor.form', $data);
    }

    public function saveVendor(Request $request)
    {
        $bus_id = $request->bus_id;
        $phone =  $request->phone;

        $checkNewBus = DB::table('new_business')->where('phone',$phone)->where('bus_id',$bus_id)->first();
        if(!empty($checkNewBus)){
            return redirect()->back()->with('alert-danger','This vendor is already present in new list');
        }
        
        $reqData = $request->except(['_token', 'back_url','serviceName','servicePrice','aadharFront','aadharBack','workImages','service_area']);

        // $request->validate([
        //     'serviceName.*' => 'required',
        //     'servicePrice.*' => 'required',
        //     'vendorExp' => 'required',
        //     'kyc' => 'required',
        //     'aadhar' => 'required',
        //     'business_name' => 'required',
        //     'phone' => 'required',
        // ]);

        $path = $request->file('bus_image')->storePublicly('public/images');
        print_r($path);die;
        $serviceName = $request->serviceName;
        $servicePrice = $request->servicePrice;

        foreach ($servicePrice as $key => $price) {
            $data['business_id'] = $request->bus_id;
            $data['user_id'] = $request->user_id;
            $data['price'] = $price;
            $data['service_name'] = $serviceName[$key];
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            $id = DB::table('service_pricing')->insertGetId($data);
        }
        $data2['vendorExp'] = $request->vendorExp;
        $data2['paidLeads'] = $request->paid_leads;
        $data2['aadhar'] = $request->aadhar;
        if ($request->aadharFront) {
            $fileName1 = '1' . time() . '.' . $request->aadharFront->extension();
            $request->aadharFront->move(public_path('uploads'), $fileName1);
            $data2['aadharFront'] = 'public/uploads/' . $fileName1;
            $reqData['aadhar_front'] = 'public/uploads/' . $fileName1;
        }
        if ($request->aadharBack) {
            $fileName2 = '2' . time() . '.' . $request->aadharBack->extension();
            $request->aadharBack->move(public_path('uploads'), $fileName2);
            $data2['aadharBack'] = 'public/uploads/' . $fileName2;
            $reqData['aadhar_back'] = 'public/uploads/' . $fileName2;
        }

        $update =     DB::table('businesses')->where('id',$request->bus_id)->update($data2);
        $workImagesArray=array();
        $img = $request->workImages;
        foreach($img as $key => $workImage){
            $wrkImg = $key . time() . '.' .$workImage->getClientOriginalName();
            $workImage->move(public_path('uploads/work_images/'), $wrkImg);
            $workImagesArray[] = 'public/uploads/work_images/' . $wrkImg;
        }


        $reqData['work_images'] = implode(',',$workImagesArray);
        $reqData['service_area'] = implode(',',$request->service_area);

        // echo '<pre>';
        // print_r($reqData);die;

        $id = DB::table('new_business')->insertGetId($reqData);
        print_r($id);

        return redirect()->back()->with('alert-success','Vendor submitted successfully');


        
    }






    public function save(Request $request, $id = 0)
    {
        // prd($request->toArray());

        $data = $request->except(['_token', 'back_url', 'image', 'password', 'image_name', 'galleryIds', 'titleArr', 'alt_tag']);

        if ($id == 0) {
            $slug = CustomHelper::GetSlug('businesses', 'id', '', $request->title);
            $data['slug'] = $slug;
        }
        $oldImg = '';

        $businesses = new Business;

        if (!empty($id)) {
            $exist = Business::where('id', $id)->first();

            if (isset($exist->id) && $exist->id == $id) {
                $businesses = $exist;

                $oldImg = $exist->image;
            }
        }
        //prd($oldImg);

        foreach ($data as $key => $val) {
            $businesses->$key = $val;
        }

        $isSaved = $businesses->save();

        if ($isSaved) {
            $this->saveImage($request, $businesses, $oldImg);

            $galleryIds = $request->galleryIds ?? '';
            $titleArr = $request->titleArr ?? '';
            $alt_tag = $request->alt_tag ?? '';
            if (!empty($galleryIds)) {
                foreach ($galleryIds as $key => $value) {
                    $dbArray = [];
                    $dbArray['title'] = $titleArr[$key] ?? '';
                    $dbArray['alt_tag'] = $alt_tag[$key] ?? '';
                    DB::table('business_gallery')->where('id', $value)->update($dbArray);
                }
            }
        }

        return $isSaved;
    }


    private function saveImage($request, $businesses, $oldImg = '')
    {

        $file = $request->file('image');
        if ($file) {
            $path = 'business_gallery/';
            $thumb_path = 'business_gallery/thumb/';
            $storage = Storage::disk('public');
            //prd($storage);
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = '', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT, $request->image_name);

            // prd($uploaded_data);
            if ($uploaded_data['success']) {

                if (!empty($oldImg)) {
                    if ($storage->exists($path . $oldImg)) {
                        $storage->delete($path . $oldImg);
                    }
                    if ($storage->exists($thumb_path . $oldImg)) {
                        $storage->delete($thumb_path . $oldImg);
                    }
                }
                $image = $uploaded_data['file_name'];
                $businesses->image = $image;
                $businesses->save();
            }

            if (!empty($uploaded_data)) {
                return $uploaded_data;
            }
        }
    }




    public function delete(Request $request)
    {
        $id = (isset($request->id)) ? $request->id : 0;

        $is_delete = '';

        if (is_numeric($id) && $id > 0) {
            $is_delete = Blogs::where('id', $id)->update(['is_delete' => 1]);
        }

        if (!empty($is_delete)) {
            return back()->with('alert-success', 'Blogs has been deleted successfully.');
        } else {
            return back()->with('alert-danger', 'something went wrong, please try again...');
        }
    }



    public function change_blog_status(Request $request)
    {
        $id = isset($request->id) ? $request->id : '';
        $status = isset($request->status) ? $request->status : '';

        $data = Blogs::where('id', $id)->first();
        if (!empty($data)) {

            Blogs::where('id', $id)->update(['status' => $status]);
            $response['success'] = true;
            $response['message'] = 'Status updated';


            return response()->json($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Not  Found';
            return response()->json($response);
        }
    }


    private function saveImageMultiple($request, $society_id)
    {

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

            foreach ($files as $file) {
                $uploaded_data = CustomHelper::UploadFile($file, $path, $ext = '');
                if ($uploaded_data['success']) {
                    $image = $uploaded_data['file_name'];
                    $dbArray['file'] = $image;
                    $dbArray['society_id'] = $society_id;

                    $success = SocietyDocument::create($dbArray);
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function assign_types(Request $request)
    {
        $method = $request->method();
        if ($method == 'post' || $method == 'POST') {
            $rules = [];
            $rules['course_id'] = 'required';
            $rules['type_ids'] = 'required';
            $this->validate($request, $rules);

            $type_ids = $request->type_ids ?? '';
            if (!empty($type_ids)) {
                CourseType::where('course_id', $request->course_id)->delete();
                foreach ($type_ids as $type_id => $value) {
                    $dbArray = [];
                    $dbArray['course_id'] = $request->course_id ?? '';
                    $dbArray['type_id'] = $value;
                    CourseType::insert($dbArray);
                }
            }
            return back()->with('alert-success', 'Course Type Assign successfully.');
        }
    }

    public function fetchBusiness(Request $request)
    {
        $phoneNo = $request->phoneNo;
        $data['businessData'] = DB::table('businesses')->select('businesses.*','states.name as stateName','cities.name as cityName')->join('users', 'businesses.parent', '=', 'users.id')->join('states', 'businesses.state_id', '=', 'states.id')->join('cities', 'businesses.city_id', '=', 'cities.id')->where('mobile', $phoneNo)->first();
        $categories = explode(',', $data['businessData']->category_ids);
        $allCategories = DB::table('all_categories')->select('id', 'name')->whereIn('id', $categories)->get();

        $fetchSubCat = DB::table('sub_categories')->select('id', 'name')->whereIn('category_id', $categories)->get();
        $option1 = '';
        $option2 = '';
        $subCatOption1 = '';
        $subCatOption2 = '';
        foreach ($allCategories as $key => $cat) {
            if ($key == 0) {
                $option1 = $cat->name;
            }
            if ($key == 1) {
                $option2 = $cat->name;
            }
        }
        if (count($fetchSubCat) > 0) {
            foreach ($fetchSubCat as $key => $val) {
                if ($key == 0) {
                    $subCatOption1 = $cat->name;
                }
                if ($key == 1) {
                    $subCatOption2 = $cat->name;
                }
            }
        }
        $data['option1'] = $option1;
        $data['option2'] = $option2;
        $data['subCatOption1'] = $subCatOption1;
        $data['subCatOption2'] = $subCatOption2;
        return response()->json(($data));
    }


    public function sendAadharOtp(Request $request)
    {
        $aadhaar_number = $request->aadhaar_number;
        $user_id = $request->user_id;
        $postFields = '{"aadhaar_number": "' . $aadhaar_number . '","user_id": "' . $user_id . '"}';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost/numberdekho-api/api/sendAadharOtp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: laravel_session=JhI7kntQG4dvqcRnIFAERPzAa1ONqyabaZzgdDJB'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response, true);
        return response()->json($res);
    }


    public function verifyAadharOTP(Request $request)
    {

        $postFields ='{ "ref_id": "'.$request->ref_id.'", "user_id": "'.$request->user_id.'", "otp": "'.$request->otp.'" }';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost/numberdekho-api/api/verifyAadharOtp',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: laravel_session=JhI7kntQG4dvqcRnIFAERPzAa1ONqyabaZzgdDJB'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res = json_decode($response, true);
        return response()->json($res);
    }
}
