<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    private $ADMIN_ROUTE_NAME;
    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        $banners = Banner::where('is_delete', 0)->paginate(10);
        $data['banners'] = $banners;
        return view('admin.banners.index', $data);
    }

    public function add(Request $request)
    {
        $details = [];
        $id = isset($request->id) ? $request->id : 0;
        $banners = '';
        if (is_numeric($id) && $id > 0) {
            $banners = Banner::find($id);
            if (empty($banners)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/banners');
            }
        }
        if ($request->method() == "POST" || $request->method() == "post") {
            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/banners';
            }
            if (is_numeric($request->id) && $request->id > 0) {
                $details['image'] = '';
            } else {
                $details['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
            }
            $this->validate($request, $details);
            $createdDetails = $this->save($request, $id);
            if ($createdDetails) {
                $alert_msg = "Banner Created Successfully";
                if (is_numeric($id) & $id > 0) {
                    $alert_msg = "Banner Updated Successfully";
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }
        $page_Heading = "Add Banner";
        if (is_numeric($id) && $id > 0) {
            $page_Heading = 'Update Banner';
        }
        $details['page_Heading'] = $page_Heading;
        $details['banners'] = $banners;
        return view('admin.banners.form', $details);
    }


    public function save(Request $request, $id = 0)
    {
        $details = $request->except(['_token', 'back_url']);
        $oldImg = '';
        $banners = new Banner;
        if (is_numeric($id) && $id > 0) {
            $exist = Banner::find($id);
            if (isset($exist->id) && $exist->id == $id) {
                $banners = $exist;
                $oldImg = $exist->image;
            }
        }
        foreach ($details as $key => $val) {
            $banners->$key = $val;
        }
        $isSaved = $banners->save();
        // remove old imgs from s3-bucket single/main-img & multi-imgs as both
        if ($isSaved) {
            $file = $request->file('image');
            if (!empty($file)) {
                $imagePaths = $banners->image;
                Storage::disk('s3')->delete('plus91-backend/' . $imagePaths);
            }
            $this->saveImage($request, $banners, $oldImg, $file);
        }
        // end s3 
        return $isSaved;
    }


    // s3 start 
    private function saveImage($request, $hotel, $oldImg = '', $file)
    {
        if ($file) {
            $path = Storage::disk('s3')->put('plus91-backend', $file);
            $path = Storage::disk('s3')->url($path);
            $fileName = basename($path);
            $hotel->image = $fileName;
            $hotel->save();
            if ($oldImg) {
                $oldImagePath = 'plus91-backend/' . $oldImg;
                Storage::disk('s3')->delete($oldImagePath);
            }
        }
    }
    // end s3 

    public function change_banner_status(Request $request)
    {
        $id = isset($request->id) ? $request->id : '';
        $status = isset($request->status) ? $request->status : '';

        $faculties = Banner::where('id', $id)->first();
        if (!empty($faculties)) {

            Banner::where('id', $id)->update(['status' => $status]);
            $response['success'] = true;
            $response['message'] = 'Status updated';
            return response()->json($response);
        } else {
            $response['success'] = false;
            $response['message'] = 'Not  Found';
            return response()->json($response);
        }
    }

    public function delete(Request $request)
    {
        $id = $request->id ?? 0;
        $is_delete = 0;
        if (is_numeric($id) && $id > 0) {
            $banner = Banner::find($id);
            // remove old imgs from s3-bucket single/main-img & multi-imgs as both
            if ($banner) {
                $imagePaths = $banner->image;
                Storage::disk('s3')->delete('plus91-backend/' . $imagePaths);
                $is_delete = Banner::where('id', $id)->delete();
            }
            // end s3 
        }
        if ($is_delete) {
            return back()->with('alert-success', 'Banner Deleted Successfully');
        } else {
            return back()->with('alert-danger', 'Something went wrong, please try again...');
        }
    }
}
