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
use App\Models\User;
use App\Models\Admin;
use App\Models\Locality;
use Image;
use Illuminate\Support\Str;


use App\Models\Category;
use App\Models\City;
use App\Models\SubCategory;
use App\Models\Page;
use App\Models\Blogs;
use App\Models\SubscriptionHistory;

use App\Models\Course;
use App\Models\Business;
use App\Models\Permission;
use App\Models\BusinessCategory;
use App\Models\Role;
use App\Models\State;

use Storage;
use DB;
use Hash;
use FFMpeg;
use FFMpeg\Filters\Video\VideoFilters;
use ProtoneMedia\LaravelFFMpeg\Exporters\HLSExporter;

use PhpOffice\PhpWord\IOFactory;
use Session;




Class HomeController extends Controller
{

	public function index(Request $request){
		$data = [];
        // $data['users'] = User::select('id')->count();
        // $data['business'] = Business::select('id')->where('is_delete', 0)->count();
        // $data['ratings'] = DB::table('ratings')->select('id')->count();
        $data['category'] = Category::select('id')->where('is_delete', 0)->count();
        // $data['blogs'] = Blogs::select('id')->where('is_delete', 0)->count();
        // $data['pages'] = Page::select('id')->where('is_delete', 0)->count();

        $data['users'] = 0;
        $data['business'] = 0;
        $data['ratings'] = 0;
        $data['blogs'] = 0;
        $data['pages'] = 0;
        

        return view('admin.home.index',$data);
    }






    public function get_attraction_from_googleapi($next_page_token='',$city_name='bengaluru'){
        $city_name = 'kolkata';
        // $next_page_token = '';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$city_name.'+point+of+interest&language=en&key=AIzaSyAifUHhaw-NrwG8UNMSuzC1sNTftvyMOio&pagetoken='.$next_page_token.' ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);
        if(!empty($response)){
            if($response->status == 'OK'){
               //  print_r($response);
                $results = $response->results??'';
                if(!empty($results)){
                    foreach ($results as $key) {
                        $image = $this->upload_image_from_url($key->photos[0]->photo_reference??'');
                        $dbArray = [];
                        $dbArray['city_name'] = $city_name;
                        $dbArray['address'] = $key->formatted_address??'';
                        $dbArray['lat'] = $key->geometry->location->lat??'';
                        $dbArray['lng'] =$key->geometry->location->lng??'';
                        $dbArray['name'] = $key->name??'';
                        $dbArray['image'] = $image??'';
                        $dbArray['rating'] = $key->rating??'';

                        $exist = DB::table('attractions')->where('name',$key->name)->where('city_name',$city_name)->first();
                        if(empty($exist)){
                            DB::table('attractions')->insert($dbArray);
                        }else{
                            DB::table('attractions')->where('id',$exist->id)->update($dbArray);
                        }
                    }
                }
            }

            $next_page_token = $response->next_page_token??'';
            if(!empty($next_page_token)){
                self::get_attraction_from_googleapi($next_page_token);
            }
        }


    }


    public function upload_image_from_url($reference){
     $query = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=800&photoreference=".$reference."&key=".env('MAP_API_KEY')."";
     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $query);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     $a = curl_exec($ch);
     $url = Null;
     if(preg_match('#Location: (.*)#', $a, $r)) {
       $url = trim($r[1]);
   }
   $success = $this->checkRemoteFile($url);
   if($success){
    $filename = "attraction_".time().basename($url);
    Image::make($url)->save(public_path('uploads/attractions/' . $filename));
    return $filename;
}else{
  return ''; 
}
}



public function checkRemoteFile($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);

    // echo $url;
    // die;
    if($result !== FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}








public function profile(Request $request)
{
        // echo $request->method();

  $data = [];
  $method = $request->method();
  $user = Auth::guard('admin')->user();

  if($method == 'post' || $method == 'POST')
  {

       // prd($request->toArray());


   $request->validate([
    'email' => 'required',        
    'phone' => 'required',
    'username' => 'required',

]);

   $name = isset($request->name) ? $request->name : '';
   $email = isset($request->email) ? $request->email : '';      
   $phone = isset($request->phone) ? $request->phone : '';
   $username = isset($request->username) ? $request->username : '';
   $education = isset($request->education) ? $request->education : '';
   $total_exp = isset($request->total_exp) ? $request->total_exp : '';
   $speciality = isset($request->speciality) ? $request->speciality : '';
   $about = isset($request->about) ? $request->about : '';
   $image = isset($request->image) ? $request->image : '';

   if(!empty($request->name)){
       $dbArray['name'] = $request->name; 
   }
   if(!empty($request->email)){
       $dbArray['email'] = $request->email; 
   }
   if(!empty($request->phone)){
       $dbArray['phone'] = $request->phone; 
   }
   if(!empty($request->username)){
       $dbArray['username'] = $request->username; 
   }
   if(!empty($request->address)){
       $dbArray['address'] = $request->address; 
   }
   // if(!empty($request->education)){
   //     $dbArray['education'] = $request->education; 
   // }
   // if(!empty($request->total_exp)){
   //     $dbArray['total_exp'] = $request->total_exp; 
   // }
   // if(!empty($request->speciality)){
   //     $dbArray['speciality'] = $request->speciality; 
   // }
   // if(!empty($request->about)){
   //     $dbArray['about'] = $request->about; 
   // }
   $result = Admin::where('id',$user->id)->update($dbArray);
   if($result){

       if($request->hasFile('image')) {
        $file = $request->file('image');
        $image_result = $this->saveImage($file,$user->id);
        if($image_result['success'] == false){     
            session()->flash('alert-danger', 'Image could not be added');
        }
    }


    return back()->with('alert-success','Profile Updated Successfully');
}else{
    return back()->with('alert-danger','Something Went Wrong');

}
}

$data['user'] = $user;
$data['page_heading'] = 'Update Profile';
return view('admin.profile.index',$data);
}


public function get_sub_cat(Request $request){
  $cat_id = isset($request->cat_id) ? $request->cat_id : '';
  $html = '<option value="" selected disabled>Select Sub Category</option>';
  if(!empty($cat_id)){
    $subcategories = SubCategory::where('cat_id',$cat_id)->get();
    if(!empty($subcategories)){
        foreach($subcategories as $sub_cat){
            $html.='<option value='.$sub_cat->id.' >'.$sub_cat->name.'</option>';
        }
    }
}


echo $html;

}



public function permission(Request $request){
    $data = [];
    $method = $request->method();

    $role_id = isset($request->role_id) ? $request->role_id :'';
    if(!empty($role_id)){
       // $user = Auth::guard('admin')->user();
     //    if($user->role_id !=0){
     //     return redirect('admin');
     // }
     if($method == 'post' || $method == 'POST'){

     }
     $sectionArr = config('modules.allowedwithval');
     $data['sectionArr'] = $sectionArr;
     $roles = Role::get();
     $data['roles'] = $roles;
     $data['role_id'] = $role_id;
     $data['singlerole'] = Role::where('id',$role_id)->first();

     return view('admin.profile.permission',$data);
 }else{

     $roles = Role::get();
     $data['roles'] = $roles;
     $data['role_id'] = $role_id;

     return view('admin.profile.permission',$data);

 }






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






private function saveImage($file, $id){
        // prd($file); 
        //echo $type; die;

    // $result['org_name'] = '';
    // $result['file_name'] = '';

    if ($file) 
    {
        $path = 'user/';
        $thumb_path = 'user/thumb/';
        $IMG_WIDTH = 768;
        $IMG_HEIGHT = 768;
        $THUMB_WIDTH = 336;
        $THUMB_HEIGHT = 336;

        $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
        if($uploaded_data['success']){
            $new_image = $uploaded_data['file_name'];

           // prd($uploaded_data['file_name']);

            if(is_numeric($id) && $id > 0){
                $user = Admin::where('id',$id)->first();
                if(!empty($user)){
                    $storage = Storage::disk('public');
                    $old_image = $user->image;
                    $isUpdated = Admin::where('id',$id)->update(['image'=>$new_image]);
                    if($isUpdated){
                        if(!empty($old_image) && $storage->exists($path.$old_image)){
                            $storage->delete($path.$old_image);
                        }

                        if(!empty($old_image) && $storage->exists($thumb_path.$old_image)){
                            $storage->delete($thumb_path.$old_image);
                        }
                    }
                }


            }
        }

        if(!empty($uploaded_data))
        {   
            return $uploaded_data;
        }
    }
}


public function setting(Request $request){
    $data =[];  

    $method = $request->method();

    if($method == 'POST' || $method =="post"){

        $dbArray = [];

        $dbArray['refer_earn_amount'] = isset($request->refer_earn_amount) ? $request->refer_earn_amount:'';
        $dbArray['about_us'] = isset($request->about_us) ? $request->about_us:'';
        $dbArray['privacypolicy'] = isset($request->privacypolicy) ? $request->privacypolicy:'';
        $dbArray['contact_email'] = isset($request->contact_email) ? $request->contact_email:'';
        $dbArray['contact_phone'] = isset($request->contact_phone) ? $request->contact_phone:'';
        $dbArray['contactus'] = isset($request->contactus) ? $request->contactus:'';
        $dbArray['terms'] = isset($request->terms) ? $request->terms:'';
        $dbArray['app_name'] = isset($request->app_name) ? $request->app_name:'';
        $dbArray['splash1_text'] = isset($request->splash1_text) ? $request->splash1_text:'';
        $dbArray['splash2_text'] = isset($request->splash2_text) ? $request->splash2_text:'';
        $dbArray['splash3_text'] = isset($request->splash3_text) ? $request->splash3_text:'';
        $dbArray['fb_url'] = isset($request->fb_url) ? $request->fb_url:'';
        $dbArray['insta_url'] = isset($request->insta_url) ? $request->insta_url:'';
        $dbArray['youtube'] = isset($request->youtube) ? $request->youtube:'';
        $dbArray['linkedin'] = isset($request->linkedin) ? $request->linkedin:'';
        $dbArray['sms_sender'] = isset($request->sms_sender) ? $request->sms_sender:'';

        DB::table('settings')->where('id',1)->update($dbArray);
        $data['settings'] = DB::table('settings')->where('id',1)->first();
        return back()->with('alert-success','Updated Successfully');
    }

    $data['settings'] = DB::table('settings')->where('id',1)->first();

    return view('admin.home.settings',$data);

}















public function change_password(Request $request){
    //prd($request->toArray());
    $data = [];
    $password = isset($request->password) ?  $request->password:'';
    $new_password = isset($request->new_password) ?  $request->new_password:'';
    $method = $request->method();

        //prd($method);
    $auth_user = Auth::guard('admin')->user();
    $admin_id = $auth_user->id;
    if($method == 'POST' || $method =="post"){
        $post_data = $request->all();
        $rules = [];

        $rules['old_password'] = 'required|min:6|max:20';
        $rules['new_password'] = 'required|min:6|max:20';
        $rules['confirm_password'] = 'required|min:6|max:20|same:new_password';

        $validator = Validator::make($post_data, $rules);

        if($validator->fails()){
            return back()->withErrors($validator)->withInput();
        }
        else{
                //prd($request->all());

            $old_password = $post_data['old_password'];

            $user = Admin::where(['id'=>$admin_id])->first();

            $existing_password = (isset($user->password))?$user->password:'';

            $hash_chack = Hash::check($old_password, $user->password);

            if($hash_chack){
                $update_data['password']=bcrypt(trim($post_data['new_password']));

                $is_updated = Admin::where('id', $admin_id)->update($update_data);

                $message = [];

                if($is_updated){

                    $message['alert-success'] = "Password updated successfully.";
                }
                else{
                    $message['alert-danger'] = "something went wrong, please try again later...";
                }

                return back()->with($message);


            }
            else{
                $validator = Validator::make($post_data, []);
                $validator->after(function ($validator) {
                    $validator->errors()->add('old_password', 'Invalid Password!');
                });
                    //prd($validator->errors());
                return back()->withErrors($validator)->withInput();
            }
        }
    }



}

// public function profile(Request $request){
//     $data = [];


//     return view('admin.home.profile',$data);
// }

public function upload(Request $request){
   $data = [];
   $method = $request->method();
   $user = Auth::guard('admin')->user();

   if($method == 'post' || $method == 'POST'){
       $request->validate([
        'file' => 'required',
    ]);

       if($request->hasFile('file')) {
        $file = $request->file('file');
        $image_result = $this->saveImage($file,$user->id,'file');
        if($image_result['success'] == false){     
            session()->flash('alert-danger', 'Image could not be added');
        }
    }
    return back()->with('alert-success','Profile Updated Successfully');
}
}


public function birth_day_email(Request $request){
    CustomHelper::sendBirthDayEmail();
}




public function get_city(Request $request){
    $state_id = isset($request->state_id) ? $request->state_id :0;
    $html = '<option value="" selected disabled>Select City</option>';
    if($state_id !=0){
        $cities = City::where('state_id',$state_id)->get();
        if(!empty($cities)){
            foreach($cities as $city){
                $html.='<option value='.$city->id.'>'.$city->name.'</option>';
            }
        }
    } 
    echo $html;
}

public function get_locality(Request $request){
    $city_id = isset($request->city_id) ? $request->city_id :0;
    $html = '<option value="" selected disabled>Select Locality</option>';
    if($city_id !=0){
        $localities = Locality::where('city_id',$city_id)->get();
        if(!empty($localities)){
            foreach($localities as $local){
                $slug = Str::slug($local->locality);
                $html.='<option value='.$slug.'>'.$local->locality.'</option>';
            }
        }
    } 
    echo $html;
}

public function get_state(Request $request){
    $country_id = isset($request->country_id) ? $request->country_id :0;
    $html = '<option value="" selected disabled>Select City</option>';
    if($country_id !=0){
        $cities = State::where('country_id',$country_id)->get();
        if(!empty($cities)){
            foreach($cities as $city){
                $html.='<option value='.$city->id.'>'.$city->name.'</option>';
            }
        }
    } 
    echo $html;
}



public function cmsPage(Request $request){
    $data = [];

    return view('admin.home.cmspage',$data);
}


public function get_blocks(Request $request){
   $society_id = isset($request->society_id) ? $request->society_id :0;
   $html = '<option value="0" selected="" disabled >Select Society</option>';
   if($society_id !=0){
    $blocks = Blocks::where('society_id',$society_id)->get();
    if(!empty($blocks)){
        foreach($blocks as $block){
            $html.='<option value='.$block->id.'>'.$block->name.'</option>';
        }
    }
} 
echo $html;


}


public function get_flats(Request $request){
   $block_id = isset($request->block_id) ? $request->block_id :0;
   $html = '<option value="0" selected="" disabled >Select Flats</option>';
   if($block_id !=0){
    $flats = Flats::where('block_id',$block_id)->get();
    if(!empty($flats)){
        foreach($flats as $flat){
            $html.='<option value='.$flat->id.'>'.$flat->flat_no.'</option>';
        }
    }
} 
echo $html;


}



public function upload_xls(Request $request){
    $method = $request->method();
    $data = [];
    $html= '';
    if($method =='post' || $method == 'POST'){
     $phpWord = IOFactory::createReader('Word2007')->load($request->file('file')->path());
     $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
     $objWriter->save('doc.html');
     $page = file_get_contents('https://mydoor.appmantra.live/doc.html');



     DB::table('new')->insert(['text'=>$page]);
     echo $page;
     die;

     foreach($phpWord->getSections() as $section) {
        foreach($section->getElements() as $element) {
            if(method_exists($element,'getText')) {
                $html.=$element->getText();
            }
        }
    }
}

$data['html'] = $html;

return view('admin.home.upload_file',$data);


}

// public function upload_video(Request $request){
//  // $data = [];
//     // $method = $request->method();
//     // if($method == 'post' || $method == 'POST'){
//         $video = $request->file('video')->getPathName();
//         $bitrate = $request->bitrate;
//         $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate neww.mp4";
//         system($command);

//     //     echo "File has been converted";
//     // }



// //     $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
// // $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
// // $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);

// // FFMpeg::fromDisk('videos')
// //     ->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
// //     ->exportForHLS()
// //     ->addFormat($lowBitrate)
// //     ->addFormat($midBitrate)
// //     ->addFormat($highBitrate)
// //     ->save('public/uploads/converted/adaptive_steve.m3u8');

// // $ffmpeg = FFMpeg\FFMpeg::create();
// // $video = $ffmpeg->open(public_path()."/uploads/small_steve.mp4");
// // $video
// //     ->filters()
// //     ->resize(new \FFMpeg\Coordinate\Dimension(640, 480))
// //     ->synchronize();
// // $video
// //     ->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds(10))
// //     ->save(public_path().'/uploads/converted/kaushik.jpg');
// // $format= new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'); 
// // $format->setKiloBitrate(300);
// // $video->save($format,public_path().'uploads/converted/kaushik.mp4');






// // $ffmpeg = FFMpeg\FFMpeg::create();
// // $video = $ffmpeg->open('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4');
// // $video
// //     ->filters()
// //     ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
// //     ->synchronize();
// // $video
// //     ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(5))
// //     ->save('frame.jpg');
// // $video
// //     ->save(new FFMpeg\Format\Video\X264(), 'export-x264.mp4')
// //     ->save(new FFMpeg\Format\Video\WMV(), 'export-wmv.wmv')
// //     ->save(new FFMpeg\Format\Video\WebM(), 'export-webm.webm');
// // }

// // $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);

// // FFMpeg::fromDisk('videos')
// //     ->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
// //     ->addFilter(function (VideoFilters $filters) {
// //         $filters->resize(new \FFMpeg\Coordinate\Dimension(320, 240));
// //     })
// //      ->addFormat($lowBitrate, function($media) {
// //        $media->addFilter('scale=640:480');
// //    });
// //     ->export()
// //     ->toDisk('converted_videos')
// //     ->inFormat(new \FFMpeg\Format\Video\X264)
// //     ->save('ssssssssss.mp4');
// //  $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
// //         $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
// //         $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);
// //         $superBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1500);

// //     $ffmpeg = FFMpeg::fromDisk('videos')->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
// //    ->exportForHLS()
// //    ->addFormat($lowBitrate, function($media) {
// //        $media->addFilter('scale=640:480');
// //    });

// // //condition here:
// // if (true) { 
// //    $ffmpeg = $ffmpeg->addFormat($midBitrate, function($media) {
// //        $media->scale(960, 720);
// //    });
// // }
// // // 2nd condition here:
// // if (true) { 
// //    $ffmpeg = $ffmpeg->addFormat($highBitrate, function ($media) {
// //        $media->addFilter(function ($filters, $in, $out) {
// //            $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
// //        });
// //    });
// // }

// // $ffmpeg->save('adaptive_steve.m3u8');




// }



public function upload_video(Request $request){
    $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
    $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
    $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);
    $superBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1500);

    // FFMpeg::open('video.mp4')
    // ->exportForHLS()
    // ->withRotatingEncryptionKey(function ($filename, $contents) {
    //     $videoId = 1;

    //     // use this callback to store the encryption keys

    //     Storage::disk('converted_videos')->put($videoId . '/' . $filename, $contents);

    //     // or...

    //     // DB::table('hls_secrets')->insert([
    //     //     'video_id' => $videoId,
    //     //     'filename' => $filename,
    //     //     'contents' => $contents,
    //     // ]);
    // })
    // ->addFormat($lowBitrate)
    // ->addFormat($midBitrate)
    // ->addFormat($highBitrate)
    // ->save('adaptive_steve.m3u8');



// $encryptionKey = HLSExporter::generateEncryptionKey();

// FFMpeg::open('video.mp4')
//     ->exportForHLS()
//     ->withEncryptionKey($encryptionKey)
//     ->addFormat($lowBitrate)
//     ->addFormat($midBitrate)
//     ->addFormat($highBitrate)
//     ->save('adaptive_steve.m3u8');



    $lowBitrate = (new \FFMpeg\Format\Video\X264('libfaac', 'libx264'))->setKiloBitrate(250);
    $midBitrate = (new \FFMpeg\Format\Video\X264('libfaac', 'libx264'))->setKiloBitrate(500);
    $highBitrate = (new \FFMpeg\Format\Video\X264('libfaac', 'libx264'))->setKiloBitrate(1000);


    FFMpeg::FromDisk('videos')->open('videonew.mp4')
    ->exportForHLS()
            ->setSegmentLength(10) // optional
            ->setKeyFrameInterval(48) // optional
            ->addFormat($lowBitrate, function ($video) {
                // $video->addLegacyFilter(function ($filters) {
                //     $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
                // });
            })
            ->addFormat($midBitrate)
            ->addFormat($highBitrate)
            ->save('public/hls/' . '1' . '/video.m3u8');






// FFMpeg::open('videonew.mp4')
//     ->exportForHLS()
//     ->addFormat($lowBitrate, function($media) {
//         $media->addFilter('scale=640:480');
//     })
//     ->addFormat($midBitrate, function($media) {
//         $media->scale(960, 720);
//     })
//     ->addFormat($highBitrate, function ($media) {
//         $media->addFilter(function ($filters, $in, $out) {
//             $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
//         });
//     })
//     ->addFormat($superBitrate, function($media) {
//         $media->addLegacyFilter(function ($filters) {
//             $filters->resize(new \FFMpeg\Coordinate\Dimension(2560, 1920));
//         });
//     })
//     ->save('adaptive_steve.m3u8');


    // $data = [];
    // $method = $request->method();
    // if($method == 'post' || $method == 'POST'){
    //     $video = $request->file('video')->getPathName();
    //     $bitrate = $request->bitrate;
    //     $command = "/usr/local/bin/ffmpeg -i $video -b:v $bitrate -bufsize $bitrate saaaaaa.mp4";
    //     system($command);

    //     echo "File has been converted";
    // }



    // return view('admin.home.upload_video',$data);


// FFMpeg::fromDisk('videos')
//     ->openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
//     ->addFilter(function (VideoFilters $filters) {
//         $filters->resize(new \FFMpeg\Coordinate\Dimension(640, 480));
//     })
//     ->export()
//     ->toDisk('converted_videos')
//     ->inFormat(new \FFMpeg\Format\Video\X264)
//     ->save('small_steve.mp4');

// $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
// $midBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(500);
// $highBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1000);
// $superBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(1500);

// FFMpeg::openUrl('https://krantikari.appmantra.live/public/assets/mov_bbb.mp4')
//     ->exportForHLS()
//     ->addFormat($lowBitrate, function($media) {
//         $media->addFilter('scale=640:480');
//     })
//     ->addFormat($midBitrate, function($media) {
//         $media->scale(960, 720);
//     })
//     ->addFormat($highBitrate, function ($media) {
//         $media->addFilter(function ($filters, $in, $out) {
//             $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
//         });
//     })
//     ->addFormat($superBitrate, function($media) {
//         $media->addLegacyFilter(function ($filters) {
//             $filters->resize(new \FFMpeg\Coordinate\Dimension(2560, 1920));
//         });
//     })
//     // ->toDisk('converted_videos')
//     ->save('asdasd.mp4');







        }





        public function register_custom_places(Request $request){

            $data = [];

            $method = $request->method();
            if($method == 'post' || $method == 'POST'){
                $rules = [];
                $rules['category_id'] = 'required';
                $rules['name'] = 'required';
                $rules['address'] = 'required';
                $rules['latitude'] = 'required';
                $rules['longitude'] = 'required';
                $rules['status'] = 'required';
                $this->validate($request,$rules);
                $file = $request->file('image');
                $dbArray = [];
                $dbArray['parent'] = 0;
                $dbArray['business_name'] = $request->name??'';
                $dbArray['business_type'] = 'others';
                $dbArray['owner_name'] = '';
                $dbArray['address'] = $request->address??'';
                $dbArray['latitude'] = $request->latitude??'';
                $dbArray['longitude'] = $request->longitude??'';
                $dbArray['image'] = $this->save_image($file);

                $dbArray['status'] = 1;
                $dbArray['longitude'] = $request->longitude??'';
                
                $id = Business::insertGetId($dbArray);
                $dbArr = [];
                $dbArr['business_id'] = $id;
                $dbArr['cat_id'] = $request->category_id;
                $dbArr['sub_cat_id'] = 0;
                $dbArr['status'] = 1;
                BusinessCategory::insert($dbArr);
                return back();

            }

            return view('register_custom_places',$data);
        }



        public function save_image($file){
            if ($file) {
                $path = 'business_gallery/';
                $thumb_path = 'business_gallery/thumb/';
                $storage = Storage::disk('public');
                $IMG_WIDTH = 768;
                $IMG_HEIGHT = 768;
                $THUMB_WIDTH = 336;
                $THUMB_HEIGHT = 336;
                $image ='';
                $uploaded_data = CustomHelper::UploadImage($file, $path, $ext='', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb=true, $thumb_path, $THUMB_WIDTH, $THUMB_HEIGHT);
                if($uploaded_data['success']){
                    $image = $uploaded_data['file_name'];
                }

                if(!empty($image)){   
                    return  $image;
                }  

            }

        }





        public function set_tab_in_session(Request $request){
            $key = $request->key??'video';
            Session::put('key',$key);
            echo 1;
        }


        public function get_sub_category(Request $request){
            $category_id = $request->category_id??'';
            if(!empty($category_id)){
                $category_id = explode(",", $category_id);
            }
            $html ='';
            $subcategories = [];
            if(!empty($category_id)){
                $subcategories = SubCategory::whereIn('category_id',$category_id)->get();

            }
            
            if(!empty($subcategories)){
                foreach($subcategories as $subcat){
                    $html.='<option value='.$subcat->id.'>'.$subcat->name??''.'</option>';
                }
            }
            echo $html;
        }


        public function generate_slug(Request $request){
            $title = $request->title??'';
            $table = $request->table??'';

            $slug = CustomHelper::GetSlug($table, 'id', '', $title);
            return json_encode(['success'=>true,'slug'=>$slug]);
        }

        public function image_upload(Request $request){
            if(isset($_FILES['upload']['name']))
            {
             $file = $_FILES['upload']['tmp_name'];
             $file_name = $_FILES['upload']['name'];
             $file_name_array = explode(".", $file_name);
             $extension = end($file_name_array);
             $new_image_name = rand() . '.' . $extension;
               // chmod('upload', 0777);
             $allowed_extension = array("jpg", "gif", "png");

             move_uploaded_file($file, public_path('/uploads/') . $new_image_name);
             $function_number = $_GET['CKEditorFuncNum']??'1';
             $url = '/public/uploads/' . $new_image_name;
             $message = '';
             echo "<script>window.parent.CKEDITOR.tools.callFunction('$function_number', '$url', '$message');</script>";

         }
     }


 }