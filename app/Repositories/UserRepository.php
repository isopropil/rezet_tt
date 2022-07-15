<?php


namespace App\Repositories;


use App\Contracts\IUserRepository;
use App\Models\User;

class UserRepository implements IUserRepository {

    /**
     * @inheritdoc
     */
    public function byEmail($email) {
        return User::where('email', $email)->first();
    }

    /**
     * @inheritdoc
     */
    public function byGoogleId($googleId) {
        return User::where('google_id', $googleId)->first();
    }

}
