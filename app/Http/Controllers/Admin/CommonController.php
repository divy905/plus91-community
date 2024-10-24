<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    public function settingsUpdate(Request $request)
    {
        $data['about_us'] = $request->about_us;
        $data['privacypolicy'] = $request->privacypolicy;
        $data['terms'] = $request->terms;
        $data['footer_title'] = $request->footer_title;
        $data['footer_desc'] = $request->footer_desc;
        if ($request->id) {
            DB::table('settings')->where('id', $request->id)->update($data);
        } else {
            DB::table('settings')->insert($data);
        }
        return redirect()->back()->with('alert-success', 'Data saved successfuly.');
    }

    public function settingsForm()
    {
        $data = DB::table('settings')->where('id', 1)->first();
        return view('admin.settings.index', compact('data'));
    }

    public function contactUs()
    {
        $data = DB::table('contactus as c')->join('users as u', 'u.id', 'c.user_id')->select('c.*', 'u.name', 'u.email', 'u.phone')->paginate(10);
        return view('admin.user.contactus', compact('data'));
    }

    public function bookingEventList()
    {
        $data = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('events as e', 't.event_id', '=', 'e.id')
            ->select(
                't.*',
                'u.name as userName',
                'e.title as eventName',
                'e.event_date',
                'e.event_time',
                'e.address',
                't.razorpay_order_id'
            )
            ->orderBy('t.id', 'DESC')->paginate(10);
        return view('admin.booking_events.index', compact('data'));
    }

    public function aboutUpdate(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $data['description'] = $request->description;
        $data['updated_at'] = now();
        $aboutUs = DB::table('aboutus')->where('id', $request->id)->first();

        // Handle image upload if a new image is uploaded
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($aboutUs && !empty($aboutUs->image)) {
                // Delete the old image from S3
                Storage::disk('s3')->delete('plus91-backend/' . $aboutUs->image);
            }

            // Save the new image to S3
            $path = Storage::disk('s3')->put('plus91-backend', $file);
            $data['image'] = basename($path);
        }

        // Update or insert the record
        if ($request->id) {
            DB::table('aboutus')->where('id', $request->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('aboutus')->insert($data);
        }

        return redirect()->back()->with('alert-success', 'Data saved successfully.');
    }

    public function aboutUsForm()
    {
        $data = DB::table('aboutus')->where('id', 1)->first();
        return view('admin.about.aboutus', compact('data'));
    }

    public function trusteeList(Request $request)
    {
        $searchTerm = $request->search;
        $query = DB::table('users')->where('is_trustee', 1);
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            });
        }
        $data = $query->paginate(10);

        return view('admin.trustee.index', compact('data'));
    }

    public function commiteeList(Request $request)
    {
        $searchTerm = $request->search;
        $query = DB::table('users')->where('is_commitee', 1);
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%');
            });
        }
        $data = $query->paginate(10);

        return view('admin.commitee.index', compact('data'));
    }

    public function changeCotactStatus(Request $request){
        $id = isset($request->id) ? $request->id :'';
        $status = isset($request->status) ? $request->status :'';
      
        $data = DB::table('contactus')->where('id',$id)->first();
        if(!empty($data)){
      
           DB::table('contactus')->where('id',$id)->update(['status'=>$status]);
           $response['success'] = true;
           $response['message'] = 'Status updated';
      
      
           return response()->json($response);
       }else{
           $response['success'] = false;
           $response['message'] = 'Not  Found';
           return response()->json($response);  
       }
      
      }
}
