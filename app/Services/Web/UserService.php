<?php

namespace App\Services\Web;

use App\Models\User;

class UserService
{
    public function create($inputs)
    {
        return User::create([
            'name' => $inputs['name'],
            'email' => $inputs['email'],
            'password' => $inputs['password'],
        ]);
    }

    public function findByEmail($email)
    {
        return User::where('email', '=', $email)->first();
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function verifyUser($user)
    {
        $user->email_verified_at = now();

        return $user->save();
    }
}
