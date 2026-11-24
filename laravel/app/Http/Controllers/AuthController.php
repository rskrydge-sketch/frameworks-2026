<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'     => [
                'required',
                'string',
                'max:255'
            ],
            'email'    => [
                'required',
                'email',
                'max:180',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
            ],
        ]);

        $user = User::create($data);

        return response()->json([
            'id'    => $user->id,
            'email' => $user->email
        ], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'string'
            ],
        ]);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'error'   => 'invalid_credentials',
                'message' => 'Невірний email або пароль',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    private function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->getTTL() * 60,
            // секунд
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json(null, 204);
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        try {
            $token = auth('api')->refresh();

        } catch (JWTException $e) {
            return response()->json([
                'error'   => 'refresh_failed',
                'message' => $e->getMessage(),
            ], 401);
        }
        return $this->respondWithToken($token);
    }

}
