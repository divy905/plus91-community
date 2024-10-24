<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller

{
    private $ADMIN_ROUTE_NAME;
    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        $data = [];
        $categories = Category::where('is_delete', 0)->latest();
        $search = $request->search ?? '';
        $type = $request->type ?? '';
        if (!empty($search)) {

            $categories->where('name', 'like', '%' . $search . '%');
        }
        if (!empty($type)) {

            $categories->where('type', $type);
        }
        $categories = $categories->paginate(50);
        $data['categories'] = $categories;
        return view('admin.groups.index', $data);
    }

    public function add(Request $request)
    {
        $data = [];
        $id = (isset($request->id)) ? $request->id : 0;
        $categories = '';
        if (!empty($id)) {
            $categories = Category::where('id', $id)->first();
            if (empty($categories)) {

                return redirect($this->ADMIN_ROUTE_NAME . '/groups');
            }
        }
        if ($request->method() == 'POST' || $request->method() == 'post') {
            if (empty($back_url)) {

                $back_url = $this->ADMIN_ROUTE_NAME . '/groups';
            }
            $name = (isset($request->name)) ? $request->name : '';
            $rules = [];
            if (is_numeric($id) && $id > 0) {
                $rules['name'] = 'required';
            } else {
            }
            $this->validate($request, $rules);
            $createdCat = $this->save($request, $id);
            if ($createdCat) {
                $alert_msg = 'Group has been added successfully.';
                if (is_numeric($id) && $id > 0) {
                    $alert_msg = 'Group has been updated successfully.';
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }
        $page_heading = 'Add Group';
        if (!empty($id)) {

            $categories_name = $categories->name;

            $page_heading = 'Update Group - ' . $categories_name;
        }
        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['categories'] = $categories;
        return view('admin.groups.form', $data);
    }

    public function save(Request $request, $id = 0)
    {
        $data = $request->except(['_token', 'back_url', 'image', 'password', 'image_name', 'main_image', 'mainimage_name', 'page']);
        if ($id == 0) {
            $slug = CustomHelper::GetSlug('all_categories', 'slug', '', $request->name);
            $data['slug'] = $slug;
        }
        $oldImg = '';
        $categories = new Category;
        if (!empty($id)) {
            $exist = Category::where('id', $id)->first();
            if (isset($exist->id) && $exist->id == $id) {
                $categories = $exist;
                $oldImg = $exist->image;
            }
        }
        foreach ($data as $key => $val) {
            $categories->$key = $val;
        }
        $isSaved = $categories->save();
        // remove old imgs from s3-bucket single/main-img & multi-imgs as both
        if ($isSaved) {
            $file = $request->file('image');
            if (!empty($file)) {
                $imagePaths = $categories->image;
                Storage::disk('s3')->delete('plus91-backend/' . $imagePaths);
            }
            $this->saveImage($request, $categories, $oldImg, $file);
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

    public function delete(Request $request)
    {
        $id = $request->id ?? 0;
        $is_delete = 0;
        if (is_numeric($id) && $id > 0) {
            $banner = Category::find($id);
            // remove old imgs from s3-bucket single/main-img & multi-imgs as both
            if ($banner) {
                $imagePaths = $banner->image;
                Storage::disk('s3')->delete('plus91-backend/' . $imagePaths);
                $is_delete = Category::where('id', $id)->delete();
            }
            // end s3 
        }
        if ($is_delete) {
            return back()->with('alert-success', 'Group Deleted Successfully');
        } else {
            return back()->with('alert-danger', 'Something went wrong, please try again...');
        }
    }
}
