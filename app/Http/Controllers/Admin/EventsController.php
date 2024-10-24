<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;
use DB;

class EventsController extends Controller
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
        $upcomingEvents = Event::orderBy('id', 'desc');
        if (!empty($search)) {
            $upcomingEvents->where('title', 'like', '%' . $search . '%');
        }

        $upcomingEvents = $upcomingEvents->paginate(10);
        $data['upcomingEvents'] = $upcomingEvents;

        return view('admin.upcoming-events.index', $data);
    }



    public function add(Request $request)
    {
        
        $data = [];
        $id = (isset($request->id)) ? $request->id : 0;

        $upcomingEvents = '';
        if (!empty($id)) {
            $upcomingEvents = Event::where('id', $id)->first();
            if (empty($upcomingEvents)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/events');
            }
        }

        if ($request->method() == 'POST' || $request->method() == 'post') {

            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/events';
            }

            $name = (isset($request->name)) ? $request->name : '';


            $rules = [];
            $rules['title'] = 'required';
            $rules['event_date'] = 'required';
            $rules['event_time'] = 'required';
            $rules['address'] = 'required';
            $rules['allowed_people_no'] = 'required';

            $this->validate($request, $rules);

            $createdCat = $this->save($request, $id);

            if ($createdCat) {
                $alert_msg = 'Event has been added successfully.';
                if (is_numeric($id) && $id > 0) {
                    $alert_msg = 'Event has been updated successfully.';
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }


        $page_heading = 'Add Upcoming Events';

        if (!empty($id)) {
            $exam_categories_name = $upcomingEvents->title;
            $page_heading = 'Update Upcoming Events';
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['upcomingEvents'] = $upcomingEvents;


        return view('admin.upcoming-events.form', $data);
    }

    public function save(Request $request, $id = 0)
    {

        $data = $request->except(['_token', 'back_url', 'title', 'image', 'amount', 'payment_type', 'password']);

        $data['title'] = Str::slug($request->title, '-');
        $data['title'] = $request->title;
        if($request->payment_type == 'Free'){
            $data['amount'] = '';
        }else{
            if($request->amount == ''){
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }else{
                $data['amount'] = $request->amount;
            }
        }
        $oldImg = '';
        $upcomingEvents = new Event;

        if (!empty($id)) {
            $exist = Event::where('id', $id)->first();

            if (isset($exist->id) && $exist->id == $id) {
                $upcomingEvents = $exist;

                $oldImg = $exist->image;
            }
        }

        foreach ($data as $key => $val) {
            $upcomingEvents->$key = $val;
        }

        $isSaved = $upcomingEvents->save();
        // remove old imgs from s3-bucket single/main-img & multi-imgs as both
        if ($isSaved) {
            $file = $request->file('image');
            if (!empty($file)) {
                $imagePaths = $upcomingEvents->image;
                Storage::disk('s3')->delete('plus91-backend/' . $imagePaths);
            }
            $this->saveImage($request, $upcomingEvents, $oldImg, $file);
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
            $event = Event::find($id);
            // remove old imgs from s3-bucket single/main-img & multi-imgs as both
            if ($event) {
                $imagePaths = $event->image;
                Storage::disk('s3')->delete('plus91-backend/' . $imagePaths);
                $is_delete = Event::where('id', $id)->delete();
            }
            // end s3 
        }
        if ($is_delete) {
            return back()->with('alert-success', 'Event Deleted Successfully');
        } else {
            return back()->with('alert-danger', 'Something went wrong, please try again...');
        }
    }
    
    public function details(Request $request)
    {
        $id = $request->id ?? 0;
        $data = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('events as e', 't.event_id', '=', 'e.id')
            ->select(
                't.*',
                'u.name as userName',
                'u.email',
                'u.phone',
                'e.title as eventName',
                'e.event_date',
                'e.event_time',
                'e.address',
                't.razorpay_order_id'
            )
            ->orderBy('t.id', 'DESC')->where('e.id', $id)->paginate(10);
        return view('admin.upcoming-events.details', compact('data'));
    }
}
