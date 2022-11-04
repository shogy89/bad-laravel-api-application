<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if ($validation->fails()){
            return response()->json('Invalid Inputs', 403);
        }


        $user = DB::table('users')->where('email', $request->email)->get();
        if (count($user) > 0){
            return response()->json([
                'message' => 'User already exists'
            ]); 
        }


        $user_id = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = bin2hex(random_bytes(10));

        DB::table('bearer_tokens')->insert([
            'user_id' => $user_id,
            'token' => $token,
            'expiry_time' => Carbon::now()->addHours(5)
        ]);

        return response()->json([
            'message' => 'Registration successfull',
            'token' => $token
        ]);
    }




    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if ($validation->fails()){
            return response()->json('Invalid Inputs', 403);
        }

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)){
            return response()->json('Wrong Details', 403); 
        }

        $token = bin2hex(random_bytes(10));

        DB::table('bearer_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expiry_time' => Carbon::now()->addHours(5)
        ]);

        return response()->json([
            'message' => 'login successfull',
            'token' => $token
        ]);
    }
}