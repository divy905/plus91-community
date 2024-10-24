<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationController extends Controller
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
        $notifications = Notification::orderBy('id', 'desc');
        if (!empty($search)) {
            $notifications->where('title', 'like', '%' . $search . '%');
            $notifications->orWhere('date', 'like', '%' . $search . '%');
        }
        $notifications = $notifications->paginate(10);
        $data['notifications'] = $notifications;
        return view('admin.notifications.index', $data);
    }



    public function add(Request $request)
    {
        $data = [];
        $id = (isset($request->id)) ? $request->id : 0;
        $notifications = '';
        if (!empty($id)) {
            $notifications = Notification::where('id', $id)->first();
            if (empty($notifications)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/notifications');
            }
        }
        if ($request->method() == 'POST' || $request->method() == 'post') {
            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/notifications';
            }
            $name = (isset($request->name)) ? $request->name : '';
            $rules = [];
            $rules['title'] = 'required';
            $this->validate($request, $rules);
            $createdCat = $this->save($request, $id);
            if ($createdCat) {
                $alert_msg = 'Notification has been added successfully.';
                if (is_numeric($id) && $id > 0) {
                    $alert_msg = 'Notification has been updated successfully.';
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }
        $page_heading = 'Add Notification';
        if (!empty($id)) {
            $exam_categories_name = $notifications->title;
            $page_heading = 'Update Notification';
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['notifications'] = $notifications;
        return view('admin.notifications.form', $data);
    }

    public function save(Request $request, $id = 0)
    {
        $data = $request->except(['_token', 'back_url', 'title', 'image', 'amount', 'payment_type', 'password']);
        $data['title'] = Str::slug($request->title, '-');
        $data['title'] = $request->title;
        $notifications = new Notification;

        if (!empty($id)) {
            $exist = Notification::where('id', $id)->first();
            if (isset($exist->id) && $exist->id == $id) {
                $notifications = $exist;
            }
        }
        foreach ($data as $key => $val) {
            $notifications->$key = $val;
        }
        $isSaved = $notifications->save();
        return $isSaved;
    }

    public function delete($id)
    {
        $is_delete = Notification::find($id);
        if ($is_delete->delete()) {
            return back()->with('alert-success', 'Notification Deleted Successfully');
        } else {
            return back()->with('alert-danger', 'Something went wrong, please try again...');
        }
    }
}
