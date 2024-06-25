<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logIn(Request $req)
    {
        try {
            if (
                !Auth::attempt([
                    "email" => $req->email,
                    "password" => $req->password
                ])
            ) {
                return response()->json([
                    "auth" => false,
                    "message" => "Datos de acceso inválidos"
                ], 200);
            }

            $token = Auth::user()->createToken("authToken")->accessToken;
            $user = User::find(Auth::id(), [
                "id",
                "name",
                "email",
                "username",
            ]);

            return response()->json([
                "auth" => true,
                "message" => "Datos de acceso validos",
                "token" => $token,
                "user" => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "auth" => false,
                "message" => "ERR. " . $th
            ], 200);
        }
    }

    public function logOut(Request $request)
    {
        try {
            $request->user()->tokens->each(function ($token, $key) {
                $token->delete();
            });

            return response()->json([
                "success" => true,
                "message" => "Sesión finalizada correctamente"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "ERR. " . $th
            ], 200);
        }
    }
}