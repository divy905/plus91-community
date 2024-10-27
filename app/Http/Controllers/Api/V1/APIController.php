<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Testimonial;
use DB;
use Auth;

class APIController extends Controller
{
    use ApiResponseHelpers;
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

    public function home(Request $request)
    {
        $data['header_tag_line'] = 'Website is Under Construction';
        $data['banners'] = Banner::select('*', DB::raw("CONCAT('/public/storage/banners/', image) AS image"))->where('is_delete', 0)->where('status', 1)->limit(4)->latest()->get();

        // $data['about_us'] = DB::table('aboutus')->select('*', DB::raw("CONCAT('/public/backend/productimg/', image) AS image"))->where('id', 1)->first();

        // $data['blogs'] = Blogs::select('*', DB::raw("CONCAT('/public/storage/blogs/', image) AS 
        // image"))->where('is_delete', 0)->where('status', 1)->limit(3)->latest()->get();

        $data['testimonials'] = Testimonial::select('*', DB::raw("CONCAT('/public/storage/testimonials/', image) AS 
        image"))->where('status', 1)->limit(3)->latest()->get();

        $data['products'] = Product::select('products.id', 'products.prd_name', 'products.*', DB::raw("CONCAT('/public/storage/products/', products.prd_images) AS image"))
            ->join('ratings', 'products.id', '=', 'ratings.product_id')
            ->selectRaw('AVG(ratings.rating) AS average_rating')
            ->selectRaw('count(ratings.review) AS total_review')
            ->groupBy('products.id', 'products.prd_name')->latest()->limit(2)
            ->get();



        $data['rating'] = Rating::where('status', 1)->orderBy('id', 'DESC')->limit(5)->get();
        // $data['outlets'] = DB::table('outlets')->orderBy('id', 'DESC')->limit(15)->get();

        $data['hot_product'] = Product::select('products.id', 'products.prd_name', 'products.*', DB::raw("CONCAT('/public/storage/products/', products.prd_images) AS image"))
            ->join('ratings', 'products.id', '=', 'ratings.product_id')
            ->selectRaw('AVG(ratings.rating) AS average_rating')
            ->selectRaw('count(ratings.review) AS total_review')
            ->groupBy('products.id', 'products.prd_name')->latest()->limit(2)->where('is_hot', 1)
            ->get();





        return response()->json([
            'result' => true,
            'message' => 'Data Fetched SuccessFully',
            'data' => $data
        ], 200);
    }

    public function banner(Request $request)
    {
        $banners = Banner::select('id', DB::raw("CONCAT('" . env('AWS_STORAGE_URL') . "/', image) AS image"))
            ->where('is_delete', 0)
            ->where('status', 1)
            ->limit(4)
            ->latest()
            ->get();

        return response()->json([
            'result' => true,
            'message' => 'Banners Fetched Successfully',
            'data' => $banners
        ], 200);
    }


    public function userProfile(Request $request)
    {
        $user = User::where('is_delete', 0)
            ->where('id', $request->id)
            ->first();

        if (!$user) {
            return response()->json([
                'result' => false,
                'message' => 'User not found',
                'data' => null
            ], 404);
        }
        $headOfFamilyName = User::where('id', $user->head_of_family)->value('name');
        $user->headOfFamilyName = $headOfFamilyName;

        return response()->json([
            'result' => true,
            'message' => 'User Profile Fetched Successfully',
            'data' => $user
        ], 200);
    }



    function addAddress(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'name' => 'required',
            'mobile_number' => 'required|digits:10|numeric',
            'state' => 'required',
            'city' => 'required',
            'address1' => 'required',
            'pincode' => 'required|digits:6|numeric',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors())

            ], 400);
        }
        $data['user_id'] = Auth::user()->id;
        $data['name'] = $request->name;
        $data['mobile_number'] = $request->mobile_number;
        $data['state'] = $request->state;
        $data['city'] = $request->city;
        $data['address1'] = $request->address1;
        $data['address2'] = $request->address2;
        $data['pincode'] = $request->pincode;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        DB::table('address')->insert($data);
        return response()->json([
            'result' => true,
            'message' => 'Address Added SuccessFully',
            'data' => $data
        ], 200);
    }

    function updateAddress(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors())

            ], 400);
        }
        // if (!empty($request->user_id))
        //     $data['user_id'] = $request->user_id;

        if (!empty($request->name))
            $data['name'] = $request->name;

        if (!empty(Auth::user()->id))
            $data['user_id'] = Auth::user()->id;

        if (!empty($request->mobile_number))
            $data['mobile_number'] = $request->mobile_number;

        if (!empty($request->state))
            $data['state'] = $request->state;

        if (!empty($request->city))
            $data['city'] = $request->city;

        if (!empty($request->address1))
            $data['address1'] = $request->address1;

        if (!empty($request->address2))
            $data['address2'] = $request->address2;

        if (!empty($request->pincode))
            $data['pincode'] = $request->pincode;

        $data['updated_at'] = Carbon::now();



        DB::table('address')->where('id', $request->id)->update($data);
        return response()->json([
            'result' => true,
            'message' => 'Address Updated SuccessFully',
            'data' => $data
        ], 200);
    }

    public function aboutUs()
    {
        $data = DB::table('aboutus')->select('*', DB::raw("CONCAT('/public/backend/productimg/', image) AS image"))->where('id', 1)->first();
        return response()->json([
            'result' => true,
            'message' => 'Data Fetched SuccessFully',
            'data' => $data
        ], 200);
    }

    public function settings()
    {
        $data = DB::table('settings')->select('about_us', 'privacypolicy as privacy_policy',  'terms as terms_n_condition', 'footer_title', 'footer_desc')->first();
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data,
        ], 200);
    }


    // public function notification()
    // {
    //     $data = DB::table('notifications')->orderBy('id', 'desc')->get();
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Data Fetched SuccessFully',
    //         'data' => $data
    //     ], 200);
    // }

    public function nativeVillageList()
    {
        $query = DB::table('native_villags')->orderBy('name', 'ASC');
        $datalist = $query->paginate(20);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data Fetched SuccessFully',
            'data' => $data
        ], 200);
    }

    public function eventsList(Request $request, $id = null)
    {
        if ($id) {
            // Fetch event Details
            $event = Event::select('*', DB::raw("CONCAT('" . env('AWS_STORAGE_URL') . "/', image) AS image"))
                ->where('id', $id)
                ->first();

            $joinedPoeple = DB::table('transactions as t')
                ->join('events as e', 't.event_id', 'e.id')
                ->where('t.event_id', $id)->count();

            $isJoined = DB::table('transactions as t')
                ->join('events as e', 't.event_id', 'e.id')
                ->where('t.event_id', $id)->first();
            if ($event) {
                // Add booking events count to the event object
                $event->joinedPoeple = $joinedPoeple;
                if (!empty($isJoined)) {
                    $event->isJoined = $isJoined->is_joined;
                } else {
                    $event->isJoined = 0;
                }
                return response()->json([
                    'result' => true,
                    'message' => 'Event details fetched successfully',
                    'data' => $event
                ], 200);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Event not found',
                    'data' => null
                ], 404);
            }
        } else {
            // Fetch events list
            $userId =  Auth::user()->id;
            $currentDate = now();
            $query = Event::select(
                'events.*',
                DB::raw("CONCAT('" . env('AWS_STORAGE_URL') . "/', events.image) AS image"),
                DB::raw("IF(transactions.id IS NOT NULL, 1, 0) AS isJoined")
            )
                ->leftJoin('transactions', function ($join) use ($userId) {
                    $join->on('transactions.event_id', '=', 'events.id')
                        ->where('transactions.user_id', '=', $userId);
                });

            if (!empty($request->search)) {
                $query->where('events.title', 'like', '%' . $request->search . '%');
            }

            // Apply event_type filters
            if ($request->event_type == 1) {
                // Upcoming events
                $query->where('events.event_date', '>=', $currentDate);
            } elseif ($request->event_type == 2) {
                // Past events
                $query->where('events.event_date', '<', $currentDate);
            }

            $query->orderBy('events.id', 'desc');
            $datalist = $query->paginate(10);
            $data = $datalist->toArray();
            $data['dataItems'] = $data['data'];
            unset($data['data']);
            return response()->json([
                'result' => true,
                'message' => 'Data fetched successfully',
                'data' => $data
            ], 200);
        }
    }


    // error message print one by one 
    public function errorMessage($error)
    {
        $messages = $error->messages();
        foreach ($messages as $field => $messageArray) {
            if (!empty($messageArray)) {
                return $messageArray[0];
            }
        }
        return null;
    }


    public function isAccountExists(Request $request)
    {
        $data = null;
        if ($request->email) {
            $data = DB::table('users')->where('email', $request->email)->first();
        } elseif ($request->phone) {
            $data = DB::table('users')->where('phone', $request->phone)->first();
        }

        if (!$data) {
            $inputData = [];
            if ($request->phone) {
                $inputData['phone'] = $request->phone;
            }
            if ($request->email) {
                $inputData['email'] = $request->email;
            }
            $lastInsertId = DB::table('users')->insertGetId($inputData);
            $user = User::find($lastInsertId);
        } else {
            $user = User::find($data->id);
        }

        $token = $user->createToken('ikb')->accessToken;

        if ($data) {
            if ($request->email && !empty($data->phone)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Account Exists',
                    'is_registered' => true,
                    'token' => $token
                ], 200);
            } elseif ($request->phone && !empty($data->email)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Account Exists',
                    'is_registered' => true,
                    'token' => $token
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Account Exists but incomplete',
                    'is_registered' => false,
                    'token' => $token
                ], 200);
            }
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Account Created',
                'is_registered' => false,
                'token' => $token
            ], 200);
        }
    }

    public function contactUsQuerySend(Request $request)
    {
        if (Auth::user()->id) {
            $data['user_id'] = Auth::user()->id;
            $data['message'] = $request->message;
            DB::table('contactus')->insert($data);
        }
        return response()->json([
            'result' => true,
            'message' => 'Your query sent successfully, We will contact you shortly',
            'data' => ''
        ], 200);
    }

    public function helpAndSupportQueriesList(Request $request)
    {
        if (Auth::user()->id) {
            $userId = Auth::user()->id;
            $query = DB::table('contactus')->where('user_id', $userId)->orderBy('id', 'DESC');
            $datalist = $query->paginate(10);
            $data = $datalist->toArray();
            $data['dataItems'] = $data['data'];
            unset($data['data']);
        }
        return response()->json([
            'result' => true,
            'message' => 'Data fetcehd successfully.',
            'data' => $data
        ], 200);
    }

    public function galleryList(Request $request)
    {
        $query = Gallery::select('*', DB::raw("CONCAT('" . env('AWS_STORAGE_URL') . "/', image) AS image"))
            ->orderBy('id', 'DESC');

        $datalist = $query->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);

        // Loop through each item in the paginated data to add other_images
        foreach ($data['dataItems'] as &$gallery) {
            $otherImages = [];
            if (is_string($gallery['images'])) {
                $imageArray = explode(',', $gallery['images']);
                foreach ($imageArray as $img) {
                    $otherImages[] = env('AWS_STORAGE_URL') . '/' . $img;
                }
            }
            $gallery['other_images'] = $otherImages;
        }

        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }


    public function communityInfo()
    {
        $data = DB::table('aboutus')->select('*', DB::raw("CONCAT('" . env('AWS_STORAGE_URL') . "/', image) AS image"))->where('id', 1)->first();

        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function matrimonyList(Request $request, $id = null)
    {
        $native_village_id = $request->native_village_id;
        $name = $request->name;
        $profession = $request->profession;
        $gender  = $request->gender;
        $education  = $request->education;
        $group_id  = $request->group_id;
        $organisation = $request->organisation;
        $excludeGotraIds = explode(',', $request->exclude_gotra_id);
        $minAgeDate = $request->input('min_age');
        $maxAgeDate = $request->input('max_age');
        $maritulStatus = $request->maritul_status;

        $awsStorageUrl = env('AWS_STORAGE_URL');
        $query = DB::table('users')
            ->select(
                'users.*',
                'wishlist.is_matrimony',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'gotraNormal.name as gotraName',
                'sasuralGotra.name as sasuralGotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName',
                'headOfFamily.name AS headOfFamilyName',
                DB::raw("IF(users.head_of_family IS NOT NULL, COALESCE(headOfFamily.phone, 'N/A'), 'No Head of Family') AS phone"),
                DB::raw("IF(users.head_of_family IS NOT NULL, COALESCE(headOfFamily.native_full_address, 'N/A'), 'No Head of Family') AS usersFullAdress")
            )
            ->where('users.status', 1)
            ->leftJoin('wishlist', 'wishlist.user_added', '=', 'users.id')
            ->leftJoin('goatra as gotraNormal', 'users.gotra_id', '=', 'gotraNormal.id')
            ->leftJoin('goatra as sasuralGotra', 'users.sasural_gotra_id', '=', 'sasuralGotra.id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('users as headOfFamily', 'users.head_of_family', '=', 'headOfFamily.id');


        if ($id) {
            $data = $query->where('users.id', $id)->first();
            return response()->json([
                'result' => true,
                'message' => 'Data fetched successfully.',
                'data' => $data
            ], 200);
        }
        // $gender = Auth::user()->gender;
        // Add age restriction based on gender
        $today = now();
        if ($gender == 0 || $gender == 1) {
            // Apply the gender filter
            $query->where('users.gender', $gender);
        }


        if (!empty($maritulStatus)) {
            $query->where('users.maritl_status', $maritulStatus);
        } else {
            $query->where('users.maritl_status', '!=', 'Married');
        }

        if (!empty($native_village_id)) {
            $query->where('users.native_village_id', $native_village_id);
        }

        if (!empty($profession)) {
            $query->where('users.designation', 'like', '%' . $profession . '%');
        }

        if (!empty($education)) {
            $query->where('users.education', 'like', '%' . $education . '%');
        }

        if (!empty($name)) {
            $query->where('users.name', $name);
        }


        if (!empty($group_id)) {
            $query->where('users.group_id', $group_id);
        }

        if (!empty($organisation)) {
            $query->where('users.cmpny_name', $organisation);
        }


        if (!empty($request->gotra_id)) {
            $gotraIds = explode(',', $request->gotra_id);
            $query->whereIn('users.gotra_id', $gotraIds);
        }

        if (!empty($excludeGotraIds)) {
            $exgotraIds = explode(',', $request->exclude_gotra_id);
            $query->whereNotIn('users.gotra_id', $exgotraIds);
        }


        if (!empty($minAgeDate)) {
            $minAgeDateFormatted = \Carbon\Carbon::createFromFormat('d-m-Y', $minAgeDate)->format('Y-m-d');
            $query->where('users.dob', '>=', $minAgeDateFormatted);
        }

        if (!empty($maxAgeDate)) {
            $maxAgeDateFormatted = \Carbon\Carbon::createFromFormat('d-m-Y', $maxAgeDate)->format('Y-m-d');
            $query->where('users.dob', '<=', $maxAgeDateFormatted);
        }

        $query->where(function ($query) {
            $query->orWhere('wishlist.is_matrimony', 1)
                ->orWhereNull('wishlist.is_matrimony');
        });
        $datalist = $query->where('users.name', '!=', '')->where('users.id', '!=', Auth::user()->id)->paginate(10);
        $data = $datalist->toArray();
        foreach ($data as $key => $val) {
            if (!empty($val->head_of_family)) {
                $checkParent = DB::table('users')->where('name', $val->head_of_family)->first();
                $data[$key]['phone'] = $checkParent->phone;
            }
        }
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function matrimonyFamilyMemberList($id)
    {
        $awsStorageUrl = env('AWS_STORAGE_URL');
        $headOfFamilyName = DB::table('users')->select('name')->where('id', $id)->first();
        $query = DB::table('users')
            ->select(
                'users.*',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'gotraNormal.name as gotraName',
                'sasuralGotra.name as sasuralGotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName'
            )
            ->leftJoin('goatra as gotraNormal', 'users.gotra_id', '=', 'gotraNormal.id')
            ->leftJoin('goatra as sasuralGotra', 'users.sasural_gotra_id', '=', 'sasuralGotra.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id')->where('users.head_of_family', $id);

        $datalist = $query->where('users.name', '!=', '')->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        $data['headOfFamilyName'] = $headOfFamilyName->name ?? null;
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function directoryList(Request $request, $id = null)
    {
        $native_village_id = $request->native_village_id;
        $group_id = $request->group_id;
        $gotra_id = $request->gotra_id;
        $cmpny_name = $request->cmpny_name;
        $industry = $request->industry;
        $name = $request->name;
        $awsStorageUrl = env('AWS_STORAGE_URL');

        $query = DB::table('users')
            ->select(
                'users.*',
                'wishlist.is_directory',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'gotraNormal.name as gotraName',
                'sasuralGotra.name as sasuralGotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName',
                DB::raw("headOfFamily.name AS headOfFamilyName")
            )->where('users.status', 1)
            ->leftJoin('wishlist', 'users.id', '=', 'wishlist.user_added')
            ->leftJoin('goatra as gotraNormal', 'users.gotra_id', '=', 'gotraNormal.id')
            ->leftJoin('goatra as sasuralGotra', 'users.sasural_gotra_id', '=', 'sasuralGotra.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id')
            ->leftJoin('users as headOfFamily', 'users.head_of_family', '=', 'headOfFamily.id');

        if ($id) {
            $data = $query->where('users.id', $id)->where('users.name', '!=', '')->first();
            return response()->json([
                'result' => true,
                'message' => 'Data fetched successfully.',
                'data' => $data
            ], 200);
        }

        if (!empty($native_village_id)) {
            $query->where('users.native_village_id', $native_village_id);
        }

        if (!empty($group_id)) {
            $query->where('users.group_id', $group_id);
        }

        if (!empty($gotra_id)) {
            $query->where('users.gotra_id', 'like', '%' . $gotra_id . '%');
        }

        if (!empty($cmpny_name)) {
            $query->where('users.cmpny_name', 'like', '%' . $cmpny_name . '%');
        }
        if (!empty($industry)) {
            $query->where('users.industry', 'like', '%' . $industry . '%');
        }

        if (!empty($name)) {
            $query->where('users.name', 'like', '%' . $name . '%');
        }

        // Prepare an array of fields
        // $fields = [
        //     'native_village_id' => $native_village_id,
        //     'group_id' => $group_id,
        //     'gotra_id' => $gotra_id,
        //     'cmpny_name' => $cmpny_name,
        //     'name' => $name
        // ];

        // Apply filters based on provided fields
        // $this->applyFiltersDirectory($query, $fields);

        // Ensure user does not see their own record
        $query->whereNot('users.id', Auth::user()->id)
            ->where(function ($query) {
                $query->orWhere('wishlist.is_directory', 1)
                    ->orWhereNull('wishlist.is_directory');
            });

        // Apply search filter if provided
        // if (!empty($request->search)) {
        //     $query->where(function ($query) use ($request) {
        //         $query->orWhere('users.name', 'like', '%' . $request->search . '%')
        //             ->orWhere('users.email', 'like', '%' . $request->search . '%')
        //             ->orWhere('users.phone', 'like', '%' . $request->search . '%');
        //     });
        // }

        $datalist = $query->where('users.name', '!=', '')->where('users.head_of_family', null)->orderBy('users.name', 'Asc')->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }



    public function directoryFamilyMemberList($id)
    {
        $awsStorageUrl = env('AWS_STORAGE_URL');
        $headOfFamilyName = DB::table('users')->select('name')->where('id', $id)->first();
        $query = DB::table('users')
            ->select(
                'users.*',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'gotraNormal.name as gotraName',
                'sasuralGotra.name as sasuralGotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName'
            )
            ->leftJoin('goatra as gotraNormal', 'users.gotra_id', '=', 'gotraNormal.id')
            ->leftJoin('goatra as sasuralGotra', 'users.sasural_gotra_id', '=', 'sasuralGotra.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id')
            ->where('users.head_of_family', $id);
        $datalist = $query->where('users.name', '!=', '')->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        $data['headOfFamilyName'] = $headOfFamilyName->name ?? null;
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'headOfFamilyName' => $headOfFamilyName->name ?? null,
            'data' => $data
        ], 200);
    }


    public function groupList($id = null)
    {
        $awsStorageUrl = env('AWS_STORAGE_URL');
        $query = Category::select('id', 'name', 'created_at', 'updated_at', DB::raw("CONCAT('$awsStorageUrl/',image) AS image"))->orderBy('id', 'DESC');
        $datalist = $query->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function addWishlist(Request $request)
    {
        if ($request->is_matrimony == 1) {
            $data['is_matrimony'] = 1;
        }
        if ($request->is_directory == 1) {
            $data['is_directory'] = 1;
        }
        $data['user_id'] = Auth::user()->id;
        $data['user_added'] = $request->user_added;
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        $check1 = DB::table('wishlist')->where('user_id', Auth::user()->id)->where('user_added', $request->user_added)->where('is_matrimony', $request->is_matrimony)->first();
        if (!empty($check1)) {
            DB::table('wishlist')->where('id', $check1->id)->delete();
            return response()->json([
                'result' => true,
                'message' => 'Profile successfully removed from wishlist.',
                'data' => ''
            ], 200);
        } else {
            DB::table('wishlist')->insert($data);
            return response()->json([
                'result' => true,
                'message' => 'Profile successfully added to wishlist.',
                'data' => ''
            ], 200);
        }

        $check2 = DB::table('wishlist')->where('user_id', Auth::user()->id)->where('user_added', $request->user_added)->where('is_directory', $request->is_directory)->first();

        if (!empty($check2)) {
            DB::table('wishlist')->where('id', $check2->id)->delete();
            return response()->json([
                'result' => true,
                'message' => 'Profile successfully removed from wishlist.',
                'data' => ''
            ], 200);
        } else {
            DB::table('wishlist')->insert($data);
            return response()->json([
                'result' => true,
                'message' => 'Profile successfully added to wishlist.',
                'data' => ''
            ], 200);
        }
    }

    // this function is not in used 
    public function removeWishlist(Request $request)
    {
        if ($request->is_matrimony == 0) {
            $check1 = DB::table('wishlist')->where('user_id', Auth::user()->id)->where('user_added', $request->user_added)->where('is_matrimony', $request->is_matrimony)->first();
            DB::table('wishlist')->where('id', $check1->id)->delete();
        }

        if ($request->is_directory == 0) {
            $check2 = DB::table('wishlist')->where('user_id', Auth::user()->id)->where('user_added', $request->user_added)->where('is_directory', $request->is_directory)->first();
            DB::table('wishlist')->where('id', $check2->id)->delete();
        }

        return response()->json([
            'result' => true,
            'message' => 'Profile successfully removed from wishlist.',
            'data' => ''
        ], 200);
    }

    public function getAllWishlist(Request $request)
    {
        $query = DB::table('users as u')->join('wishlist', 'wishlist.user_added', '=', 'u.id')->leftJoin('goatra as g', 'u.gotra_id', '=', 'g.id')->leftJoin('native_villags as nv', 'u.native_village_id', '=', 'nv.id')
            ->leftJoin('all_categories as c', 'u.group_id', '=', 'c.id')->select('u.*', 'g.name as goatraName', 'c.name as GroupName', 'nv.name as nativeVillageName', 'wishlist.is_matrimony', 'wishlist.is_directory', DB::raw("CONCAT('" . env('AWS_STORAGE_URL') . "/', u.image) AS image"))->where('wishlist.is_matrimony', $request->is_matrimony)->where('wishlist.is_directory', $request->is_directory)->orderby('id', 'DESC');

        // if (!empty($request->is_matriomony)) {
        //     $query = $query->where('is_matriomony', $request->is_matriomony);
        // }

        $datalist = $query->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function trusteeList(Request $request, $id = null)
    {

        $awsStorageUrl = env('AWS_STORAGE_URL');
        $query = DB::table('users')
            ->select(
                'users.*',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'goatra.name as gotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName'
            )
            ->leftJoin('goatra', 'users.gotra_id', '=', 'goatra.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id');

        if ($id) {
            $data = $query->$query->where('users.name', '!=', '')->where('is_trustee', 1)->where('users.id', $id)->first();
            return response()->json([
                'result' => true,
                'message' => 'Data fetched successfully.',
                'data' => $data
            ], 200);
        }

        if (!empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
            $query->orWhere('users.email', 'like', '%' . $request->search . '%');
            $query->orWhere('users.phone', 'like', '%' . $request->search . '%');
        }

        $datalist = $query->where('users.name', '!=', '')->where('is_trustee', 1)->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function commiteeList(Request $request, $id = null)
    {

        $awsStorageUrl = env('AWS_STORAGE_URL');
        $query = DB::table('users')
            ->select(
                'users.*',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'goatra.name as gotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName'
            )
            ->leftJoin('goatra', 'users.gotra_id', '=', 'goatra.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id');

        if ($id) {
            $data = $query->where('users.name', '!=', '')->where('is_commitee', 1)->where('users.id', $id)->first();
            return response()->json([
                'result' => true,
                'message' => 'Data fetched successfully.',
                'data' => $data
            ], 200);
        }

        if (!empty($request->search)) {
            $query->where('users.name', 'like', '%' . $request->search . '%');
            $query->orWhere('users.email', 'like', '%' . $request->search . '%');
            $query->orWhere('users.phone', 'like', '%' . $request->search . '%');
        }

        $datalist = $query->where('users.name', '!=', '')->where('is_commitee', 1)->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }


    public function groupDetails($id)
    {
        $awsStorageUrl = env('AWS_STORAGE_URL');
        $query = DB::table('users')
            ->select(
                'users.*',
                DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                'goatra.name as gotraName',
                'groups.name as groupName',
                'nv.name as nativeVillageName'
            )
            ->leftJoin('goatra', 'users.gotra_id', '=', 'goatra.id')
            ->leftJoin('native_villags as nv', 'nv.id', '=', 'users.native_village_id')
            ->leftJoin('all_categories as groups', 'users.group_id', '=', 'groups.id');


        $datalist = $query->where('users.group_id', $id)->where('users.name', '!=', '')->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function newsList()
    {
        $query = DB::table('news')->orderBy('id', 'DESC');
        $datalist = $query->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }

    public function notificationList()
    {
        $query = DB::table('notifications')->orderBy('id', 'DESC');
        $datalist = $query->paginate(10);
        $data = $datalist->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data
        ], 200);
    }
}
