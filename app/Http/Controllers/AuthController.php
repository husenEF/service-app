<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

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
                'password' => 'required|min:6'
            ]
        );

        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;

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

    /**ord');

        $user = User::where('email', $email)->first();

        if ($user === null) {
            return response()->json(
                [
     * @name login
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

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
}
