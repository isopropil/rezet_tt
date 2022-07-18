<?php


namespace App\Services;


use App\Contracts\IUserRepository;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class UserService {

    /**
     * Create new user with a data
     */
    public static function createUser(array $data) {
        $data['password'] = User::hashPassword($data['password']);
        $user = new User($data);
        $user->save();
        return $user;
    }

    /**
     * Create/retrieve user by google auth
     * @return User
     */
    public static function fromGoogleAuth(IUserRepository $repository) {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = $repository->byGoogleId($googleUser->id);
            if (!$user) {
                $user = new User();
                $user->email = $googleUser->email;
                $user->name = $googleUser->name ?? $googleUser->nickname ?? 'Anonymous';
                $user->password = '[MustBeChanged]';
                $user->google_id = $googleUser->id;
                $user->save();
            }
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

}
