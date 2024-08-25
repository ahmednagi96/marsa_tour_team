<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CheckPhoneRequest;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterUser1Request;
use App\Http\Requests\API\RegisterUser2Request;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Interfaces\SendSmsInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private $otp;

    public function __construct(SendSmsInterface $otp){
        $this->otp=$otp;
    }
    public function registerUser1(RegisterUser1Request $request)
{
    try {
        $data = $request->validated();
        $user = User::create([
            'phone' =>$data['phone'],
            'country_code' => $data['country_code'],
        ]);
        if ($user) {
            return sendResponse(201, 'User Registered Successfully', [
                'id' => $user->id,
                'phone' => $user->phone,
                'country_code' => $user->country_code,
            ]);
        }
        return sendResponse(404, 'Failed to register account, please sign up again!',null);
        }
        catch (\Exception $ex) {

            return sendResponse(500, 'Internal Server Error',null);
        }
}


    public function registerUser2(User $user, RegisterUser2Request $request)
    {
        try {
            $data = $request->validated();
            $user->update([
                'name' => $data['name'],
                'country' => $data['country'],
                'email' => $data['email'],
                'photo' => uploadPhoto('photo', 'users', 'image', $request),
                'password'=>bcrypt($data['password']),
                'code' => rand(1000, 9999),
                'expired_at' => now()->addMinutes(5),
            ]);
            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'country' => $user->country,
                'photo' =>!$request->hasFile('photo')? null: BASEURLPHOTO . $user->photo,
                'fcm_token' => $user->fcm_token,
                'code'=>$user->code,
            ];
            return SendResponse(201, 'Code sent successfully to 0' . $user->phone, $data);
        } catch (\Exception $e) {
            // return $e;
            return SendResponse(500, 'Internal server error.', []);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $loginInput = $request->input('login'); // Assuming 'login' field can be either email or phone
            $password = $request->input('password');
            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                $credentials = ['email' => $loginInput, 'password' => $password];
                $user_check = User::where('email', $loginInput)
                ->first();
            } else {
                $phoneWithoutFirstChar = ltrim($loginInput, '0');
                $credentials = ['phone' => $phoneWithoutFirstChar, 'password' => $password];
                $user_check = User::where('phone', $phoneWithoutFirstChar)
                ->first();
            }
            if ($user_check) {
                if (!is_null($user_check->code) || !is_null($user_check->expired_at)) {
                    return sendResponse(401, 'Validation Error..', ['phone' => ['Please confirm your phone first, enter the code we sent']]);
                }
                if (Auth::guard('web')->attempt($credentials)) {
                    $user = Auth::user();
                    $token = $user->createToken($user->name, ['*'], now()->addMonth())->plainTextToken;
                    if ($request->has('fcm_token')) {
                        $user->fcm_token = $request->input('fcm_token');
                        $user->save();
                    }
                    $data = [
                        'id' => $user->id,
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'email' => $user->email,
                        'country' => $user->country,
                        'photo' => is_null(BASEURLPHOTO . $user->photo) ? null : BASEURLPHOTO . $user->photo,
                        'fcm_token' => $user->fcm_token,
                        'token' => $token,
                        'token_type' => "bearer"
                    ];
                    return sendResponse(200, 'User Logged Successfully..', $data);
                }
            }
            return sendResponse(401, 'Unauthenticated Error...', []);
        } catch (\Exception $e) {
            return sendResponse(500, 'Internal Server Error.', []);
        }
    }
    public function refreshToken(Request $request){
        $user = auth()->user();
        return sendResponse(200,'token refresh successfully',[
            'token'=>$request->user()->createToken($user->name,['*'],now()->addMonth())->plainTextToken,
            'token_type'=>"bearer"
        ]);
    }
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return SendResponse(200, 'user logged out Successfully', []);
        } catch (\Exception $e) {
            return SendResponse(500, 'internal serever Error,,', []);
        }
    }

    public function forgotPassword(CheckPhoneRequest $request){
        try{
            $data=$request->validated();
            if(DB::table('users')->where('phone',$data['phone'])->doesntExist()){
                return sendResponse(200,'User Not Found',[]);
            }
            $code=rand(1000,9999);
            $updateUser=DB::table('users')->where('phone',$data['phone'])->update([
                'code'=>$code,
                'expired_at'=>now()->addMinutes(5)
            ]);
            if($updateUser){
                // $this->otp->SendSms('966570869472','Rmoz159@0',"966".$data['phone'],"فرحتي",'كود تفعيلك في ابليكشن فرحتي:'.$code);
                return sendResponse(200,'code sent successfully to 0'.$data['phone'],[
                    'phone'=>$data['phone'],
                    'code'=>$code,

                ]);
            }
        }catch(\Exception $e){
            return sendResponse(500,'internal server error..',[]);
        }
    }
    public function verifyPhoneCode(User $user,Request $request){
        try{
            $validator = Validator::make(
                $request->all(),
                 [
                    'code' => 'required|exists:users,code',
                    ]
                ,['code.exists'=>'this code is wrong, write it again!!'],[],
            );
            if ($validator->fails()) {
                return SendResponse(422, 'validation Error..', $validator->errors());
            }
            if($user->code !=$request->input('code')){
                return sendResponse(422,'validation Error..',['code'=>['code is wrong, write it again']]);
            }
            if($user->expired_at < now()){
                return sendResponse(422,'validation Error..',['code'=>['Time of code is expired ,please resend code again!']]);
            }
            $user->update([
                'code'=>null,
                'expired_at'=>null
            ]);
                $token = $user->createToken($user->name,['*'],now()->addMonth())->plainTextToken;
            return sendResponse(200,'verification code is correct',[
                    'id'=>$user->id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'country'=>$user->country,
                    'photo'=>is_null($user->photo)?null:BASEURLPHOTO.$user->photo,
                    'fcm_token'=>$user->fcm_token,
                    'token' => $token,
                    'token_type'=>"bearer"
            ]);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error..',[]);
        }
    }
    public function verifyCodeForPassword(User $user,Request $request){
        try{
            $validator = Validator::make(
                $request->all(),
                 [
                    'code' => 'required|exists:users,code',
                    ]
                ,['code.exists'=>'this code is wrong, write it again!!'],[],
            );
            if ($validator->fails()) {
                return SendResponse(422, 'validation Error..', $validator->errors());
            }
            if($user->code !=$request->input('code')){
                return sendResponse(422,'validation Error..',['code'=>['code is wrong, write it again']]);
            }
            if($user->expired_at < now()){
                return sendResponse(422,'validation Error..',['code'=>['Time of code is expired ,please resend code again!']]);
            }

            return sendResponse(200,'verification code is correct',[
                    'phone' => $user->phone,
            ]);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error..',[]);
        }
    }
    public function resetPassword(User $user,Request $request){
        try{
            $validator = Validator::make(
                $request->all(),
                ['code' => 'required|exists:users,code','password' => ['required','confirmed',Password::min(8)->numbers()->uncompromised()]],[],[],
            );
            if ($validator->fails()) {
                return SendResponse(422, 'validation Error..', $validator->errors());
            }
            if($user->code !=$request->input('code')){
                return sendResponse(422,'validation Error..',['code'=>['code is wrong, write it again']]);
            }
            if($user->expired_at < now()){
                return sendResponse(422,'validation Error..',['code'=>['Time of code is expired ,please resend code again!']]);
            }
            if(Hash::check($request->input('password'),$user->password)){
                return sendResponse(404,'you can\'t use old password as new password',[]);
            }
            $user->update([
                'password'=>bcrypt($request->input('password')),
                'code'=>null,
                'expired_at'=>null
            ]);
            $token=$user->createToken($user->name)->plainTextToken;
            Auth::guard('web')->login($user,true);
            $data = [
                'id'=>$user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'country'=>$user->country,
                'photo' =>!$request->hasFile('photo')? null: BASEURLPHOTO . $user->photo,
                'fcm_token'=>$user->fcm_token,
                'token' => $token,
                'token_type'=>"bearer"
            ];
             return sendResponse(200,'password changed successfully',$data);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error..',[]);
        }
    }
    public function resendCode(User $user){
        try{
            $code=rand(1000,9999);
                $user->update([
                    'code'=>$code,
                    'expired_at'=>now()->addMinutes(5)
                ]);
           //     $this->otp->SendSms('966570869472','Rmoz159@0',"966".$user->phone,"فرحتي",'كود تفعيلك في ابليكشن فرحتي:'.$user->code);
                return sendResponse(200,'resend code successfully to 0'.$user->phone,['phone'=>$user->phone,'code'=>$code]);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error',[]);
        }
    }

    public function getAuthUser(){
        return sendResponse(200,'Auth User Retrieved Successfully',new UserResource(User::find(auth()->id())));
    }

}
