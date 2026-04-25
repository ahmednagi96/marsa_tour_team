<?php 

namespace App\DTOs;

use App\Models\User;

readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $phone
    )
    {
    }

    /** @param User $user
     * 
     *  @return UserDto
     */
    
    public static function fromEloquentModel(User $user): UserDto
    {
        return new self($user->id, $user->name, $user->email,$user->phone);
    }
}