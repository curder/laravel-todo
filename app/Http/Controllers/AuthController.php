<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use function response;

/**
 * Class AuthController
 * @package App\Http\Controllers
 */
class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response|JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'message' => 'Your credentials are incorrect. Please try again',
                    'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'data' => []
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return response()->json([
            'data' => [
                'token_type' => 'Bearer',
                "expires_in" => 31622400,
                'access_token' => $user->createToken('auth_token')->accessToken,
            ],
            'message' => 'Login successful',
            'status' => Response::HTTP_OK,
        ], Response::HTTP_OK);

    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'data' => [
                'token_type' => 'Bearer',
                "expires_in" => 31622400,
                'access_token' => $user->createToken('auth_token')->accessToken,
            ],
            'message' => 'Registration successful',
            'status' => Response::HTTP_CREATED,
        ], Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

}
