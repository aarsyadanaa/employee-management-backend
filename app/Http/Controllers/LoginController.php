<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class LoginController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return response()->json([
                'response_code' => 404,
                'message' => 'email sudah ada',
            ]);       
        }
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('remember_token')->plainTextToken;
        $success['name'] =  $user->name;
   
        return response()->json([
            'response_code' => 200,
            'message' => 'Register Berhasil',
            'content' => $success
        ]);
    }
   
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('authToken')->plainTextToken; 
            $success['name'] =  $user->name;
   
            return response()->json([
                'response_code' => 200,
                'message' => 'Login Berhasil',
                'token' => $success['token'],
                'user' => $user
            ]);
        } 
        else{ 
            return response()->json([
                            'response_code' => 404,
                            'message' => 'Username atau Password Tidak Ditemukan!'
                        ]);
        } 
    }

    public function logout(Request $request)
    {
        // Cek jika pengguna terautentikasi
        if (Auth::check()) {
            // Menghapus token pengguna yang sedang login
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'response_code' => 200,
                'message' => 'Logout Berhasil'
            ]);
        }

        return response()->json([
            'response_code' => 401,
            'message' => 'Tidak ada pengguna yang terautentikasi'
        ], 401);
    }
}

