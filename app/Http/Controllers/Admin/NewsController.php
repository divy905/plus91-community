<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
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
        $news = News::orderBy('id', 'desc');
        if (!empty($search)) {
            $news->where('title', 'like', '%' . $search . '%');
        }
        $news = $news->paginate(10);
        $data['news'] = $news;
        return view('admin.news.index', $data);
    }



    public function add(Request $request)
    {
        $data = [];
        $id = (isset($request->id)) ? $request->id : 0;
        $news = '';
        if (!empty($id)) {
            $news = News::where('id', $id)->first();
            if (empty($news)) {
                return redirect($this->ADMIN_ROUTE_NAME . '/news');
            }
        }
        if ($request->method() == 'POST' || $request->method() == 'post') {
            if (empty($back_url)) {
                $back_url = $this->ADMIN_ROUTE_NAME . '/news';
            }
            $name = (isset($request->name)) ? $request->name : '';
            $rules = [];
            $rules['title'] = 'required';
            $this->validate($request, $rules);
            $createdCat = $this->save($request, $id);
            if ($createdCat) {
                $alert_msg = 'News has been added successfully.';
                if (is_numeric($id) && $id > 0) {
                    $alert_msg = 'News has been updated successfully.';
                }
                return redirect(url($back_url))->with('alert-success', $alert_msg);
            } else {
                return back()->with('alert-danger', 'something went wrong, please try again or contact the administrator.');
            }
        }
        $page_heading = 'Add News';
        if (!empty($id)) {
            $exam_categories_name = $news->title;
            $page_heading = 'Update News';
        }

        $data['page_heading'] = $page_heading;
        $data['id'] = $id;
        $data['news'] = $news;
        return view('admin.news.form', $data);
    }

    public function save(Request $request, $id = 0)
    {
        $data = $request->except(['_token', 'back_url', 'title', 'image', 'amount', 'payment_type', 'password']);
        $data['title'] = Str::slug($request->title, '-');
        $data['title'] = $request->title;
        $news = new News;

        if (!empty($id)) {
            $exist = News::where('id', $id)->first();
            if (isset($exist->id) && $exist->id == $id) {
                $news = $exist;
            }
        }
        foreach ($data as $key => $val) {
            $news->$key = $val;
        }
        $isSaved = $news->save();
        return $isSaved;
    }

    public function delete($id)
    {
        $is_delete = News::find($id);
        if ($is_delete->delete()) {
            return back()->with('alert-success', 'News Deleted Successfully');
        } else {
            return back()->with('alert-danger', 'Something went wrong, please try again...');
        }
    }
}
