<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use App\Models\Seo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\SeoTrait;

class AuthController extends Controller
{
    use GeneralTrait, SeoTrait;

    public function __construct()
    {
        $this->middleware('jwt:api', ['except' => ['login', 'register']]);
    } // end of __construct

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = auth('api')->user();

        $response_data = [
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ];
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            $response_data,
            $this->seo($seo->title, 'login', $seo->description, $seo->keywords),
            __('User logged in successfully')
        );
    } // end of login

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    } // end of logout

    public function refresh()
    {
        $response_data = [
            'user' => auth('api')->user(),
            'authorisation' => [
                'token' => auth('api')->refresh(),
                'type' => 'bearer',
            ]
        ];
        $seo = Seo::first();
        return $this->apiSuccessResponse(
            $response_data,
            $this->seo($seo->title, 'refresh', $seo->description, $seo->keywords),
            __('Token refreshed successfully')
        );
    } // end of refresh
}
