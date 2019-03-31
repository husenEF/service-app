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

    public function update(Request $request, $id)
    {
        $roles = ["name" => 'required'];
        if (isset($request->password)) {
            $roles["password"] = 'required|min:6';
        }

        $this->validate($request, $roles);

        // dd($roles);
        $user = User::find($id);
        $user->name = $request->input("name");
        if (isset($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if ($user->save()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Update User Success!',
                    'data' => $user,
                    "debug" => $request->all()
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Update User gagal!',
                    'data' => ""
                ],
                400
            );
        }
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not Found!',
                    'data' => ""
                ],
                404
            );
        }
        if ($user->delete()) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Delete Success!',
                    'data' => ""
                ],
                204
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Delete User gagal!',
                    'data' => ""
                ],
                404
            );
        }
    }
}
