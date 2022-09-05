<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            "username" => "required|string|unique:users,username",
            "first_name" => "string",
            "last_name" => "string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string|confirmed",
        ]);
        $user = User::create([
            "username" => $fields["username"],
            "first_name" => $fields["first_name"],
            "last_name" => $fields["last_name"],
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"]),
            "user_type_id" => 1
        ]);

        $token = $user->createToken("myapptoken")->plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token,
        ];

        event(new Registered($user));

        return ["responseData" => $response, "status" => 201];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $request->session()->invalidate();
        return ["message" => "Token destroyed", "status" => 204];
    }

    public function getRememberedUser(Request $request)
    {
        $request->validate(["remember_token" => "required|string"]);
        $remember_token = $request["remember_token"];
        $user = User::query()->where("remember_token", "=", $remember_token)->first();
        if (!$user) {
            return response(["message" => "User not found"], 404);
        }
        $request->session()->regenerate();
        $session_id = $request->session()->getId();
        $token = $user->createToken("myapptoken")->plainTextToken;
        User::where("id", $user["id"])->update(["session_id" => $session_id]);
        $userData = Auth::loginUsingId($user["id"], true); 
        $response = [
            "user" => $user,
            "token" => $token,
            "session" => $session_id
        ];
        
        return ["responseData" => $response, "status" => 201];
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string",
            "remember_me" => "nullable|boolean"
        ]);

        $user = User::where("email", $fields["email"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response(["message" => "Bad credentials"], 401);
        }

        $token = $user->createToken("myapptoken")->plainTextToken;
        $rememberMe = $fields["remember_me"] ?? false;

        if (!Auth::attempt(["email" => $fields["email"], "password" => $fields["password"]], $rememberMe)) {
            return response(["message" => "Login failed, please try again later"], 422);
        }

        if ($user["email_verified_at"] == null) {
            return response(["message" => "Email not verified"], 403);
        }
        $request->session()->regenerate();
        $session_id = $request->session()->getId();
        User::where("id", $user["id"])->update(["session_id" => $session_id]);
        $response = [
            "user" => $user,
            "token" => $token,
            "session" => $session_id
        ];
        
        return ["responseData" => $response, "status" => 201];
    }
}
