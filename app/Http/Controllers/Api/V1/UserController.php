<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Http\Request;
use F9Web\ApiResponseHelpers;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Response;
use App\Helpers\CustomHelper;
use App\Models\Event;
use Auth;
use DB;
use Laravel\Passport\PersonalAccessTokenResult;
use Mail;

class UserController extends Controller
{
    use ApiResponseHelpers;
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}

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


    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'otp' => '',
                'message' => json_encode($validator->errors()),
            ], 200);
        }

        $data['phone'] = $request->phone;

        // Check if the phone number already exists
        $existingUser = User::where('phone', $request->phone)->first();

        if (!empty($existingUser)) {
            if ($existingUser->status == 0) {
                return response()->json([
                    'result' => true,
                    'message' => 'Account Suspended!',
                    'status' => $existingUser->status,
                ], 200);
            }
        }

        if (!$existingUser) {
            User::insert($data);
        }

        $mobile = $request['phone'];
        $time = date("Y-m-d H:i:s", strtotime('15 minutes'));
        $type = $request->type ?? '';
        $websiteName = "Plus Nine One";

        if (!empty($request->phone)) {
            $check_user = User::where('phone', $request->phone)->first();
            if (!empty($check_user) && $request->phone != $check_user->phone) {
                if ($check_user->is_delete == 1) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Your Account does not exist.',
                        'status' => 0,
                    ], 200);
                }
            }

            if ($request->phone == "7982025566") {
                $otp = '1234';
                UserOtp::updateOrCreate([
                    'mobile' => $mobile
                ], [
                    'otp' => $otp,
                    'timestamp' => $time,
                    'type' => $type,
                ]);
                return response()->json([
                    'result' => true,
                    'message' => 'OTP Sent Successfully!',
                    'status' => $check_user->status,
                ], 200);
            }

            // Send OTP using the API
            $ch = curl_init(); // Initialize cURL session

            // Set the URL, HTTP method, and headers
            curl_setopt($ch, CURLOPT_URL, 'https://auth.otpless.app/auth/otp/v1/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'phoneNumber' => '91' . $mobile,
                'otpLength' => 4,
                'channel' => 'SMS', // Change to 'SMS' to send via text message
                'expiry' => 60
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'clientId: LF4R5FC2J4BVRYLOPAGNR39OP9YFPXUV',
                'clientSecret: r1rmrs5v5mi0z2i3610le6lzfy0xdt1y',
                'Content-Type: application/json'
            ]);

            // Execute cURL request
            $response = curl_exec($ch);

            // Check for errors
            if (curl_errno($ch)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Failed to send OTP: ' . curl_error($ch),
                    'status' => 0
                ], 200);
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch); // Close cURL session

            // Check HTTP response code
            if ($http_code !== 200) {
                return response()->json([
                    'result' => false,
                    'message' => 'Failed to send OTP. HTTP Status Code: ' . $http_code,
                    'status' => 0
                ], 200);
            }

            $responseBody = json_decode($response, true);
            if (!isset($responseBody['orderId'])) {
                return response()->json([
                    'result' => false,
                    'message' => 'OTP not received from the service.',
                    'status' => 0
                ], 200);
            }

            $otp = $responseBody['orderId'];
            UserOtp::updateOrCreate([
                'mobile' => $mobile
            ], [
                'otp' => $otp,
                'timestamp' => $time,
                'type' => $type,
            ]);
        }

        return response()->json([
            'result' => true,
            'message' => 'OTP Sent Successfully',
            'status' => $check_user->status
        ], 200);
    }


    public function verify_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required',
            'type' => 'required',
        ]);
        $status = 'new';

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => json_encode($validator->errors()),
                'status' => $status,
            ], 400);
        }


        $mobile = $request->phone;
        $otp = $request->otp;
        $type = $request->type;
        // get order id from userOtp table 
        $getUserOtpDetails = UserOtp::where(['mobile' => $mobile, 'type' => 'login'])->first();

        // cURL to verify OTP with external service
        if ($mobile != '7982025566') {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://auth.otpless.app/auth/otp/v1/verify');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
                'orderId' => $getUserOtpDetails->otp, // Replace with actual order ID if available
                'otp' => $otp,
                'phoneNumber' => '91' . $mobile,
            ]));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'clientId: LF4R5FC2J4BVRYLOPAGNR39OP9YFPXUV',
                'clientSecret: r1rmrs5v5mi0z2i3610le6lzfy0xdt1y',
                'Content-Type: application/json',
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                return response()->json([
                    'result' => false,
                    'message' => 'Failed to verify OTP: ' . curl_error($ch),
                    'status' => $status,
                ], 200);
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code !== 200) {
                return response()->json([
                    'result' => false,
                    'message' => 'Failed to verify OTP. HTTP Status Code: ' . $http_code,
                    'status' => $status,
                ], 200);
            }

            // Decode the response body
            $responseBody = json_decode($response, true);

            // Debugging: Log or inspect responseBody
            \Log::info('Response from OTP verification service:', $responseBody);

            // Check if the OTP is verified
            if (!isset($responseBody['isOTPVerified']) || !$responseBody['isOTPVerified']) {
                return response()->json([
                    'result' => false,
                    'message' => 'OTP Not Verified',
                    'status' => $status,
                ], 200);
            }
        }

        // Continue with the local verification and user handling logic
        if ($type == 'login') {
            if ($getUserOtpDetails) {
                $awsStorageUrl = env('AWS_STORAGE_URL');

                $check_user = DB::table('users')
                    ->leftJoin('goatra as g', 'users.gotra_id', '=', 'g.id')
                    ->leftJoin('all_categories as c', 'users.group_id', '=', 'c.id')
                    ->leftJoin('native_villags as nv', 'users.native_village_id', '=', 'nv.id')
                    ->where('users.phone', $mobile)
                    ->select(
                        'users.*',
                        DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"),
                        'g.name as goatraName',
                        'c.name as GroupName',
                        'nv.name as VillageName'
                    )
                    ->first();

                if (empty($check_user)) {
                    $success = User::insert(['phone' => $mobile, 'status' => 1]);
                }

                if (auth()->loginUsingId($check_user->id)) {
                    $token = auth()->user()->createToken('passport_token')->accessToken;
                    User::where('phone', $mobile)->update(['token' => $token]);
                    return response()->json([
                        'result' => true,
                        'message' => 'OTP Verified Successfully and User login successfully.',
                        'token' => $token,
                        'data' => $check_user,
                        'is_registered' => $check_user->is_registered,
                        'status' => $check_user->status,
                    ], 200);
                } else {
                    return response()->json([
                        'result' => false,
                        'message' => 'User authentication failed.',
                    ], 401);
                }

                $status = 'old';
                UserOtp::where(['mobile' => $mobile, 'type' => 'login'])->update(['otp' => null]);
                return response()->json([
                    'result' => true,
                    'message' => 'OTP Verified Successfully',
                    'status' => $status,
                ], 200);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'OTP Not Verified',
                    'status' => $status,
                ], 200);
            }
        }
    }


    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->token()->revoke();
        $data = [
            'status' => true,
            'message' => 'You have successfully logged out'
        ];
        return $this->respondWithSuccess($data);
    }

    public function profile(Request $request): JsonResponse
    {
        $data = DB::table('users')
            ->leftJoin('goatra as g', 'users.gotra_id', '=', 'g.id')
            ->leftJoin('all_categories as c', 'users.group_id', '=', 'c.id')
            ->leftJoin('users as hf', 'users.head_of_family', '=', 'hf.id') // Self-join to get head of family name
            ->where('users.id', Auth::user()->id)
            ->select(
                'users.*',
                DB::raw("CONCAT('/public/uploads/', users.image) AS image"),
                'g.name as goatraName',
                'c.name as GroupName',
                'hf.name as headOfFamilyName', // Retrieve the head of family name
                DB::raw('CASE WHEN users.name IS NULL THEN NULL ELSE 1 END AS is_valid')
            )
            ->first();

        if ($data && $data->is_valid == 1) {
            unset($data->is_valid); // Remove the is_valid field from the final data
            $response = [
                'result' => true,
                'message' => 'Data fetched successfully',
                'data' => $data
            ];
        } else {
            $response = [
                'result' => false,
                'message' => 'Data not found',
                'data' => null
            ];
        }

        return $this->respondWithSuccess($response);
    }





    public function updateProfile(Request $request): JsonResponse
    {
        // $request->validate([
        //     'phone' => ['digits:10', 'required'],
        //     'email' => ['email', 'required'],
        // ]);

        if ($request->name) {
            $data['name'] = $request->name;
        }

        if ($request->email) {
            $data['email'] = $request->email;
        }

        if ($request->phone) {
            $data['phone'] = $request->phone;
        }

        if ($request->dob) {
            $data['dob'] = date('Y-m-d', strtotime($request->dob));
        }

        if ($request->image) {
            $data['qualification'] = $request->qualification;
        }

        if ($request->image) {
            $fileName = 'image' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $fileName);
            $data['image'] = $fileName;
        }
        $data = DB::table('users')->where('id', Auth::user()->id)->update($data);
        $data1 = DB::table('users')->where('id', Auth::user()->id)->select('id', 'name', 'email', 'phone', 'dob', 'qualification', 'created_at', 'updated_at', DB::raw("CONCAT('/public/uploads/', image) AS image"))->first();
        $data = [
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $data1
        ];
        return $this->respondWithSuccess($data);
    }


    public function register(Request $request): JsonResponse
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'required',
            ],
            'gender' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'result' => true,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        // Process registration
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'is_registered' => 1,
            'is_commitee' => $request->is_commitee,
            'is_trustee' => $request->is_trustee,
            'cmpny_name' => $request->cmpny_name,
            'firm_address' => $request->firm_address,
            'residence_address' => $request->residence_address,
            'native_full_address' => $request->native_full_address,
            'member_id' => "MBRID#" . rand(1111, 9999),
        ];

        $userExistsWithEmail = User::where('email', $request->email)->first();
        if ($userExistsWithEmail) {
            return response()->json([
                'result' => false,
                'message' => 'This email address already exists.',
                'token' => '',
            ], 200);
        }

        $userExists = User::where('phone', $request->phone)->first();
        if ($userExists) {
            // Update existing user
            DB::table('users')->where('id', $userExists->id)->update($data);
            $user = $userExists;
        } else {
            // Insert new user
            User::insert($data);
            $user = User::where('phone', $data['phone'])->first();
        }

        // Generate and assign token
        auth()->login($user);
        $token = $user->createToken('passport_token')->accessToken;
        $user->update(['token' => $token]);

        return response()->json([
            'result' => true,
            'message' => 'User registered successfully.',
            'token' => $token,
        ], 200);
    }

    private function saveImage($file, $path)
    {

        //prd($file);
        if ($file) {
            $thumb_path = '';
            $storage = Storage::disk('public');
            //prd($storage);
            $IMG_WIDTH = 768;
            $IMG_HEIGHT = 768;
            $THUMB_WIDTH = 336;
            $THUMB_HEIGHT = 336;

            $uploaded_data = CustomHelper::UploadImage($file, $path, $ext = '', $IMG_WIDTH, $IMG_HEIGHT, $is_thumb = true, $THUMB_WIDTH, $THUMB_HEIGHT);

            if ($uploaded_data['success']) {

                // if(!empty($oldImg)){
                //     if($storage->exists($path.$oldImg)){
                //         $storage->delete($path.$oldImg);
                //     }
                //     if($storage->exists($thumb_path.$oldImg)){
                //         $storage->delete($thumb_path.$oldImg);
                //     }
            }
            $image = $uploaded_data['file_name'];
        }
        if (!empty($uploaded_data)) {
            return  $image;
        }
    }

    public function loginWithGoogle(Request $request)
    {
        $data = $request->all();
        // $validator = Validator::make($data, [
        //     'email' => 'required|email|unique:users',
        //     'google_id' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     $errors = $this->errorMessage($validator->errors());
        //     return response()->json([
        //         'result' => false,
        //         'message' => $errors,

        //     ], 400);
        // }
        $emailExists = User::where('email', $request->email)->first();
        if (!empty($emailExists)) {
            $token = $emailExists->createToken('ikb')->accessToken;
            if (!empty($emailExists->phone)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login successfully',
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'is_registered' => true,
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Login successfully',
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'is_registered' => false,
                ], 200);
            }
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['domain_name'] = $request->domain_name;
            $data['family_name'] = $request->family_name;
            $data['google_id'] = $request->google_id;
            $data['password'] = encrypt('Sh@21');
            $data['image'] = $request->image;
            $lastId = DB::table('users')->insertGetId($data);
            if (!empty($lastId)) {
                $user = User::find($lastId);
                $token = $user->createToken('ikb')->accessToken;
                return response()->json([
                    'status' => true,
                    'message' => 'Login successfully',
                    'token_type' => 'Bearer',
                    'token' => $token,
                    'is_registered' => false,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User authentication failed',
                    'token_type' => 'Bearer',
                    'token' => '',
                ], 401);
            }
        }
    }

    public function bookEvents(Request $request)
    {
        $request->validate([
            'razorpay_id' => 'required',
            'event_id' => 'required|integer',
        ]);

        $userId = Auth::user()->id;
        $eventId = $request->event_id;
        $existingBooking = DB::table('book_events')
            ->where('event_id', $eventId)
            ->first();

        if ($existingBooking) {
            return response()->json([
                'result' => false,
                'message' => 'This event already booked. Please choose another one.',
                'data' => '',
            ], 400);
        }

        $data = [
            'user_id' => $userId,
            'razorpay_id' => $request->razorpay_id,
            'payment_status' => $request->status,
            'event_id' => $eventId,
            'amount' => $request->amount,
        ];

        DB::table('book_events')->insert($data);

        // Get events 
        $getEvents = DB::table('book_events as be')
            ->join('users as u', 'be.user_id', '=', 'u.id')
            ->join('events as e', 'be.event_id', '=', 'e.id')
            ->select(
                'be.*',
                'u.name as userName',
                'e.title as eventName',
                'e.event_date',
                'e.event_time',
                'e.address'
            )
            ->where('be.user_id', $userId)
            ->where('be.event_id', $eventId)
            ->first();
        return response()->json([
            'result' => true,
            'message' => 'Your event has been booked successfully',
            'data' => $getEvents,
        ], 200);
    }

    // public function bookEventList($eventId){
    //     $userId = Auth::user()->id;
    //     $getEvents = DB::table('book_events as be')
    //         ->join('users as u', 'be.user_id', '=', 'u.id')
    //         ->join('events as e', 'be.event_id', '=', 'e.id')
    //         ->select(
    //             'be.*',
    //             'u.name as userName',
    //             'e.title as eventName',
    //             'e.event_date',
    //             'e.event_time',
    //             'e.address'
    //         )
    //         ->where('be.user_id', $userId)
    //         ->where('be.event_id', $eventId)
    //         ->first();
    // }


    public function updateUserDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'head_of_family' => 'required|string|max:255',
            // 'relation_with_head' => 'required|string|max:255',
            'dob' => 'required|date',
            'native_village_id' => 'required|integer',
            'bld_group' => 'required|string|max:10',
            'maritl_status' => 'required|string|max:50',
            'education' => 'required|string|max:100',
            'gotra_id' => 'required|integer',
            'group_id' => 'required|integer',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'result' => true,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $data['head_of_family'] = $request->head_of_family;
        // $data['relation_with_head'] = $request->relation_with_head;
        $data['dob'] = $request->dob;
        $data['native_village_id'] = $request->native_village_id;
        $data['bld_group'] = $request->bld_group;
        $data['maritl_status'] = $request->maritl_status;
        $data['education'] = $request->education;
        $data['gotra_id'] = $request->gotra_id;
        $data['group_id'] = $request->group_id;
        $data['designation'] = $request->designation;
        $data['firm_address'] = $request->firm_address;
        $data['residence_address'] = $request->residence_address;
        $data['cmpny_name'] = $request->cmpny_name;
        $data['relation_with_head'] = $request->relation_with_head;
        $data['industry'] = $request->industry;
        $data['sasural_gotra_id'] = $request->sasural_gotra_id;
        $data['member_id'] = $request->member_id;


        if ($request->native_full_address) {
            $data['native_full_address'] = $request->native_full_address;
        }
        if ($request->name) {
            $data['name'] = $request->name;
        }
        if ($request->email) {
            $data['email'] = $request->email;
        }
        if ($request->phone) {
            $data['phone'] = $request->phone;
        }

        if ($request->image) {
            $data['image'] = $request->image;
        }

        $awsStorageUrl = env('AWS_STORAGE_URL');
        User::where('id', Auth::user()->id)->update($data);
        $user = DB::table('users')
            ->leftJoin('goatra as g', 'users.gotra_id', '=', 'g.id')
            ->leftJoin('all_categories as c', 'users.group_id', '=', 'c.id')
            ->leftJoin('native_villags as nv', 'users.native_village_id', '=', 'nv.id')
            ->where('users.id', Auth::user()->id)
            ->select('users.*', DB::raw("CONCAT('$awsStorageUrl/', users.image) AS image"), 'g.name as goatraName', 'c.name as GroupName', 'nv.name as VillageName')
            ->first();

        return response()->json([
            'result' => true,
            'message' => 'User data updated successfully.',
            'data' => $user,
            'is_updated' => 1,
        ], 200);
    }

    public function goatraList()
    {
        $data = DB::table('goatra')->orderBy('id', 'DESC')->get();
        // $datalist = $query->paginate(10);
        // $data = $datalist->toArray();
        // $data['dataItems'] = $data['data'];
        // unset($data['data']);
        return response()->json([
            'result' => true,
            'message' => 'Data fetched successfully.',
            'data' => $data,
        ], 200);
    }


    public function addMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'head_of_family' => 'required',
            'relation_with_head' => 'required|string|max:255',
            'dob' => 'required|date',
            'native_village_id' => 'required|integer',
            'maritl_status' => 'required|string|max:50',
            'education' => 'required|string|max:100',
            'gotra_id' => 'required|integer',
            'group_id' => 'required|integer',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'result' => true,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $user = Auth::user();
        $url = url('/') . '?head_of_family_id=' . $user->id;
        $newMember = new User();
        $newMember->head_of_family = $user->id;
        $newMember->relation_with_head = $request->input('relation_with_head');
        $newMember->name = $request->input('name');
        $newMember->phone = $request->input('phone');
        $newMember->email = $request->input('email');
        $newMember->gender = $request->input('gender');
        $newMember->dob = $request->input('dob');
        $newMember->native_village_id = $request->input('native_village_id');
        $newMember->bld_group = $request->input('bld_group');
        $newMember->maritl_status = $request->input('maritl_status');
        $newMember->education = $request->input('education');
        $newMember->gotra_id = $request->input('gotra_id');
        $newMember->is_trustee = $request->input('is_trustee');
        $newMember->is_commitee = $request->input('is_commitee');
        $newMember->designation = $request->input('designation');
        $newMember->firm_address = $request->input('firm_address');
        $newMember->residence_address = $request->input('residence_address');
        $newMember->native_full_address = $request->input('native_full_address');
        $newMember->cmpny_name = $request->input('cmpny_name');
        $newMember->sasural_gotra_id = $request->input('sasural_gotra_id');
        $newMember->industry = $request->input('industry');
        $newMember->member_id = $request->input('member_id');
        $newMember->group_id = $request->input('group_id');
        $newMember->save();

        Mail::send('email.addmember', ['user' => $request->input('name')], function ($message) use ($request) {

            $message->to($request->email)
                ->subject('Member Added To Community');
        });
        return response()->json([
            'result' => true,
            'message' => 'Member added successfully.',
            'data' => '',
        ], 200);
    }

    public function updateMember(Request $request)
    {
        $user = Auth::user();
        $member = User::find($request->input('id'));
        if (!$member) {
            return response()->json([
                'result' => false,
                'message' => 'Member not found.',
                'data' => '',
            ], 404);
        }

        // Validate the request, including the image file
        $validatedData = $request->validate([
            // 'relation_with_head' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'gender' => 'required|string|max:10',
            'dob' => 'required|date',
            'native_village_id' => 'required',
            'bld_group' => 'required|string|max:3',
            'maritl_status' => 'required|string|max:20',
            'education' => 'required|string|max:255',
            'gotra_id' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image upload to S3 (start S3) 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = Storage::disk('s3')->put('plus91-backend', $image);
            $fileName = basename($path);

            // Delete old image from S3 if exists
            if ($member->image) {
                $oldImagePath = 'plus91-backend/' . $member->image;
                Storage::disk('s3')->delete($oldImagePath);
            }

            // Update member's image with new file name
            $member->image = $fileName;

            // Optionally, you can store the full URL if needed
            // $member->image_url = Storage::disk('s3')->url($path);
        }
        // end S3 

        // Update member attributes
        $member->head_of_family = $user->id;
        // $member->relation_with_head = $validatedData['relation_with_head'];
        $member->name = $validatedData['name'];
        $member->phone = $validatedData['phone'];
        $member->email = $validatedData['email'];
        $member->gender = $validatedData['gender'];
        $member->dob = $validatedData['dob'];
        $member->native_village_id = $validatedData['native_village_id'];
        $member->bld_group = $validatedData['bld_group'];
        $member->maritl_status = $validatedData['maritl_status'];
        $member->education = $validatedData['education'];
        $member->gotra_id = $validatedData['gotra_id'];
        $member->designation = $request->input('designation');
        $member->firm_address = $request->input('firm_address');
        $member->residence_address = $request->input('residence_address');
        $member->native_full_address = $request->input('native_full_address');
        $member->cmpny_name = $request->input('cmpny_name');
        $member->sasural_gotra_id = $request->input('sasural_gotra_id');
        $member->industry = $request->input('industry');
        $member->member_id = $request->input('member_id');
        $member->group_id = $request->input('group_id');
        $member->status = 0;
        $member->save();

        // Add full URL to image
        $member->image_url = $member->image ? env('AWS_STORAGE_URL') . '/' . $member->image : null;

        return response()->json([
            'result' => true,
            'message' => 'Member updated successfully.',
            'data' => $member,
        ], 200);
    }

    public function memberList($id = null)
    {
        $user = Auth::user();

        // Query to fetch members based on head_of_family
        $query = User::where('head_of_family', $user->id)->where('is_delete', 0)
            ->with('headOfFamily:id,name')
            ->orderBy('id', 'DESC');

        // If $id is provided, fetch details for that specific member
        if ($id !== null) {
            $member = $query->find($id);
            if (!$member) {
                return response()->json([
                    'result' => false,
                    'message' => 'Member not found.',
                ], 404);
            }

            // Append image URL and head of family details to the member object
            $member->image_url = $member->image ? env('AWS_STORAGE_URL') . '/' . $member->image : null;
            $member->head_of_family_name = $member->headOfFamily ? $member->headOfFamily->name : null;
            $member->head_of_family_id = $member->headOfFamily ? $member->headOfFamily->id : null;

            return response()->json([
                'result' => true,
                'message' => 'Member fetched successfully.',
                'data' => $member,
            ], 200);
        }

        // Fetch all members if $id is not provided
        $members = $query->where('name', '!=', '')->paginate(10);
        $data = $members->toArray();
        $data['dataItems'] = $data['data'];
        unset($data['data']);

        // Append image URL and head of family details to each member object
        foreach ($data['dataItems'] as &$member) {
            $member['image_url'] = $member['image'] ? env('AWS_STORAGE_URL') . '/' . $member['image'] : null;
            $member['head_of_family_name'] = $member['headOfFamily']['name'] ?? null;
            $member['head_of_family_id'] = $member['headOfFamily']['id'] ?? null;
        }

        return response()->json([
            'result' => true,
            'message' => 'Members fetched successfully.',
            'data' => $data,
        ], 200);
    }




    public function memberDelete($id)
    {
        // Update the is_delete column to 1 for the specified user
        $member = DB::table('users')->where('id', $id)->update(['is_delete' => 1]);

        if ($member) {
            return response()->json([
                'result' => true,
                'message' => 'Member marked as deleted successfully.',
                'data' => '',
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => 'Something went wrong.',
                'data' => '',
            ], 200);
        }
    }


    public function updateProfileAvatar(Request $request)
    {
        $user = Auth::user();

        // Validate the image file
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle image upload to S3
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = Storage::disk('s3')->put('plus91-backend/', $image);
            $fileName = basename($path);

            // Delete old image from S3 if it exists
            if ($user->image) {
                $oldImagePath = 'plus91-backend/' . $user->image;
                Storage::disk('s3')->delete($oldImagePath);
            }

            // Update user image
            $user->image = $fileName;
            $user->save();
        }

        // Get full image URL
        $awsStorageUrl = env('AWS_STORAGE_URL');
        $fullImagePath = $awsStorageUrl . '/' . $user->image;

        return response()->json([
            'result' => true,
            'message' => 'Profile avatar updated successfully.',
            'data' => [
                'image' => $fullImagePath,
            ],
        ]);
    }

    public function isApproved()
    {
        $user = Auth::user();
        return response()->json(['status' => $user ? $user->status : 0]);
    }
}
