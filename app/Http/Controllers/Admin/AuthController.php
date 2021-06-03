<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ProfilePasswordUpdateRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Http\Requests\Admin\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Cookie;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $isAdmin = $request->url() === route("admin.register");

        $user = User::create($request->only(['email', 'first_name', 'last_name']) + ['password' => Hash::make($request->password), 'is_admin' => $isAdmin]);

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => "Invalid credentials"], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $isLoggingInAsAdmin = $request->url() === route('admin.login');

        if ($isLoggingInAsAdmin && !$user->is_admin)
            return response()->json(['error' => 'Access Denied'], Response::HTTP_UNAUTHORIZED);

        $scope = $isLoggingInAsAdmin ? 'admin' : 'ambassador';

        $jwt = $user->createToken('token', [$scope])->plainTextToken;

        $cookie = cookie('jwt', $jwt, 60 * 24);

        return response()->json([
            'message' => 'success',
            'user' => $user
        ])->withCookie($cookie);
    }

    public function user(Request $request)
    {
        return new UserResource($request->user());
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response()->json(['message' => 'success'])->withCookie($cookie);
    }

    public function updateInfo(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->update($request->only('email', 'first_name', 'last_name'));

        return response()->json($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(ProfilePasswordUpdateRequest $request)
    {
        $user = $request->user();
        $user->update(['password' => Hash::make($request->input('password'))]);

        return response()->json($user, Response::HTTP_ACCEPTED);
    }
}
