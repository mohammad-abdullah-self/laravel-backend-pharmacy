<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidation;
use App\Http\Requests\RegisterValidation;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login']]);
    }

    public function register(RegisterValidation $request)
    {

        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return response()->json(['errors' => ['email' => 'Bad Request']], 400);
        }

        $user->userdetail()->create();
        $user->assignRole('User');
        return response()->json(['success' => 'Registration Successful'], 201);
    }

    public function login(LoginValidation $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->getAuthPassword())) {
            return response()->json(['errors' => ['email' => "These credentials do not match our records."]], 403);
        }

        if (!$token = auth()->attempt(['email' => $user->email, 'password' => $request->password])) {
            return response()->json(['errors' => ['email' => "Unauthorized"]], 401);
        }

        return $this->respondWithToken($token);
    }

    public function user()
    {
        return new UserResource(auth()->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Logout Successful'], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL(),
        ]);
    }
}
