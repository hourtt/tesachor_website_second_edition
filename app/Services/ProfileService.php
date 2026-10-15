<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
   
    public function update(User $user, array $data): User
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        return $user;
    }
    public function delete(User $user){
          $user->delete();
    }
}
