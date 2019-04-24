<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

use App\Http\Controllers\api\v1\HistoryController;


class AuthController extends Controller
{
    /**
     * @name register
     */
    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => "required",
                "email" => 'required|email|unique:users',
                'password' => 'required|min:6',
                'roles' => "required"
            ]
        );

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $roles = $request->input("roles");

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->roles = $roles;

        if ($user->save()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Register Success!',
                    'data' => $user
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Register gagal!',
                    'data' => ""
                ],
                400
            );
        }
    }

    /**
     * @name login
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $user = User::where('username', $username)->first();

        if ($user === null) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Email tidak ditemukan',
                    'data' => ""
                ]

            );
        }

        if (Hash::check($password, $user->password)) {
            $apiToken = base64_encode(str_random(40));
            $user->update([
                'api_token' => $apiToken
            ]);
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Login Succes!',
                    'data' => [
                        'user' => $user,
                        'api_token' => $apiToken
                    ]
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Login gagal!',
                    'data' => [$user->password, $password]
                ],
                400
            );
        }
    }

    static function getUser($token = null)
    {
        if ($token == null)
            return false;
        $apiToken = explode(" ", $token);
        return User::where('api_token', $apiToken[1])->first();
    }
}
