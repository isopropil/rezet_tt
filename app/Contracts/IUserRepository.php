<?php


namespace App\Contracts;


use App\Models\User;

interface IUserRepository {

    /**
     * Retrieve user by email
     * @param $email
     * @return User
     */
    public function byEmail($email);

    /**
     * Retrieve user by google ID
     * @param $googleId
     * @return User
     */
    public function byGoogleId($googleId);

}
