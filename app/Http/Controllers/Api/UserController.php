<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Auth;

class UserController extends Controller
{
    //login function api
    public function login(Request $request)
    {
      $credentials = $request->only('email','password');
      if(Auth::attempt($credentials)){
      $token = Str::random(80);
      Auth::user()->api_token = $token;
      Auth::user()->save();
      return response()->json(['token' => $token], 200);
      }
      return response()->json(['status' => 'wrong credentials!'], 403);
    }

        //register function api
        public function register(Request $request)
        {
           
          $token = Str::random(80);
          User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'api_token' => $token,
           ]);
          return response()->json(['token' => $token], 200);
          
          //return response()->json(['status' => 'wrong credentials!'], 403);
        }
    //verying token
    public function verify(Request $request)
    {
      return $request->user()->only('api_token');
    }

    //returning all users
    public function index()
    {
      return response()->json(User::all(),200);
    }

    public function destroy($id)
    {
      $user = User::find($id)->delete();
      return response()->json(['users' => User::all()], 200);
    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->user_role = $request->user_role;
        $user->save();
        return response()->json(['users' => User::all()], 200);
    }
}
