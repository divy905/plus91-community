<?php


namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class PassportController extends Controller
{
    
    public function send_otp(Request $request)
    {
        $validator =  Validator::make($request->all(), [
         'mobile_number' => 'required|digits:10'
     ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
                'is_new' => false

            ]);
        }

        $mobile_number = $request->input('mobile_number');
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (empty($user)) {
            $user = User::create([
                'mobile_number' => $mobile_number,
                'auth_medium' => 1
            ]);
        }
        $otp = 1234;
        $user->otp = $otp;
        $user->otp_expiration = Carbon::now()->addMinutes(10);
        $user->save();
        
        
        return response()->json([
            'success' => true,
            'message' => 'OTP Sent',
            'is_new' => empty($user->name)

        ], 200);
    }



    public function register(Request $request)
    {
        $input = $request->only(['name', 'email', 'password']);
        $validate_data = [
            'name' => 'required|string|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ]);
        }
        
        $mobile_number = $request->input('mobile_number');
        $user = User::where(['mobile_number' => $mobile_number])->first();
        if (empty($user)) {
            $user = User::create([
                'mobile_number' => $mobile_number,
                'auth_medium' => 1
            ]);
        }
        $otp = 9999;
        $user->otp = $otp;
        $user->otp_expiration = Carbon::now()->addMinutes(10);
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'OTP Sent Successfully'
        ], 200);
    }
    
    /**
     * Login user.
     *
     * @return json
     */
    public function login(Request $request)
    {
        $input = $request->only(['email', 'password']);

        $validate_data = [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        // authentication attempt
        if (auth()->attempt($input)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;
            
            return response()->json([
                'success' => true,
                'message' => 'User login succesfully, Use token to authenticate.',
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User authentication failed.'
            ], 401);
        }
    }

    /**
     * Access method to authenticate.
     *
     * @return json
     */
    public function userDetail()
    {
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully.',
            'data' => auth()->user()
        ], 200);
    }

    /**
     * Logout user.
     *
     * @return json
     */
    public function logout()
    {
        $access_token = auth()->user()->token();

        // logout from only current device
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);

        // use this method to logout from all devices
        // $refreshTokenRepository = app(RefreshTokenRepository::class);
        // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($$access_token->id);

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }
}