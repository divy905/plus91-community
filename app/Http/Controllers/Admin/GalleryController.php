<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    private $ADMIN_ROUTE_NAME;
    public function __construct()
    {
        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();
    }

    public function index(Request $request)
    {
        $search = isset($request->search) ? $request->search : '';
        $data['search'] = $search;
        $gallery = Gallery::orderBy('id', 'desc');
        if (!empty($search)) {
            $gallery->where('title', 'like', '%' . $search . '%');
        }

        $gallery = $gallery->paginate(10);
        $data = $gallery;
        return view('admin.gallery.index', compact('data'));
    }

    public function add(Request $request)
    {
        $data = [];
        $id = (isset($request->id)) ? $request->id : 0;

        $gallery = '';
        if (!empty($id)) {
            $gallery = DB::table('galleries')->where('id', $id)->first();
            if (empty($gallery)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/gallery');
            }
        }

        if ($request->method() == 'POST' || $request->method() == 'post') {

            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/gallery';
            }

            $name = (isset($request->name)) ? $request->name : '';


            $rules = [
                'title' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ];

            $messages = [
                'title.required' => 'The Title field is required.',
                'image.image' => 'The Main Image must be an image file.',
                'image.mimes' => 'The Main Image must be a file of type: jpeg, png, jpg, gif, svg.',
                'image.max' => 'The Main Image may not be greater than 2048 kilobytes.',
            ];

            $this->validate($request, $rules, $messages);


            $createdCat = $this->save($request, $id);

            if ($createdCat) {
                $alert_msg = 'Gallery has been added successfully.';
                if (is_numeric($id) && $id > 0) {
                    $alert_msg = 'Gallery has been updated successfully.';
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }


        $page_heading = 'Add Gallery';
        if (!empty($id)) {
            $title = $gallery->title;
            $page_heading = 'Update Gallery';
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['gallery'] = $gallery;
        return view('admin.gallery.form', $data);
    }

    public function save(Request $request, $id = 0)
    {
        $data = $request->except(['_token', 'back_url', 'images', 'image', 'password']);
        $oldSingleImg = '';
        $oldMultipleImgs = '';

        $gallery = new Gallery;

        if (!empty($id)) {
            $exist = Gallery::where('id', $id)->first();
            if (isset($exist->id) && $exist->id == $id) {
                $gallery = $exist;
                $oldSingleImg = $exist->image;
                $oldMultipleImgs = $exist->images;
            }
        }

        foreach ($data as $key => $val) {
            $gallery->$key = $val;
        }

        $isSaved = $gallery->save();

        if ($isSaved) {
            // Handle single image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $this->saveImageToS3($gallery, $file, $oldSingleImg);
            }

            // Handle multiple images upload
            if ($request->hasFile('images')) {
                $this->saveMultipleImagesToS3($request, $gallery, $oldMultipleImgs);
            }
        }

        return $isSaved;
    }

    private function saveImageToS3($gallery, $file, $oldImg = '')
    {
        if ($file) {
            $path = Storage::disk('s3')->put('plus91-backend', $file);
            $fileName = basename($path);
            $gallery->image = $fileName;
            $gallery->save();

            // Delete old image from S3
            if ($oldImg) {
                $oldImagePath = 'plus91-backend/' . $oldImg;
                Storage::disk('s3')->delete($oldImagePath);
            }
        }
    }

    private function saveMultipleImagesToS3($request, $gallery, $oldImgs = '')
    {
        $images = $request->file('images');
        $galleryImages = [];

        if ($images) {
            // Upload new images
            foreach ($images as $file) {
                if ($file) {
                    $path = Storage::disk('s3')->put('plus91-backend', $file);
                    $fileName = basename($path);
                    $galleryImages[] = $fileName;
                }
            }

            // Delete old images from S3 if needed
            if (!empty($oldImgs)) {
                $oldImagesArray = explode(',', $oldImgs);
                foreach ($oldImagesArray as $oldImage) {
                    $oldImagePath = 'plus91-backend/' . $oldImage;
                    if (Storage::disk('s3')->exists($oldImagePath)) {
                        Storage::disk('s3')->delete($oldImagePath);
                    }
                }
            }

            // Save new image names to the gallery
            $gallery->images = implode(',', $galleryImages);
            $gallery->save();
        }
    }




    public function delete($id)
    {
        $is_delete = 0;
        if (is_numeric($id) && $id > 0) {
            $gallery = Gallery::find($id);
            // remove old imgs from s3-bucket single/main-img & multi-imgs as both
            if ($gallery) {
                // Delete single image
                $imagePath = $gallery->image;
                if ($imagePath) {
                    Storage::disk('s3')->delete('plus91-backend/' . $imagePath);
                }

                // Delete multiple images
                $images = $gallery->images;
                if ($images) {
                    $imageArray = explode(',', $images);
                    foreach ($imageArray as $image) {
                        $imagePath = 'plus91-backend/' . $image;
                        if (Storage::disk('s3')->exists($imagePath)) {
                            Storage::disk('s3')->delete($imagePath);
                        }
                    }
                }

                // Delete the gallery entry from the database
                $is_delete = Gallery::where('id', $id)->delete();
            }
            // end s3 
        }
        if ($is_delete) {
            return back()->with('alert-success', 'Images Deleted Successfully');
        } else {
            return back()->with('alert-danger', 'Something went wrong, please try again...');
        }
    }
}
