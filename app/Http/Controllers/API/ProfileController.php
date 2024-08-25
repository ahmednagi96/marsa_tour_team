<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function getAuthFavourite(){
        try{
        $user=auth('sanctum')->user();
          $data=[
            'offers'=>OfferResource::collection($user->offerFavourite ?: []),
            'tours'=> $user->tourFavourite->map(function($tour){
                return $tour->getHomeData();
            }),
          ] ;
       return sendResponse(200,'Auth Favourite Retrieved Successfully ',$data);
        }catch(\Exception $ex){
            return sendResponse(500,'internal server error !',null);
        }
    }
    public function index()
    {
        try {
            $user = auth()->user();
            return sendResponse(200, 'user retrieved successfully', new UserResource($user));
        } catch (\Exception $e) {
            return sendResponse(500, 'internal srever error.', []);
        }
    }
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => ['required','unique:users,email,'.auth()->id()],
                'country'=>'required|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,jfif,svg',

            ],
        );
        if ($validator->fails()) {
            return SendResponse('422', 'validation Error..', $validator->errors());
        }
        $user = auth()->user();
        if ($user) {
            $user->update([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'country'=>$request->input('country'),
            ]);
            if($request->hasFile('photo')){
                if(isset($user->photo)){
                    $photo_path=public_path('images/'.$user->photo);
                    unlink($photo_path);
                }
                $user->photo=uploadPhoto('photo','users','image',$request);
                $user->save();
            }
            return sendResponse(200, 'user updated successfully', new UserResource($user));
        }
        return sendResponse(404, 'user not found', []);
    }
    public function updatePassword(Request $request){
        try{
            $validator = Validator::make(
                $request->all(),
                [
                   'old_password'=>'required',
                   'new_password'=>['required','confirmed',Password::min(8)->numbers()
                   //->uncompromised()
                   ],
                ],
            );
            if ($validator->fails()) {
                return SendResponse('422', 'validation Error..', $validator->errors());
            }
            $user=auth()->user();
            if($request->has('old_password') && Hash::check($request->input('old_password'),$user->password)){
                if($request->old_password==$request->new_password){
                    return sendResponse(401,'you can\'t use old password as new password',null);
                }
                $user->password=Hash::make($request->new_password);
                $user->save();
              return sendResponse(200,'password changed successfully!!',new UserResource($user));
            }else{
                return sendResponse(401,'old password is wrong ',null);

            }


        }
        catch(\Exception $ex){
            return sendResponse(500,'internal server error!!',null);
        }
    }
    public function delete()
    {
        $user =auth('sanctum')->user();
        if($user){
            if(isset($user->photo)){
                $photo_path=public_path('images/'.$user->photo);
                unlink($photo_path);
            }
            $user->tokens()->delete();
            $user->delete();
            return sendResponse(200, 'user deleted', null);
        }else{
            return sendResponse(404, 'not found user', null);

        }

    }
}
