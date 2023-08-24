<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function createUser(array $data) {
        // Check if user with provided email already exists
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            // Create a new user
            $user = User::create([
                'name' => $data['name'],
                'gender' => $data['gender'],
                'email' => $data['email'],
                'homepage' => $data['homepage']
            ]);
        } else {
            $user->update([
                'name' => $data['name'],
                'gender' => $data['gender'],
                'homepage' => $data['homepage']
            ]);
        }

        return $user;
    }
}
