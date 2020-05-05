<?php

namespace App\Http\Controllers;

use App\User;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\Client\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $response = Http::post(config('services.passport.login_endpoint'), [
                'grant_type'    => 'password',
                'client_id'     => config('services.passport.client_id'),
                'client_secret' => config('services.passport.client_secret'),
                'username'      => $request->username,
                'password'      => $request->password,
            ]);
            return $response;
        } catch (BadResponseException $e) {
            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'username'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return User::create([
            'name'     => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

}
