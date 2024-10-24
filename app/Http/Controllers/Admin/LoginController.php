<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\UserOtp;
use App\Helpers\CustomHelper;
use Laravel\Socialite\Facades\Socialite;
use Redirect;




class LoginController extends Controller
{
    protected $redirectTo = '/';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }



    public function loginWithGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle()
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();
            $is_user = Admin::where('email', $user->getEmail())->first();
            if (!empty($is_user)) {
                $saveUser = Admin::updateOrCreate([
                    'email' => $user->getEmail(),
                ], [
                    'google_id' => $user->getId(),
                ]);


                if ($is_user->status == 0) {
                    return redirect('admin')->with('alert-danger', 'Your Account id Inactive, contact the administrator.');
                }

                if ($is_user->status == 1) {
                    if ($is_user->is_approve == 0) {
                        return redirect('admin')->with('alert-danger', 'Your Account is Not Approved');
                    } else {
                        Auth::guard('admin')->loginUsingId($is_user->id);
                        return redirect('admin');
                    }
                }
            } else {
                $data = [];
                $data['email'] = $user->getEmail();
                $data['google_id'] = $user->getId();
                $data['phone'] = '';


                //return view('admin/register/index',$data);
                return \Redirect::route('admin.register', $data);

                //return redirect('/en/about', ['param'=>$param])
                // return redirect(url('admin/register',['data'=>$data]))->with('alert-danger', 'Email Not Exist');
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }





















    public function index(Request $request)
    {

        if (auth()->user()) {
            return redirect('admin');
            }
            
            $method = $request->method();
            
            if ($method == 'POST' || $method == 'post') {
            $rules = [];
            $rules['email'] = 'required';
            $rules['password'] = 'required';
            // $rules['otp'] = 'required';
            // $rules['token'] = 'required';

            $dd = $this->validate($request, $rules);


            // echo '<img src="'.$g->getURL('Ambitious Baba', 'ambitiousbaba.com', $secret).'" />';

            // die;  
            $credentials = $request->only('email', 'password');
            $users = Admin::where('email', $request->email)->first();
            if (!empty($users)) {
                if ($users->status == 0) {
                    return back()->with('alert-danger', 'Your Account id Inactive, contact the administrator.');
                }
                if ($users->status == 1) {
                    if ($users->is_approve == 0) {
                        return back()->with('alert-danger', 'Your Account is Not Approved');
                    } else {
                        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
                            $users->device_token = $request->token ?? '';
                            $users->login_time = date('Y-m-d h:i:s');
                            $users->is_logout = 0;
                            $users->save();
                            $request->session()->regenerate();

                            return redirect('admin');
                        } else {
                            return back()->with('alert-danger', 'Invalid Username and Password');
                        }
                    }
                }
            }else{
                return back()->with('alert-danger', 'Invalid Username and Password');
            }
        }


        return view('admin/login/index');
    }



    public function register(Request $request)
    {
        $data = [];
        $data['google_id'] = isset($request->google_id) ? $request->google_id : '';
        $data['email'] = isset($request->email) ? $request->email : '';
        $data['phone'] = isset($request->phone) ? $request->phone : '';
        $method = $request->method();

        if ($method == 'POST' || $method == 'post') {
            $rules = [];
            $rules['email'] = 'required|unique:admins';
            $rules['password'] = 'required';
            $rules['name'] = 'required';
            $rules['phone'] = 'required|unique:admins';
            $this->validate($request, $rules);
            $dbArray = [];
            $dbArray['phone'] = $request->phone;
            $dbArray['google_id'] = $request->google_id;
            $dbArray['name'] = $request->name;
            $dbArray['password'] = bcrypt($request->password);
            $dbArray['email'] = $request->email;
            $dbArray['username'] = $request->username ?? '';
            $dbArray['status'] = 0;
            $dbArray['is_approve'] = 0;
            $dbArray['role_id'] = 1;

            if (empty($request->google_id)) {
                $success1 = $this->send_otp($request->email);
                return view('admin.register.otp', $dbArray);
            } else {
                $success = Admin::create($dbArray);
                if ($success) {
                    return view('snippets.pendingforvarification', $dbArray);
                }
            }
        }

        return view('admin/register/index', $data);
    }


    public function verify_otp(Request $request)
    {
        $email = $request->email;
        $otp = $request->otp;
        $rules = [];
        $rules['email'] = 'required|unique:admins';
        $rules['password'] = 'required';
        $rules['name'] = 'required';
        $rules['phone'] = 'required|unique:admins';
        $rules['otp'] = 'required';
        $this->validate($request, $rules);
        if (!empty($email)) {
            $verify_otp  = UserOtp::where(['email' => $email, 'otp' => $otp])->first();
            if (!empty($verify_otp)) {

                $dbArray = [];
                $dbArray['phone'] = $request->phone;
                $dbArray['google_id'] = $request->google_id ?? '';
                $dbArray['name'] = $request->name;
                $dbArray['password'] = $request->password;
                $dbArray['email'] = $request->email;
                $dbArray['username'] = $request->username ?? '';
                $dbArray['status'] = 0;
                $dbArray['is_approve'] = 0;
                $dbArray['role_id'] = 1;


                $success = Admin::create($dbArray);
                if ($success) {
                    return view('snippets.pendingforvarification', $dbArray);
                }
            } else {
                return view('admin.register.otp', $request->toArray());
            }
        }
    }



    public function send_otp($email)
    {
        //$otp = rand(1111,9999);
        $otp = 1234;
        $time = date("Y-m-d H:i:s", strtotime('15 minutes'));
        UserOtp::updateOrcreate([
            'email' => $email
        ], [
            'otp' => $otp,
            'timestamp' => $time,
        ]);

        $to_email = $email;
        $from_email = 'satyasahoo.abc@gmail.com';
        $subject = 'OTP For Authentication- Krantikari';
        $email_data = [];
        $email_data['otp'] = $otp;
        $success = CustomHelper::sendEmail('mail', $email_data, $to = $to_email, $from_email, $replyTo = $from_email, $subject);

        return true;
    }












    public function logout(Request $request)
    {


        // auth()->user('admin')->logout();
        Auth::logout();

        $request->session()->invalidate();

        return redirect('/admin/login');
    }




    public function forgot(Request $request)
    {

        $data = [];

        $method = $request->method();

        if ($method == 'POST') {

            $rules = [];

            $rules['email'] = 'required|email';

            $this->validate($request, $rules);

            $msg_type = 'danger';

            $message = 'Please check your email';

            $email = $request->email;

            $user = Admin::where('email', $email)->first();

            $forgot_token = generateToken(40);

            if ($email) {
                $email = $request->email;
                $to_email = $email;
                $subject = 'Reset password - ' . env('APP_NAME');
                $ADMIN_EMAIL = config('custom.admin_email');
                $from_email = $ADMIN_EMAIL;
                $reset_link = '<a href="' . url('admin/reset?t=' . $forgot_token) . '">Click here to reset password</a>';

                $email_data = [];
                $email_data['reset_link'] = $reset_link;

                $is_mail = CustomHelper::sendEmail('emails.reset_password', $email_data, $to = $to_email, $from_email, $replyTo = $from_email, $subject);

                if ($is_mail && !empty($user)) {
                    $user->forgot_token = $forgot_token;
                    $user->save();
                    $msg_type = 'success';
                    $message = 'Reset password link has been sent to your email, please check.';
                }

                return redirect(url('admin/forgot-password'))->with('alert-' . $msg_type, $message);
            }
        }

        return view('admin.login.forgot', $data);
    }



    public function reset(Request $request)
    {

        $data = [];

        $isVerified = false;
        $isValidToken = false;

        $token = (isset($request->t)) ? $request->t : '';

        if (!empty($token)) {

            $user = Admin::where('forgot_token', $token)->first();

            if (!empty($user)) {

                $isValidToken = true;

                $method = $request->method();

                if ($method == 'POST') {

                    $rules = [];

                    $rules['email'] = 'required|email';
                    $rules['password'] = 'required|min:6';
                    $rules['confirm_password'] = 'required|same:password';

                    $this->validate($request, $rules);

                    $msg_type = 'danger';

                    $message = 'Please check your email';

                    $email = $request->email;

                    $user = Admin::where('email', $email)->first();

                    $referer = (isset($user->referer)) ? $user->referer : '';

                    $forgot_token = generateToken(40);

                    if ($user->email == $email) {
                        $password = bcrypt($request->password);
                        $user->password = $password;
                        $user->forgot_token = '';
                        $isSaved = $user->save();
                        if ($isSaved) {
                            $msg_type = 'success';
                            $message = 'Your password has been updated successfully, please login.';
                        }

                        if (!empty($referer)) {
                            return redirect(url('admin/login'))->with('alert-' . $msg_type, $message);
                        }

                        return redirect(url('admin/login'))->with('alert-' . $msg_type, $message);
                    }
                }
            }
        }

        $data['isVerified'] = $isVerified;
        $data['isValidToken'] = $isValidToken;


        return view('admin.login.reset', $data);
    }








    public function logout_from_all_device(Request $request)
    {
        $sessions = glob(storage_path("framework/sessions/*"));
        foreach ($sessions as $file) {
            unlink($file);
        }
        return back()->with('alert-success', 'All Device Logout successfully');
    }








    /*End of controller */
}
