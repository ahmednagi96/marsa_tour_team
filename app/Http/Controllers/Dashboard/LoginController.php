<?php

namespace App\Http\Controllers\Dashboard;

use Validator;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AdminResource;
use Illuminate\Validation\Rule;
class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function Admins()
    {
        return response()->json([
            "data"=> AdminResource::collection( Admin::all())
        ]);
    }
    public function register(Request $request)
    {
        $AdminData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:admins',
            'role'=> ['required', Rule::in(['Admin', 'SuperAdmin'])],
            'password'=>'required|min:8'
        ]);
        $Admin = Admin::create([
            'name' => $AdminData['name'],
            'email' => $AdminData['email'],
            'role'=>$AdminData['role'],
            'password' => Hash::make($AdminData['password']),
        ]);
        return response()->json([
            'message' => 'Admin Created ',
        ]);
    }
    public function login(Request $request)
    {
    $Data = $request->validate([
    'email'=>'required|string|email',
    'password'=>'required|min:8'
    ]);

    if(!Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){
        return response()->json([
            'message' => 'Unauthorized'
        ],401);
    }
    $user= auth()->guard('admin')->user();
    $token = $user->createToken("password" ,['*'], now()->addMonth())->plainTextToken;

    return response()->json([
     'UserLogin'=>$user,
    'access_token' => $token,
    'token_type' => 'Bearer',
    ]);

    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(["massage"=>'Logged out successfully']);
    }
    public function delete($id)
    {
        if(Admin::where('role','SuperAdmin')){
            Admin::findOrFail($id)->delete();
            return sendResponse(200,"Deleted successfully");
        }else{
            return sendResponse(403,"You do not have permission.");
        }

    }

}

