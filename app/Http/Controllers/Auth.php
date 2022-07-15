<?php

namespace App\Http\Controllers;

use App\Contracts\IUserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Socialite\Facades\Socialite;

class Auth extends BaseController {
    use ValidatesRequests;

    /**
     * Sign up new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSignup(Request $request) {
        $data = $request->validate([
            'email' => 'required|email|unique:users|max:254',
            'name' => 'required|max:254',
            'password' => 'required|max:254',
        ]);

        try {
            $data['password'] = User::hashPassword($data['password']);
            $user = new User($data);
            $user->fill($data);
            $user->save();
            \Illuminate\Support\Facades\Auth::login($user);

        } catch (\Exception $e) {
            return \response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return \response()->json([
            'status' =>'ok',
            'payload' => []
        ]);
    }

    /**
     * Log in by credentials
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request) {
        $data = $request->validate([
            'email' => 'required|email|max:254',
            'password' => 'required|max:254',
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($data)) {
            return \response()->json([
                'status' =>'ok',
                'payload' => []
            ]);
        }

        return \response()->json([
            'status' =>'error',
            'payload' => []
        ]);
    }

    /**
     * Log out a user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLogout() {
        \Illuminate\Support\Facades\Auth::logout();
        return \response()->json([
            'status' =>'ok',
            'payload' => []
        ]);
    }

    /**
     * Retrieve authenticated user data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserData(Request $request) {
        $resp = [
            'authenticated' => \Illuminate\Support\Facades\Auth::check()
        ];
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user) {
            $resp = array_merge($resp, $user->toArray());
        }
        return response()->json($resp);
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

            \Illuminate\Support\Facades\Auth::login($user);
            return redirect()->intended('/home');
        } catch (\Exception $e) {
            return 'Google login failed';
        }
    }

}
