<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $ADMIN_ROUTE_NAME;

    public function __construct(){

        $this->ADMIN_ROUTE_NAME = CustomHelper::getAdminRouteName();

    }


    public function index(Request $request)
    {
        $product = Product::where('status',1)->latest()->paginate(10);
        $data['product'] = $product;
        return view('admin.product.index',$data);
    }

    public function add(Request $request)
    {
        $details = [];

        $id = isset($request->id) ? $request->id : 0;

        $products = '';

        if (is_numeric($id) && $id > 0) {

            $products = Product::find($id);

            if (empty($products)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/products');
            }
        }


        if ($request->method() == "POST" || $request->method() == "post") {

            // prd($request->toArray());

            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/products';
            }


            if (is_numeric($request->id) && $request->id > 0) {

                $details['image'] = '';
            } 
            // else {

            //     $details['image'] = 'required';
            // }

            // $this->validate($request, $details);

            $createdDetails = $this->save($request, $id);

            if ($createdDetails) {
                $alert_msg = "Product Created Successfully";

                if (is_numeric($id) & $id > 0) {
                    $alert_msg = "Product Updated Successfully";
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {

                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }

        $page_Heading = "Add Product";


        if (is_numeric($id) && $id > 0) {
            $page_Heading = 'Update Product';
        }

        $details['page_Heading'] = $page_Heading;

        $details['products'] = $products;

        return view('admin.product.form', $details);
    }


    public function save(Request $request, $id = 0)
    {
        // prd($request->toArray());
        // $qty = $request->qty;
        // $msr_unit = $request->msr_unit;
        // $price = $request->price;
        // $discounted_price = $request->discounted_price;
        // foreach($qty as $key => $val){
        //     $data['qty'] = $val;
        //     $data['msr_unit'] = $msr_unit[$key];
        //     $data['price'] = $price[$key];
        //     $data['discounted_price'] = $discounted_price[$key];
        //     DB::table('prd_prices')->insert();
        // }
        // print_r($data);die;
        
        // print_r($details);die;
        $details = $request->except(['_token', 'back_url','qty','msr_unit','price','discounted_price','prd_images','other_images']);
        $details['qty'] = implode(',',$request->qty);
        $details['msr_unit'] = implode(',',$request->msr_unit);
        $details['price'] = implode(',',$request->price);
        $details['discounted_price'] = implode(',',$request->discounted_price);
        $details['created_by'] = Auth::guard('admin')->user()->id;


        $old_img = '';

        $products = new Product;

        if (is_numeric($id) && $id > 0) {
            $exist = Product::find($id);

            if (isset($exist->id) && $exist->id == $id) {
                $products = $exist;
                $old_img = $exist->image;
            }
        }

        foreach ($details as $key => $val) {
            $products->$key = $val;
        }
        // echo '<pre>';
        // print_r($products);die;
        
    
        $isSaved = $products->save();

        if ($isSaved) {
            $file = $request->file('prd_images');
            $this->saveImage($request, $products, $old_img, $file);
            $this->saveMultipleImage($request, $products, $old_img);
            
        }

        return $isSaved;
    }

    private function saveImage($request, $products, $oldImg = '', $file)
    {

        // $file = $request->file('image');

        //prd($file);
        if ($file) {
            $path = 'products/';
            $thumb_path = 'products/thumb/';
            $storage = Storage::disk('public');
            //prd($storage);
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = '', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $THUMB_WIDTH, $THUMB_HEIGHT);

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

                    $products->prd_images = $image;
                
                $products->save();
            }
            if (!empty($uploaded_data)) {
                return  $uploaded_data;
            }
        }
    }

    private function saveMultipleImage($request, $products, $oldImg = '')
    {

        $other_images = $request->file('other_images');
        if ($other_images) {
        foreach($other_images as $key => $file){

    
        if ($file) {
            $path = 'products/';
            $thumb_path = 'products/thumb/';
            $storage = Storage::disk('public');
            //prd($storage);
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = '', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $THUMB_WIDTH, $THUMB_HEIGHT);

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

                $otherImages[$key] = $image;
                
            }
        }
    }
    $products->other_images = implode(',',$otherImages);
                $products->save();

                return   $otherImages; 
}
    }


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
        $id = isset($request->id) ? $request->id : 0;



        $is_delete = 0;

        if (empty($back_url)) {
            $back_url = $this->ADMIN_ROUTE_NAME . '/banners';
        }

        if (is_numeric($id) && $id > 0) {
            //echo $id;
            $is_delete = Banner::where('id', $id)->update(['is_delete' => '1']);
        }

        //die;

        if (!empty($is_delete)) {
            return back()->with('alert-success', 'Banner Deleted Successfully');
        } else {

            return back()->with('alert-danger', 'something went wrong, please try again...');
        }
    }

    public function fetchSubCat(Request $request){
        $catId =  $request->catId;
        $data =  SubCategory::where('category_id', $catId)->where('status',1)->where('is_delete',0)->get();
        $options  = '';
        foreach ($data as $cat) {
           $options .= '<option value="'.$cat->id.'">'.$cat->name.'</option>';
        }
        if(count($data)>0){
            $res['success'] = true;
            $res['data'] = $options; 
        }else{
            $res['success'] =  false;
            $res['data'] = '';
        }
        return response()->json($res);
    }
}
