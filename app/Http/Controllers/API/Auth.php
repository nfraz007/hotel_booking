<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


use Validator;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller{
    public function __construct(){
        DB::beginTransaction();
    }

    public function login(Request $request){
        try{
            // validating every thing
            $validator=Validator::make($request->all(), [
                'username' => $this->get_rule("username"),
                'password' => $this->get_rule("password")
            ]);
            if($validator->fails()){
                throw new Exception($validator->errors()->first());
            }

            $username = $request->input("username");
            $password = $request->input("password");
            
            $user = DB::table("user")->where("username", $username)->first();

            if(!$user) throw new Exception("Invalid Username.");
            if($user->status == "INACTIVE") throw new Exception("Your account is inactive.");
            
            if(!Hash::check($password, $user->password)) throw new Exception("Invalid Password.");

            // get the token for this user
            $token = $this->jwt_encode($user->id);
            // put this token in user_token table
            DB::table("user")->where("id", $user->id)->update(["token" => $token, "last_login" => date("Y-m-d H:i:s")]);
            // put token in session
            session()->put("token", "$token");
            // put token in static token
            static::$token = $token;
            
            DB::commit();
            return response()->json(array(
                "status" => true,
                "message" => "You are successfully login.",
                "token" => $token,
                "redirect" => ""
            ));
        }catch(Exception $e){
            DB::rollback();
            return response()->json(array(
                "status" => false,
                "message" => $e->getMessage()
            ));
        }
    }

    public function logout(Request $request){
        session()->put("token", "");

        return redirect()->route("login");
    }
}
