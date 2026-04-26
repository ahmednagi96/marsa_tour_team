<?php 

namespace App\Warehouses\Auth;

use App\Models\User;

class UserManager

{
    public function updateLastLoginAt(User $user){
        $user->last_login_at=now();
        $user->save();
    }
}