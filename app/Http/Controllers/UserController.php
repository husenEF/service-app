<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User Found',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not Found',
                'data' => ""
            ], 404);
        }
    }


    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => "required",
            "email" => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

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
                    'message' => 'Created User Success!',
                    'data' => $user
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Create User gagal!',
                    'data' => ""
                ],
                400
            );
        }
    }
}
