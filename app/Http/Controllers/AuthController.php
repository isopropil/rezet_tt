<?php

namespace App\Http\Controllers;

use App\Contracts\IUserRepository;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class AuthController extends AbstractController {
    use ValidatesRequests;

    /**
     * Sign up new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSignup(SignupRequest $request) {
        $data = $request->validated();

        try {
            $user = UserService::createUser($data);
            Auth::login($user);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }

        return $this->successResponse([]);
    }

    /**
     * Log in by credentials
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(LoginRequest $request) {
        $data = $request->validated();

        if (Auth::attempt($data)) {
            return $this->successResponse([]);
        }

        return $this->errorResponse([]);
    }

    /**
     * Log out a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogout() {
        Auth::logout();
        return $this->successResponse([]);
    }

    /**
     * Retrieve authenticated user data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserData(Request $request) {
        $resp = [
            'authenticated' => Auth::check()
        ];
        $user = Auth::user();
        if ($user) {
            $resp = array_merge($resp, $user->toArray());
        }
        return $this->successResponse($resp);
    }

    /**
     * Redirect to google auth
     * @return mixed
     */
    public function getGoogleLogin() {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getGoogleCallback(IUserRepository $repository) {
        $user = UserService::fromGoogleAuth($repository);
        if ($user) {
            Auth::login($user);
            return redirect()->intended('/home');
        }
        return 'Google login failed';
    }

}
