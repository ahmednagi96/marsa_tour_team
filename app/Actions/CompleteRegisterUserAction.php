<?php

namespace App\Actions;

use App\Exceptions\InvalidOtpException;
use App\Exceptions\PhoneAlreadyExistsException;
use App\Http\Resources\AuthResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CompleteRegisterUserAction
{
    public function handle($data):AuthResource
    {
        $tokenKey = 'auth:reg_token:' . $data['registration_token'];
        $phone = Redis::get($tokenKey);
        if (!$phone) {
             throw new InvalidOtpException(__('auth.registration_expired'), 422);
        }
        return DB::transaction(function () use ($data, $phone, $tokenKey) {
            if (User::where('phone', $phone)->exists()) {
                Redis::del($tokenKey);
                throw new PhoneAlreadyExistsException();
            }
            $user = User::create([
                'name'           => $data['name'],
                'phone'          => $phone,
                'email'          => $data['email'],
                'country_id'     => $data['country_id'],
                'is_active'      => true,
                'phone_verified_at' => now(),
                'last_login_at'     => now()
            ]);
            Redis::del($tokenKey);
            return  new AuthResource([
                'user'  => new UserResource($user->load("country")),
                'token' => $user->token()
            ]);
        });
    }
}
